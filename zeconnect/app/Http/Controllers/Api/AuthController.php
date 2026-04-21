<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthForgotPasswordRequest;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Requests\Auth\AuthResendOtpRequest;
use App\Http\Requests\Auth\AuthResetPasswordRequest;
use App\Http\Requests\Auth\AuthUpdateProfileRequest;
use App\Http\Requests\Auth\AuthVerifyOtpRequest;
use App\Helpers\SmsPriorityHelper;
use App\Models\Metadata;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(AuthLoginRequest $request): JsonResponse
    {
        return $this->loginFromUsersTable($request);
    }

    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $mobile = $this->normalizeMobile($validated['mobile']);

        try {
            DB::beginTransaction();

            $existingUser = User::query()->where('mobile', $mobile)->first();

            if ($existingUser && $existingUser->is_verified) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Mobile number already registered',
                    'data' => null,
                ], 400);
            }

            $otp = (string) random_int(100000, 999999);

            $user = User::query()->updateOrCreate(
                ['mobile' => $mobile],
                [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'address' => $validated['address'],
                    'password' => $validated['password'],
                    'role' => 'user',
                    'is_active' => true,
                    'is_verified' => false,
                    'otp' => $otp,
                    'otp_expires_at' => now()->addMinutes(5),
                    'reset_otp' => null,
                    'reset_otp_expires_at' => null,
                ]
            );

            $this->sendOtp($mobile, $otp);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'msg' => 'Signup successful. OTP sent successfully.',
                'data' => [
                    'mobile' => $mobile,
                    'role' => $user->role,
                ],
            ], 201);
        } catch (QueryException $exception) {
            DB::rollBack();
            $sqlMessage = $exception->getMessage();

            if (str_contains($sqlMessage, 'users_email_unique')) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'This email is already registered.',
                    'data' => null,
                ], 400);
            }

            if (str_contains($sqlMessage, 'users_mobile_unique')) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'This mobile number is already registered.',
                    'data' => null,
                ], 400);
            }

            Log::error('User registration failed: '.$exception->getMessage(), [
                'mobile' => $mobile,
                'role' => 'user',
            ]);

            return response()->json([
                'status' => 'error',
                'msg' => 'Registration failed. Please try again later.',
                'data' => null,
            ], 500);
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error('User registration failed: '.$exception->getMessage(), [
                'mobile' => $mobile,
                'role' => 'user',
            ]);

            return response()->json([
                'status' => 'error',
                'msg' => 'Registration failed. Please try again later.',
                'data' => null,
            ], 500);
        }
    }

    public function verifyOtp(AuthVerifyOtpRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $mobile = $this->normalizeMobile($validated['mobile']);
        $user = User::query()->where('mobile', $mobile)->first();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Account not found.',
                'data' => null,
            ], 404);
        }

        if ((string) $user->otp !== $validated['otp']) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Invalid OTP.',
                'data' => null,
            ], 422);
        }

        if (! $user->otp_expires_at || now()->greaterThan($user->otp_expires_at)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'OTP has expired.',
                'data' => null,
            ], 422);
        }

        $user->update([
            'is_verified' => true,
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'msg' => 'Account verified successfully.',
            'data' => [
                'mobile' => $mobile,
            ],
        ]);
    }

    public function resendOtp(AuthResendOtpRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $mobile = $this->normalizeMobile($validated['mobile']);
        $user = User::query()->where('mobile', $mobile)->first();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Account not found.',
                'data' => null,
            ], 404);
        }

        if ($user->is_verified) {
            return response()->json([
                'status' => 'success',
                'msg' => 'Account already verified.',
                'data' => [
                    'mobile' => $mobile,
                ],
            ]);
        }

        $otp = (string) random_int(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        $this->sendOtp($mobile, $otp);

        return response()->json([
            'status' => 'success',
            'msg' => 'OTP resent successfully.',
            'data' => [
                'mobile' => $mobile,
            ],
        ]);
    }

    public function forgotPassword(AuthForgotPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $mobile = $this->normalizeMobile($validated['mobile']);
        $user = User::query()->where('mobile', $mobile)->first();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Account not found.',
                'data' => null,
            ], 404);
        }

        if (! $user->is_verified) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Please verify your account first.',
                'data' => null,
            ], 403);
        }

        $otp = (string) random_int(100000, 999999);

        $user->update([
            'reset_otp' => $otp,
            'reset_otp_expires_at' => now()->addMinutes(5),
        ]);

        $this->sendOtp($mobile, $otp);

        return response()->json([
            'status' => 'success',
            'msg' => 'Reset OTP sent successfully.',
            'data' => [
                'mobile' => $mobile,
            ],
        ]);
    }

    public function resetPassword(AuthResetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $mobile = $this->normalizeMobile($validated['mobile']);
        $user = User::query()->where('mobile', $mobile)->first();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Account not found.',
                'data' => null,
            ], 404);
        }

        if ((string) $user->reset_otp !== $validated['otp']) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Invalid OTP.',
                'data' => null,
            ], 422);
        }

        if (! $user->reset_otp_expires_at || now()->greaterThan($user->reset_otp_expires_at)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'OTP has expired.',
                'data' => null,
            ], 422);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
            'reset_otp' => null,
            'reset_otp_expires_at' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'msg' => 'Password reset successful.',
            'data' => [
                'mobile' => $mobile,
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('api')->user();

        if (! $user->is_active) {
            JWTAuth::invalidate(JWTAuth::getToken(), true);

            return response()->json([
                'status' => 'error',
                'msg' => 'This account is inactive.',
                'data' => null,
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'msg' => 'Authenticated user fetched successfully.',
            'data' => [
                'id' => $user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'address' => $user->address,
                'profile_pic' => $user->profile_pic,
                'role' => $user->role,
                'is_active' => $user->is_active,
            ],
        ]);
    }

    public function refresh(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('api')->user();

        if (! $user->is_active) {
            JWTAuth::invalidate(JWTAuth::getToken(), true);

            return response()->json([
                'status' => 'error',
                'msg' => 'This account is inactive.',
                'data' => null,
            ], 403);
        }

        $token = JWTAuth::refresh(JWTAuth::getToken());

        return $this->respondWithToken($token, 'Token refreshed successfully.', $user);
    }

    public function updateProfile(AuthUpdateProfileRequest $request): JsonResponse
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::guard('api')->user();

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Unauthenticated.',
                'data' => null,
            ], 401);
        }

        $validated = $request->validated();

        if ($request->hasFile('profile_pic')) {
            if (! empty($user->profile_pic)) {
                Storage::disk('public')->delete($user->profile_pic);
            }

            $validated['profile_pic'] = $request->file('profile_pic')->store('users', 'public');
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'profile_pic' => $validated['profile_pic'] ?? $user->profile_pic,
        ]);

        $user->refresh();

        return response()->json([
            'status' => 'success',
            'msg' => 'Profile updated successfully.',
            'data' => [
                'id' => $user->id,
                'uuid' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'address' => $user->address,
                'profile_pic' => $user->profile_pic,
                'role' => $user->role,
                'is_active' => $user->is_active,
            ],
        ]);
    }

    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken(), true);

        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out successfully.',
            'data' => null,
        ]);
    }

    protected function loginFromUsersTable(AuthLoginRequest $request): JsonResponse
    {
        $mobile = $this->normalizeMobile($request->string('mobile')->toString());
        $password = $request->string('password')->toString();

        /** @var \App\Models\User|null $user */
        $user = User::query()
            ->where('mobile', $mobile)
            ->first();

        if (! $user || ! Auth::guard('api')->validate([
            'mobile' => $mobile,
            'password' => $password,
        ])) {
            return response()->json([
                'status' => 'error',
                'msg' => 'The provided credentials are invalid.',
                'data' => null,
            ], 400);
        }

        if (! in_array($user->role, ['user', 'agent'], true)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'This account is not allowed to use this login endpoint.',
                'data' => null,
            ], 403);
        }

        if (! $user->is_active) {
            return response()->json([
                'status' => 'error',
                'msg' => 'This account is inactive.',
                'data' => null,
            ], 403);
        }

        if (! $user->is_verified) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Please verify your account by OTP first.',
                'data' => null,
            ], 403);
        }

        $token = JWTAuth::claims([
            'role' => $user->role,
        ])->fromUser($user);

        return $this->respondWithToken($token, ucfirst($user->role).' login successful.', $user);
    }

    protected function respondWithToken(string $token, string $message, ?User $authUser = null): JsonResponse
    {
        /** @var \App\Models\User|null $user */
        $user = $authUser ?? Auth::guard('api')->user();
        if (! $user) {
            $user = JWTAuth::setToken($token)->toUser();
        }

        if (! $user) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Unable to resolve authenticated user.',
                'data' => null,
            ], 401);
        }

        $ttlMinutes = config('jwt.ttl');
        $expiresInSeconds = $ttlMinutes ? (int) $ttlMinutes * 60 : null;

        return response()->json([
            'status' => 'success',
            'msg' => $message,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expiresInSeconds,
                'user' => [
                    'id' => $user->id,
                    'uuid' => $user->uuid,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'address' => $user->address,
                    'profile_pic' => $user->profile_pic,
                    'role' => $user->role,
                    'is_active' => $user->is_active,
                ],
            ],
        ]);
    }

    protected function normalizeMobile(string $mobile): string
    {
        return preg_replace('/\D+/', '', $mobile) ?? $mobile;
    }

    protected function sendOtp(string $mobile, string $otp): void
    {
        $sendSms = (bool) config('sms.fast2sms.sms.send_sms', false);

        if (! $sendSms) {
            Log::info('OTP generated (SMS disabled).', [
                'mobile' => $mobile,
                'otp' => $otp,
            ]);

            return;
        }

        try {
            $priority = Metadata::getMetaData('otp_channel_priority', 'whatsapp');
            SmsPriorityHelper::sendOtp($mobile, $otp, (string) $priority);
        } catch (\Throwable $exception) {
            Log::error('Failed to send OTP SMS', [
                'mobile' => $mobile,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
