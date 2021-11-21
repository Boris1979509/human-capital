<?php

namespace Tests\Feature\Auth;

use Artisan;
use Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sends_token_to_user_who_provided_valid_credentials(): void
    {
        Artisan::call('passport:install');
        $credentials = [
            'email' => 'some@mail.com',
            'password' => 'password'
        ];

        $user = $this->createUser([
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]);

        $response = $this->json('post', route('login'), $credentials);
        $response->assertOk();

        $responseData = $response->json();
        $this->assertEquals(1, $user->tokens()->count());
        $this->assertArrayHasKey('access_token', $responseData);
        $this->assertFalse(auth()->check());
    }
}
