<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_registration()
    // {

    //     $payload =[
    //         'first_name'=>'grace',
    //         'last_name' =>'ken',
    //         'email' => 'tes5@gmail.com',
    //         'phone_number' => 67859045000,
    //         'password' => 'admin',
    //         'password_confirmation'=>'admin',
    //         'transaction_pin'=>"00000"
    //     ];
    //     $response = $this->json('post','/api/register',$payload);

    //     $response->assertStatus(201);
    // }

    public function test_login()
    {
        $user = User::factory()->create([
            'email' => 'joe@gmail.com',
            'password' => 'admin',
        ]);

        $payload = ['email' => 'joe@user.com', 'password' => 'admin'];
        $response = $this->json('post','/api/login',$payload);

        $response->assertStatus(200);
    }
}
