<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create a new user
     *
     * @return void
     */
    public function testCreateUser()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'email' => $user->email,
            'document' => $user->document,
            'password' => 'password',
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'document' => $user->document,
            'type' => $user->type,
        ]);
    }

    public function testCreateUserWithTypeError()
    {
        $user = User::factory()->make();

        $type = 'test';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'email' => $user->email,
            'document' => $user->document,
            'password' => 'password',
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['type']);
    }

    public function testCreateUserWithDocumentError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'email' => $user->email,
            'document' => '123456789.3',
            'password' => 'password',
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['document']);
    }

    public function testCreateUserWithPasswordError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'email' => $user->email,
            'document' => $user->document,
            'password' => '1234',
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['password']);
    }
}
