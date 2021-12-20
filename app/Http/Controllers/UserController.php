<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(User::all());
    }


     /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::where('id',$id)->first();
        if(!$user){
            return response()->json(['message'=>"User not found"],404);
        } else{
        return response(['message'=> 'User retrieved successfully','user' =>$user ]);
        }
    } 
    
     /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $user = User::find($id);

        //making sure the user update only his account
        if($userId != $id){
            return response()->json(['message'=>"You dont have write to update this user"],401); 
        }

        $user->update($request->all());
        return response(['message' => 'Record updated', 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $userId = Auth::id();
        $user = User::find($id);

        //making sure the user delete only his account
        if($userId != $id){
            return response()->json(['message'=>"You dont have write to delete this user"],401); 
        }
        return response(User::destroy($id));
    }

    /**
     * Search a specified resource from storage.
     * by first_name, last_name in user
     *
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response
    {
        $searchTerm = $request->input('searchTerm');

        if (isset($searchTerm)) {
            $found = User::where('first_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                ->get();
            return response($found);
        }

        return response(['message' => 'Missing search term'], ResponseAlias::HTTP_NOT_FOUND);
    }

     /**
     * Change transaction pin
     *
     * @param Request $request
     * @return Response
     */
    public function changeTransactionPin(Request $request)
    {
        $fields = $request->validate([
            'password' => 'required',
            'new_pin' => 'required',
        ]);

        $userId = Auth::id();
        $user = User::find($userId);
        if(isset($user["password"])){
            if(Hash::check($request->password, $user["password"])){
                $user->transaction_pin = $request->new_pin;
                $user->save();
                return response()->json(['message'=>"Transaction Pin change successfully"],200); 

            }else{
                return response(['message' => 'Incorrect password'], ResponseAlias::HTTP_NOT_FOUND);
            }
        }else{
            return response()->json(['message'=>"User not found"],404);
        }

    }


       /**
     * Change password
     *
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request)
    {
        $fields = $request->validate([
            'current_pass' => 'required',
            'new_pass' => 'required',
        ]);

        $userId = Auth::id();
        $user = User::find($userId);
        if(isset($user["password"])){
            if(Hash::check($request->current_pass, $user["password"])){
                $user->password = $request->new_pass;
                $user->save();
                return response()->json(['message'=>"Password change successfully"],200); 

            }else{
                return response(['message' => 'Incorrect password'], ResponseAlias::HTTP_NOT_FOUND);
            }
        }else{
            return response()->json(['message'=>"User not found"],404);
        }

    }

    
}
