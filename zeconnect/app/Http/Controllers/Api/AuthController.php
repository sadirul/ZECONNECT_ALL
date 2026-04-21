<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function userLogin(Request $request): JsonResponse
    {
        return $this->loginByRole($request, 'user');
    }

    public function agentLogin(Request $request): JsonResponse
    {
        return $this->loginByRole($request, 'agent');
    }

    public function me(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('api')->user();

        if (! $user->is_active) {
            JWTAuth::invalidate(JWTAuth::getToken(), true);

            return response()->json([
                'message' => 'This account is inactive.',
            ], 403);
        }

        return response()->json([
            'message' => 'Authenticated user fetched successfully.',
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
                'message' => 'This account is inactive.',
            ], 403);
        }

        $token = JWTAuth::refresh(JWTAuth::getToken());

        return $this->respondWithToken($token, 'Token refreshed successfully.');
    }

    public function logout(): JsonResponse
    {
        JWTAuth::invalidate(JWTAuth::getToken(), true);

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    protected function loginByRole(Request $request, string $role): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        /** @var \App\Models\User|null $user */
        $user = User::query()
            ->where('email', $request->string('email')->toString())
            ->where('role', $role)
            ->first();

        if (! $user || ! Auth::guard('api')->validate([
            'email' => $request->string('email')->toString(),
            'password' => $request->string('password')->toString(),
        ])) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are invalid.'],
            ]);
        }

        if (! $user->is_active) {
            return response()->json([
                'message' => 'This account is inactive.',
            ], 403);
        }

        $token = JWTAuth::claims([
            'role' => $role,
        ])->fromUser($user);

        return $this->respondWithToken($token, ucfirst($role).' login successful.');
    }

    protected function respondWithToken(string $token, string $message): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::guard('api')->user();
        $ttlMinutes = config('jwt.ttl');
        $expiresInSeconds = $ttlMinutes ? (int) $ttlMinutes * 60 : null;

        return response()->json([
            'message' => $message,
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
        ]);
    }
}
