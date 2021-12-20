<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Account;

class TransferTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_transfer()
    {
        $user = User::factory()->create();

        $account1 = Account::factory()->create();
        $account2 = Account::factory()->create();

        $response = $this->actingAs($user)        
                    ->postJson('/api/transfers', 
                    ['sender_account_number'=>$account1->account_number,
                    'destination_account_number'=>$account2->account_number,
                    'sender_account_type'=>$account1->type,
                    'destination_account_type' =>$account2->type,
                    'charge'=>100.00,
                    'cuurency'=>'XAF',
                    'transaction_pin'=>$user->transaction_pin,
                    'amount'=>2000.00,
                    'user_id'=>$user->id]);

        $response
        ->assertStatus(201)
        ->assertJson([
            'message' => 'transfer created successfully',
        ]);
    }

    public function test_get_transfers()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)        
                    ->getJson('/api/transfers');

        $response
        ->assertStatus(200);

    }

    public function test_transaction_pin()
    {
        $user = User::factory()->create();

        $account1 = Account::factory()->create();
        $account2 = Account::factory()->create();

        $response = $this->actingAs($user)        
                    ->postJson('/api/transfers', 
                    ['sender_account_number'=>$account1->account_number,
                    'destination_account_number'=>$account2->account_number,
                    'sender_account_type'=>$account1->type,
                    'destination_account_type' =>$account2->type,
                    'charge'=>100.00,
                    'cuurency'=>'XAF',
                    'transaction_pin'=>'22222',
                    'amount'=>2000.00,
                    'user_id'=>$user->id]);

        $response
        ->assertStatus(400)
        ->assertJson([
            'message' => 'Incorrect transaction Pin',
        ]);

    }

    

}
