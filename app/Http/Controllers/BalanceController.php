<?php

namespace App\Http\Controllers;

use App\Models\balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     
    public function AdminReport(Request $request)
    {
        //
        $this->validate($request,[
          "option"=>"required"     
        ]);

        $option=$request->option;
        $month=$request->month;
    
            $data=DB::table('balances')->join('farmers','balances.farmer_id','farmers.farmer_id')->join('sites','sites.site_id','farmers.site_id')->get();
            $TotalNumber=DB::table('balances')->join('farmers','balances.farmer_id','farmers.farmer_id')->sum('bTotal_amount');

            return response()->json([
                'message'=>"Successfull",
                "data"=>$data,
                "total"=>$TotalNumber
            ]);
    }
    public function index($phone)
    {
        //

        if(DB::table('agents')->where('agent_phone',$phone)->exists())
        {
            $site=DB::table('agents')->where('agent_phone',$phone)->first()->site_id;
            $result=[];
            $agents=DB::table('farmers')->where('site_id',$site)->get();

            foreach($agents as $key=>$value)
            {

                $get=DB::table('balances')->join('farmers','farmers.farmer_id','balances.farmer_id')->where('farmers.farmer_id',$value->farmer_id)->where('farmers.site_id',$site)->first();
                
                    $data=[
                        "farmer_id"=>$value->farmer_id,
                        "farmer_firstname"=>$value->farmer_firstname,
                        "farmer_lastname"=>$value->farmer_lastname,
                        "balance_harvest"=>$get->bTotal_harvest,
                        'balance_amount'=>$get->bTotal_amount
                        ];
                if($get->bTotal_amount>0)
                {
                    array_push($result,$data);
                }
                
            }
            return response()->json([
                "message"=>"Successfull",
                "data"=>$result
            ]);
        }
        else
        {
            return response()->json([
                "message"=>"Failed"
           ],403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\Models\balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function show(balance $balance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function edit(balance $balance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, balance $balance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\balance  $balance
     * @return \Illuminate\Http\Response
     */
    public function destroy(balance $balance)
    {
        //
    }
}
