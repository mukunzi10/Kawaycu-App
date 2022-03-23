<?php

namespace App\Http\Controllers;

use App\Models\payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon;
use Illuminate\Support\Facades\Http;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function siteReport(Request $request)
    {
        $this->validate($request,[
            'phone'=>"required",
        ]);

        $phone=$request->phone;
        $date=strtotime($request->date);
        $month=date('m');


        if($request->date!="")
        {
           $month=date('m',$date);
        }
        if(DB::table('agents')->where('agent_phone',$phone)->exists())
        {
            $site=DB::table('agents')->where('agent_phone',$phone)->first()->site_id;

            $farmers=DB::table('farmers')->where('site_id',$site)->where(DB::raw('month(created_at)',$month))->count();
            $payments=DB::table('balances')->where('site_id',$site)->sum('bTotal_amount');
            $Harvest=DB::table('harvests')->where('site_id',$site)->sum('harvest_quantity');
            
            return response()->json([
                "message"=>"Successfull",
                "farmers"=>$farmers,
                "payments"=>$payments,
                "harvest"=>$Harvest,
                "month"=>$month
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

    public function transactions()
    {



        $data=DB::table('payments')->join('farmers','farmers.farmer_id','payments.farmer_id')->join('sites','farmers.site_id','sites.site_id')->select(DB::raw("payments.*,farmer_firstname,farmer_lastname,farmer_gender,farmer_phone,site_name"))->get();

        return response()->json([
            "message"=>"success",
            "data"=>$data
        ]);  
    }
    public function create(Request $request)
    {
        //
         $this->validate($request,[
            'amount'=>"required",
            'farmer'=>"required"
         ]);

         if($balance=DB::table('balances')->where('farmer_id',$request->farmer)->exists())
         {
            $balance=DB::table('balances')->where('farmer_id',$request->farmer)->first()->bTotal_amount;
            $balanceId=DB::table('balances')->where('farmer_id',$request->farmer)->first()->balance_id;
            $bHarvest=DB::table('balances')->where('farmer_id',$request->farmer)->first()->bTotal_harvest;
            $phone=DB::table('farmers')->where('farmer_id',$request->farmer)->first()->farmer_phone;
            
               $newbalance=$balance-$request->amount;
             if($request->amount>$balance)
             {
                  return response()->json([
                       "message"=>"Failed,amount paid is greater than balance".$balance,
                  ],403);
             }
             else
             {
                   $saved=payments::create([
                    'amount_to_be_paid'=>$balance,
                    'paid_amount'=>$request->amount,
                    'balance'=>$newbalance,
                    'total_harvest'=>$bHarvest,
                    'farmer_id'=>$request->farmer
                   ]);
                   
                   $update=DB::table('balances')->where('farmer_id',$request->farmer)->update(['bTotal_amount'=>$newbalance]);
                   $fname=DB::table('farmers')->where('farmer_id',$request->farmer)->first()->farmer_firstname;
                   $lname=DB::table('farmers')->where('farmer_id',$request->farmer)->first()->farmer_lastname;
                   $names=$fname." ".$lname;

                   if($saved)
                   {
                        DB::table('balances')->where('balance_id',$balanceId)->update(["bTotal_harvest"=>"0","bTotal_amount"=>$newbalance]);
                        
                        $key=config('app.sms_key');

                        $response=Http::withHeaders([
                            'x-api-key'=>$key
                        ])->post('https://api.mista.io/sms',[
                            'to'=>'25'.$phone,
                            'from'=>'kawayacu12',
                            'unicode'=>'0',
                            'sms'=>$names." Ubwishyu bwarangiye bwa". $request->amount."RWF hasigaye angana " .$newbalance." RWF",
                            'action'=>'send-sms'
                        ]);
                        
                        return response()->json([
                            "message"=>"Successfull Done!",
                            "sms"=>$response
                        ]);
                   }
             }
         }
         else
         {
                return response()->json([
                    "message"=>"Farmer not found",
                ],403);
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
     * @param  \App\Models\payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function show(payments $payments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function edit(payments $payments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, payments $payments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\payments  $payments
     * @return \Illuminate\Http\Response
     */
    public function destroy(payments $payments)
    {
        //
    }
}
