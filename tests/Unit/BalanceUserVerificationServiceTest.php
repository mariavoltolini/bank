<?php

namespace Tests\Unit\Services;

use App\Services\BalanceUserVerificationService;
use PHPUnit\Framework\TestCase;

class BalanceUserVerificationServiceTest extends TestCase
{
    public function testVerifyWithInsufficientBalance()
    {
        $service = new BalanceUserVerificationService();
        $balance = 10;
        $transactionValue = 15;

        $exceptionThrown = false;
        try {
            $service->verify($balance, $transactionValue);
        } catch (\Exception $e) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testVerifyWithSufficientBalance()
    {
        $service = new BalanceUserVerificationService();
        $balance = 20;
        $transactionValue = 15;

        $exceptionThrown = false;
        try {
            $service->verify($balance, $transactionValue);
        } catch (\Exception $e) {
            $exceptionThrown = true;
        }

        $this->assertFalse($exceptionThrown);
    }
}
