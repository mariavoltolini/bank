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
        $uuidPayer = Uuid::uuid4()->toString();
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidPayer, 'type' => 1]);
        User::factory()->create(['id' => $uuidReceiver]);

        Wallet::factory()->create(['user_id' => $uuidPayer]);
        Wallet::factory()->create(['user_id' =>  $uuidReceiver]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuidPayer,
            'receiver_id' => $uuidReceiver,
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
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidReceiver]);

        Wallet::factory()->create(['user_id' =>  $uuidReceiver]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => 1111,
            'receiver_id' => $uuidReceiver,
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['payer_id']);
    }

    public function testCreateTransactionWithoutPayerError()
    {
        $uuidPayer = Uuid::uuid4()->toString();
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidPayer, 'type' => 1]);
        User::factory()->create(['id' => $uuidReceiver]);

        Wallet::factory()->create(['user_id' => $uuidPayer]);
        Wallet::factory()->create(['user_id' =>  $uuidReceiver]);

        $response = $this->postJson('/api/v1/transactions', [
            'receiver_id' => $uuidReceiver,
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['payer_id']);
    }

    public function testCreateTransactionWithPayerReceiverError()
    {
        $uuid = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuid, 'type' => 1]);

        Wallet::factory()->create(['user_id' => $uuid]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuid,
            'receiver_id' => $uuid,
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
        $uuidPayer = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidPayer, 'type' => 1]);

        Wallet::factory()->create(['user_id' => $uuidPayer]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuidPayer,
            'receiver_id' => 1111,
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['receiver_id']);
    }

    public function testCreateTransactionWithoutReceiverError()
    {
        $uuidPayer = Uuid::uuid4()->toString();
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidPayer, 'type' => 1]);
        User::factory()->create(['id' => $uuidReceiver]);

        Wallet::factory()->create(['user_id' => $uuidPayer]);
        Wallet::factory()->create(['user_id' =>  $uuidReceiver]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuidPayer,
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['receiver_id']);
    }

    public function testCreateTransactionWithPayerTypeError()
    {
        $uuidPayer = Uuid::uuid4()->toString();
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidPayer, 'type' => 2]);
        User::factory()->create(['id' => $uuidReceiver]);

        Wallet::factory()->create(['user_id' => $uuidPayer]);
        Wallet::factory()->create(['user_id' =>  $uuidReceiver]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuidPayer,
            'receiver_id' => $uuidReceiver,
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testCreateTransactionWithValueZeroError()
    {
        $uuidPayer = Uuid::uuid4()->toString();
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidPayer, 'type' => 1]);
        User::factory()->create(['id' => $uuidReceiver]);

        Wallet::factory()->create(['user_id' => $uuidPayer]);
        Wallet::factory()->create(['user_id' =>  $uuidReceiver]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuidPayer,
            'receiver_id' => $uuidReceiver,
            'value' => 0,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['value']);
    }

    public function testCreateTransactionWithValueTypeError()
    {
        $uuidPayer = Uuid::uuid4()->toString();
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidPayer, 'type' => 1]);
        User::factory()->create(['id' => $uuidReceiver]);

        Wallet::factory()->create(['user_id' => $uuidPayer]);
        Wallet::factory()->create(['user_id' =>  $uuidReceiver]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuidPayer,
            'receiver_id' => $uuidReceiver,
            'value' => "R$12.00",
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['value']);
    }

    public function testCreateTransactionWithoutValueError()
    {
        $uuidPayer = Uuid::uuid4()->toString();
        $uuidReceiver = Uuid::uuid4()->toString();

        User::factory()->create(['id' => $uuidPayer, 'type' => 1]);
        User::factory()->create(['id' => $uuidReceiver]);

        Wallet::factory()->create(['user_id' => $uuidPayer]);
        Wallet::factory()->create(['user_id' =>  $uuidReceiver]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $uuidPayer,
            'receiver_id' => $uuidReceiver,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['value']);
    }
}
