<?php

namespace Tests\Unit;

use App\Services\UserTypeVerificationService;
use Illuminate\Http\Exceptions\HttpResponseException;
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

        $this->expectException(HttpResponseException::class);

        try {
            $service->verify('invalid_type');
        } catch (HttpResponseException $exception) {
            $response = $exception->getResponse();
            $this->assertInstanceOf(JsonResponse::class, $response);
            $this->assertEquals(422, $response->getStatusCode());

            throw $exception;
        }
    }
}
