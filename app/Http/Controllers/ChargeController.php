<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Charge::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate(['amount' => 'required',
            'low_range' => 'required',
            'high_range' => 'required']);

            //get all charges from the system
            $charges = Charge::all()->toArray();
            $highRange = array_map(function($item){
                
                return $item['high_range'];
            }, $charges);

            if(in_array($request->low_range, $highRange)){
                return response()->json(['message'=>"Low value already exist as high value"],400);
            }

        $charge = Charge::create($request->all());
        return response(['message' => "Charge create", 'charge' => $charge]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        return response(Charge::find($id));
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
        $request->validate([
            'low_range' => 'required',
            'high_range' => 'required']);

        $charge = Charge::find($id);
        if ($charge !== null) {
            $charge->update([
                'low_range' => $request->input('low_range'),
                'high_range' => $request->input('high_range')
            ]);
            return response($charge);
        }

        return response(['message' => 'Charge does not exist']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        return response(Charge::destroy($id));
    }
}