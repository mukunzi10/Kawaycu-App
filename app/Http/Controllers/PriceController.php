<?php

namespace App\Http\Controllers;

use App\Models\price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=DB::table('prices')->join('sites','sites.site_id','prices.site_id')->get();

        return response()->json([
            'message'=>"Successfull",
            "data"=>$data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $this->validate($request,[
            'amount'=>"required",
            "site"=>"required"
        ]);

        if(DB::table('prices')->where('site_id',$request->site)->exists())
        {
            DB::table('prices')->where('site_id',$request->site)->update(['price_amount'=>$request->amount]);
            return response()->json([
                'message'=>"Successfull "
            ]);
        }
        else
        {
             price::create([
                'price_amount'=>$request->amount,
                'site_id'=>$request->site 
             ]);

             return response()->json([
                 'message'=>"Successfull "
             ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\price  $price
     * @return \Illuminate\Http\Response
     */
    public function show(price $price)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\price  $price
     * @return \Illuminate\Http\Response
     */
    public function edit(price $price)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(price $price)
    {
        //
    }
}
