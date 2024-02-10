<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Ramsey\Uuid\Uuid;


class CreateTransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create a new user
     *
     * @return void
     */
    public function testCreateTransaction()
    {
        $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid['payer_id'],
            'receiver_id' => $uuid['receiver_id'],
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function testCreateTransactionWithNoPayerError()
    {
        $uuidPayer = Uuid::uuid4()->toString();
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidReceiver]);

        Wallet::factory()->create(['user_id' =>  $uuidReceiver]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuidPayer,
            'receiver_id' => $uuidReceiver,
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['payer_id']);
    }

    public function testCreateTransactionWithPayerFormatError()
    {
        $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => 1111,
            'receiver_id' => $uuid['receiver_id'],
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['payer_id']);
    }

    public function testCreateTransactionWithoutPayerError()
    {
        $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'receiver_id' => $uuid['receiver_id'],
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['payer_id']);
    }

    public function testCreateTransactionWithPayerReceiverError()
    {
        $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid['payer_id'],
            'receiver_id' => $uuid['payer_id'],
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['receiver_id']);
    }

    public function testCreateTransactionWithNoReceiverError()
    {
        $uuidPayer = Uuid::uuid4()->toString();
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidPayer]);

        Wallet::factory()->create(['user_id' =>  $uuidPayer]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuidPayer,
            'receiver_id' => $uuidReceiver,
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['receiver_id']);
    }

    public function testCreateTransactionWithReceiverFormatError()
    {
        $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid['payer_id'],
            'receiver_id' => 1111,
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['receiver_id']);
    }

    public function testCreateTransactionWithoutReceiverError()
    {
        $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid['payer_id'],
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['receiver_id']);
    }

    public function testCreateTransactionWithPayerTypeError()
    {
        $uuid = $this->createUserAndWallet(2);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid['payer_id'],
            'receiver_id' => $uuid['receiver_id'],
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateTransactionWithValueZeroError()
    {
        $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid['payer_id'],
            'receiver_id' => $uuid['receiver_id'],
            'value' => 0,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['value']);
    }

    public function testCreateTransactionWithValueTypeError()
    {
        $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid['payer_id'],
            'receiver_id' => $uuid['receiver_id'],
            'value' => "R$12.00",
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['value']);
    }

    public function testCreateTransactionWithValueNotValidError()
    {
        $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid['payer_id'],
            'receiver_id' => $uuid['receiver_id'],
            'value' => 12.99999999,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['value']);
    }

    public function testCreateTransactionWithBalanceError()
    {
        $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid['payer_id'],
            'receiver_id' => $uuid['receiver_id'],
            'value' => 1000,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateTransactionWithoutValueError()
    {
       $uuid = $this->createUserAndWallet();

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid['payer_id'],
            'receiver_id' => $uuid['receiver_id'],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['value']);
    }

    public function createUserAndWallet($type = 1)
    {
        $uuidPayer = Uuid::uuid4()->toString();
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidPayer, 'type' => $type]);
        User::factory()->create(['id' => $uuidReceiver]);

        Wallet::factory()->create(['user_id' => $uuidPayer]);
        Wallet::factory()->create(['user_id' =>  $uuidReceiver]);

        return [
            'payer_id' => $uuidPayer,
            'receiver_id' => $uuidReceiver
        ];
    }
}
