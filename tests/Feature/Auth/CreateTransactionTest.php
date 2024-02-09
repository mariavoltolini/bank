<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

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
        $payer = User::factory()->create();
        $receiver = User::factory()->create();

        Wallet::factory()->create(['user_id' => $payer->id]);
        Wallet::factory()->create(['user_id' =>  $receiver->id]);

        $response = $this->postJson('/api/v1/transactions', [
            'payer_id' => $payer->id,
            'receiver_id' => $receiver->id,
            'value' => 15,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

    }
}
