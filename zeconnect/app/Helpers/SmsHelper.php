<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsHelper
{
    public static function sendSms(
        string $phone,
        string $messageId,
        string $variablesValues = '',
        bool $flash = false,
        ?string $scheduleTime = null
    ): array {
        try {
            $query = [
                'authorization' => config('sms.fast2sms.sms.api_key'),
                'route' => 'dlt',
                'sender_id' => config('sms.fast2sms.sms.sender_id'),
                'message' => $messageId,
                'numbers' => $phone,
                'flash' => $flash ? '1' : '0',
            ];

            if ($variablesValues !== '') {
                $query['variables_values'] = $variablesValues;
            }

            if ($scheduleTime !== null) {
                $query['schedule_time'] = $scheduleTime;
            }

            $url = 'https://www.fast2sms.com/dev/bulkV2?'.http_build_query($query);
            $response = Http::get($url)->json();

            Log::info('Fast2SMS response', ['response' => $response]);

            return is_array($response) ? $response : ['return' => false];
        } catch (\Throwable $exception) {
            Log::error('Fast2SMS exception', ['error' => $exception->getMessage()]);

            return [
                'return' => false,
                'message' => $exception->getMessage(),
            ];
        }
    }
}
