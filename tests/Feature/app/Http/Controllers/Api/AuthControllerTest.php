<?php

namespace Tests\Feature\app\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    public function test_can_log_in()
    {
        $user = User::factory()->create();

        $params = [
            'email' => $user->email,
            'password' => 'password',
        ];
        $response = $this->postJson(route('login'), $params);

        $response->assertOk();
    }

    public function test_can_register()
    {
        $user = User::factory()->make();

        $params = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'password'
        ];
        $response = $this->postJson(route('register'), $params);

        $response->assertOk();
        $this->assertDatabaseHas('users', ['name' => $user->name, 'email' => $user->email]);

    }

    public function test_can_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson(route('logout'));

        $response->assertStatus(204);

    }

}
