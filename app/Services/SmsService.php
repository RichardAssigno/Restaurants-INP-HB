<?php

namespace App\Services;

class SmsService
{
    private function initRequest(string $url, ?string $post = null): \CurlHandle|bool
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: close']);

        if ($post !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        return $ch;
    }

    public function send(string $recipient, string $message): bool|string
    {
        $parameters = [
            'username' => config('services.sms.username'),
            'password' => config('services.sms.password'),
            'sender' => config('services.sms.sender'),
            'text' => $message,
            'type' => 'text',
            'datetime' => now()->format('Y-m-d H:i:s'),
        ];

        $post = 'to='.rawurlencode($recipient);

        foreach ($parameters as $key => $value) {
            $post .= '&'.$key.'='.rawurlencode($value);
        }

        $ch = $this->initRequest(
            config('services.sms.url'),
            $post
        );

        if ($ch === false) {
            return 'cURL ERROR: initialisation impossible';
        }

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $result = 'cURL ERROR: '.curl_errno($ch).' '.curl_error($ch);
        } else {
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode !== 200) {
                $result = 'HTTP ERROR: '.$httpCode;
            }
        }

        curl_close($ch);

        return $result;
    }

    public function isSuccessful(bool|string|null $response): bool
    {
        if (! is_string($response)) {
            return false;
        }

        $response = trim($response);

        if ($response === '') {
            return false;
        }

        if (stripos($response, 'HTTP ERROR:') === 0 || stripos($response, 'cURL ERROR:') === 0) {
            return false;
        }

        return stripos($response, 'OK') === 0;
    }

    public function formatRecipient(?string $telephone): ?string
    {
        $charactersToRemove = ['"', "'", '-', '_', '|', '/', '.', ':', '\\', '+', ' '];
        $number = str_replace($charactersToRemove, '', trim((string) $telephone));

        if ($number === '' || ! ctype_digit($number)) {
            return null;
        }

        return strlen($number) <= 10 ? '225'.$number : $number;
    }
}
