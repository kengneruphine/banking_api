<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AccountTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)        
                    ->postJson('/api/accounts', ['acount_number'=>'IK57896451','balance'=>5000.00,'type'=>'saving','user_id'=>$user->id]);

        $response
        ->assertStatus(201)
        ->assertJson([
            'message' => 'Account created successfully',
        ]);

    }

    public function test_get_accounts()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)        
                    ->getJson('/api/accounts');

        $response
        ->assertStatus(200);

    }

    public function test_get_account()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)        
                    ->getJson('/api/accounts');

        $response
        ->assertStatus(200);

    }

    
}
