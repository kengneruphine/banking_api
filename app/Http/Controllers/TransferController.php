<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Charge;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Transfer::latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JSON
     */
    public function store(Request $request)
    {

        $userId = Auth::id();
        $user = User::find($userId);

        $data = $request->all();

        $request->validate([
            'amount' => 'required',
            'destination_account_type' =>'required',
            'sender_account_type'=>'required',
            'destination_account_number'=>'required',
            'sender_account_number'=>'required',
            'transaction_pin'=>'required'
        ]);

        //get transfer charge
        $charge = Charge::where('high_range', '>',$data['amount'])
                          ->where('low_range','<', $data['amount'])
                          ->firstOrFail();

        // check user transaction pin
        if($user->transaction_pin !== $data['transaction_pin']){
            return response()->json(['message'=>"Incorrect transaction Pin"],400);
        }

        $subtraction = $data['amount'] + $charge['amount'];
        $account_sender = Account::where('account_number', $data['sender_account_number'])
                                   ->where('type',$data['sender_account_type'])
                                   ->first();

        $account_destination = Account::where('account_number', $data['destination_account_number'])
                                   ->where('type',$data['destination_account_type'])
                                   ->first();

        //check if sender has enough money in his account
        if($account_sender->balance < $data['amount']){
            return response()->json(['message'=>"Account has insufficient funds"],400);
        }

        $transfer = new Transfer();
        DB::transaction(function () use ($data, $subtraction, $account_sender, $account_destination, $charge, $userId, $transfer) {
            //subtraction from sender
            $account_sender->balance -= $subtraction;
            $account_sender->save();

            //add to receiver
            $account_destination->balance += $data['amount'];
            $account_destination->save();

            //create transfer
            $transfer = Transfer::create([
                'user_id' => $userId,
                'sender_account_number' => $data['sender_account_number'],
                'destination_account_number'=>$data['destination_account_number'],
                'sender_account_type'=>$data['sender_account_type'],
                'destination_account_type'=>$data['destination_account_type'],
                'currency'=>$data['currency'],
                'amount'=>$data['amount'],
                'charge'=> $charge['amount'],
                'status' =>'successful'
            ]);
        });
        
        return response()->json(['message'=>"transfer created successfully", 'transfer' => $transfer],201);  
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Request $request, int $id)
    {
        $transfer = Transfer::where('id',$id)->first();
        if(!$transfer){
            return response()->json(['message'=>"transfer not found"],404);
        } else{
        return response(['message'=> 'transfer retrieved successfully','transfer' =>$transfer ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        return response(Transfer::destroy($id));
    }
}