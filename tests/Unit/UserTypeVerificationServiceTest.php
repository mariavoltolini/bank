<?php

namespace Tests\Unit;

use App\Exceptions\BusinessException;
use App\Services\UserTypeVerificationService;
use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\TestCase;

class UserTypeVerificationServiceTest extends TestCase
{
    public function testVerifyValidUserType()
    {
        $service = new UserTypeVerificationService();

        $result = $service->verify('user');

        $this->assertEquals(1, $result);
    }

    public function testVerifyValidMerchantType()
    {
        $service = new UserTypeVerificationService();

        $result = $service->verify('merchant');

        $this->assertEquals(2, $result);
    }

    public function testVerifyInvalidType()
    {
        $service = new UserTypeVerificationService();

        $this->expectException(BusinessException::class);

        try {
            $service->verify('invalid_type');
        } catch (BusinessException $exception) {
            $response = $exception->getCode();
            $this->assertEquals(422, $response);

            throw $exception;
        }
    }
}
