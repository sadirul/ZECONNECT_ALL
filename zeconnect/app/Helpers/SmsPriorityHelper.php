<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Throwable;

class SmsPriorityHelper
{
    public static function sendOtp($mobile, $otp, $priority = 'whatsapp'): array
    {
        $priority = strtolower((string) $priority);

        $messageId = [
            'sms' => config('sms.fast2sms.sms.templates.otp.message_id'),
            'whatsapp' => config('sms.fast2sms.whatsapp.templates.otp.message_id'),
        ];

        $variablesValues = [
            'sms' => (string) $otp,
            'whatsapp' => (string) $otp,
        ];

        return self::send(
            phone: (string) $mobile,
            messageId: $messageId,
            variablesValues: $variablesValues,
            mediaUrl: null,
            documentFilename: null,
            priority: $priority
        );
    }

    public static function send(
        string $phone,
        array $messageId,
        array $variablesValues = [],
        ?string $mediaUrl = null,
        ?string $documentFilename = null,
        string $priority = 'whatsapp'
    ): array {
        $priority = strtolower($priority);

        $first = $priority === 'sms' ? 'sms' : 'whatsapp';
        $second = $first === 'sms' ? 'whatsapp' : 'sms';

        try {
            $firstResult = self::sendVia(
                $first,
                $phone,
                $messageId,
                $variablesValues,
                $mediaUrl,
                $documentFilename
            );

            $firstSuccess = ! empty($firstResult['return']);
            self::logFast($phone, $first, $firstSuccess);

            if ($firstSuccess) {
                return [
                    'status' => true,
                    'sent_via' => $first,
                    'response' => $firstResult,
                ];
            }

            $secondResult = self::sendVia(
                $second,
                $phone,
                $messageId,
                $variablesValues,
                $mediaUrl,
                $documentFilename
            );

            $secondSuccess = ! empty($secondResult['return']);
            self::logFast($phone, $second, $secondSuccess);

            if (! $secondSuccess) {
                Log::channel('sms')->error('OTP_TOTAL_FAILED', [
                    'm' => $phone,
                    'first_channel' => $first,
                    'second_channel' => $second,
                    't' => now()->timestamp,
                ]);
            }

            return [
                'status' => $secondSuccess,
                'sent_via' => $secondSuccess ? $second : null,
                'response' => $secondResult,
            ];
        } catch (Throwable $exception) {
            Log::channel('sms')->critical('OTP_EXCEPTION', [
                'm' => $phone,
                'error' => $exception->getMessage(),
                't' => now()->timestamp,
            ]);

            return [
                'status' => false,
                'sent_via' => null,
                'response' => [
                    'return' => false,
                    'error' => $exception->getMessage(),
                ],
            ];
        }
    }

    private static function sendVia(
        string $channel,
        string $phone,
        array $messageId,
        array $variablesValues,
        ?string $mediaUrl,
        ?string $documentFilename
    ): array {
        try {
            if ($channel === 'sms') {
                $result = SmsHelper::sendSms(
                    phone: $phone,
                    messageId: (string) ($messageId['sms'] ?? ''),
                    variablesValues: (string) ($variablesValues['sms'] ?? '')
                );

                return is_array($result) ? $result : ['return' => (bool) $result];
            }

            $whatsAppHelperClass = 'App\\Helpers\\WhatsAppHelper';

            if (! class_exists($whatsAppHelperClass)) {
                return ['return' => false, 'error' => 'WhatsAppHelper not available'];
            }

            $result = $whatsAppHelperClass::send(
                $messageId['whatsapp'] ?? null,
                '91'.$phone,
                is_array($variablesValues['whatsapp'] ?? null)
                    ? $variablesValues['whatsapp']
                    : explode('|', (string) ($variablesValues['whatsapp'] ?? '')),
                $mediaUrl,
                $documentFilename
            );

            return is_array($result) ? $result : ['return' => (bool) $result];
        } catch (Throwable $exception) {
            Log::channel('sms')->error('OTP_CHANNEL_EXCEPTION', [
                'channel' => $channel,
                'mobile' => $phone,
                'error' => $exception->getMessage(),
                't' => now()->timestamp,
            ]);

            return ['return' => false, 'error' => $exception->getMessage()];
        }
    }

    private static function logFast(string $mobile, string $channel, bool $status): void
    {
        Log::channel('sms')->info('OTP_FAST', [
            'm' => $mobile,
            'c' => $channel,
            's' => $status,
            't' => now()->timestamp,
        ]);
    }
}
