<?php

namespace Tests\Feature;

use App\Services\UserService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');

    }

    public function testRegister()
    {
        $response = $this->json('POST','/api/register', [
            'name' => 'Jonathan Beltrão',
            'email' => 'jona@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);


        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type'
        ]);
    }

    public function testLogin()
    {

        $userService = new UserService();

        $userService->create([
            'name' => 'Jonathan Beltrão',
            'email' => 'jona@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response = $this->json('POST','/api/login', [
            'email' => 'jona@gmail.com',
            'password' => '12345678',
        ]);


        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type'
        ]);
    }

    public function testLogout()
    {
        $userService = new UserService();

        $userService->create([
            'name' => 'Jonathan Beltrão',
            'email' => 'jona@gmail.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $login = $this->json('POST','/api/login', [
            'email' => 'jona@gmail.com',
            'password' => '12345678',
        ]);

        $loginContent = $login->decodeResponseJson();

        $logout = $this->json('GET', '/api/logout', [], [
            'Authorization' => $loginContent['token_type'] . " " . $loginContent['access_token']
        ]);

        $logout->assertStatus(200);
    }
}
