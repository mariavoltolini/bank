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
            'name' => $user->name,
            'email' => $user->email,
            'document' => $user->document,
            'type' => $user->type,
        ]);
    }

    public function testCreateUserWithWithoutNameError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'email' => $user->email,
            'document' => $user->document,
            'password' => 'password',
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['name']);
    }

    public function testCreateUserWithNoNameError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => " ",
            'email' => $user->email,
            'document' => $user->document,
            'password' => 'password',
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['name']);
    }

    public function testCreateUserWithEmailError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'email' => 'ddddd',
            'document' => $user->document,
            'password' => 'password',
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['email']);
    }

    public function testCreateUserWithWithoutEmailError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'document' => $user->document,
            'password' => 'password',
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['email']);
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

    public function testCreateUserWithWithoutTypeError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'email' => $user->email,
            'document' => $user->document,
            'password' => 'password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['type']);
    }

    public function testCreateUserWithDocumentCharacterError()
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

    public function testCreateUserWithDocumentMinError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'email' => $user->email,
            'document' => '1234567911',
            'password' => 'password',
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['document']);
    }

    public function testCreateUserWithDocumentMaxError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'email' => $user->email,
            'document' => '123456791112345',
            'password' => 'password',
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['document']);
    }

    public function testCreateUserWithWithoutDocumentError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'email' => $user->email,
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

    public function testCreateUserWithWithoutPasswordError()
    {
        $user = User::factory()->make();

        $type = $user->type == 1 ? 'user' : 'merchant';

        $response = $this->postJson('/api/v1/users', [
            'name' => $user->name,
            'email' => $user->email,
            'document' => $user->document,
            'type' => $type,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['password']);
    }
}
