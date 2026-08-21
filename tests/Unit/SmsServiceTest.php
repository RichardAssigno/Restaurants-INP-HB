<?php

namespace Tests\Unit;

use App\Services\SmsService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SmsServiceTest extends TestCase
{
    #[DataProvider('phoneNumbers')]
    public function test_it_formats_phone_numbers_like_the_sgi_module(?string $value, ?string $expected): void
    {
        $this->assertSame($expected, (new SmsService)->formatRecipient($value));
    }

    public static function phoneNumbers(): array
    {
        return [
            'local number' => ['07 08 09 10 11', '2250708091011'],
            'international number' => ['+225 07 08 09 10 11', '2250708091011'],
            'punctuation' => ['07-08-09-10-11', '2250708091011'],
            'empty value' => ['', null],
            'letters' => ['07AB091011', null],
        ];
    }

    #[DataProvider('apiResponses')]
    public function test_it_recognizes_successful_api_responses(bool|string|null $response, bool $expected): void
    {
        $this->assertSame($expected, (new SmsService)->isSuccessful($response));
    }

    public static function apiResponses(): array
    {
        return [
            'success' => ['OK 12345', true],
            'lowercase success' => ['ok', true],
            'http error' => ['HTTP ERROR: 500', false],
            'curl error' => ['cURL ERROR: timeout', false],
            'empty response' => ['', false],
            'boolean response' => [false, false],
            'null response' => [null, false],
        ];
    }
}
