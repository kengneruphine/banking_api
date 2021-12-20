<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Account::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Json
     */
    public function store(Request $request)
    {

        $userId = Auth::id();
        $user = User::where('id',$userId)->firstOrFail();

        //generate account number
        $account_number = strtoupper($user->first_name[0]) . strtoupper($user->last_name[0]) . rand(10000000,99999999);

        $request->validate([
            'type' => 'required|string'
        ]);

        $account = Account::create([
            'account_number' => $account_number,
            'balance' =>  $request->balance ?  $request->balance : 5000.00,  //setting default balance of 5000.00
            'type' => $request ->type,
            'user_id' => $userId
        ]); 
        return response()->json(['message'=>"Account created successfully", 'account' => $account],201);   
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Request $request, int $id): Response
    {
        $account = Account::where('id',$id)->first();
        if(!$account){
            return response()->json(['message'=>"Account not found"],404);
        } else{
        return response(['message'=> 'Account retrieved successfully','account' =>$account ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        $request->validate(['type' => 'required|string']);

        $account = Account::find($id);
        if ($account !== null) {
            $account->update(['message' => $request->input('type')]);
            return response($account);
        }

        return response(['message' => 'Account does not exist']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        return response(Account::destroy($id));
    }
}