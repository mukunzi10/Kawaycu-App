<?php

namespace App\Http\Controllers;

use App\Models\harvest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class HarvestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function AdminReport($option)
    {
           
            $TotalNumber=0;
            $month=date("m");
            $result=[];
           if($option=="all")
           {
            $farmers=DB::table('farmers')->join('sites','sites.site_id','farmers.site_id')->get();
            $TotalNumber=DB::table('harvests')->join('farmers','farmers.farmer_id','harvests.farmer_id')->sum('harvest_quantity');
                foreach($farmers as $key=>$value)
                {

                    $get=DB::table('harvests')->join('farmers','farmers.farmer_id','harvests.farmer_id')->where(DB::raw('month(harvests.created_at)'),$month)->where('farmers.farmer_id',$value->farmer_id)->select(DB::raw('sum(harvest_quantity) as harvest_quantity,sum(harvest_totalPrice) as harvest_totalPrice'))->first();
                    $data=[
                    "farmer_id"=>$value->farmer_id,
                    "farmer_firstname"=>$value->farmer_firstname,
                    "farmer_lastname"=>$value->farmer_lastname,
                    "farmer_gender"=>$value->farmer_gender,
                    "farmer_phone"=>$value->farmer_phone,
                    "harvest_quantity"=>$get->harvest_quantity,
                    "harvest_totalPrice"=>$get->harvest_totalPrice,
                    "site_name"=>$value->site_name,
                    "created_at"=>$value->created_at
                    ];
                    array_push($result,$data);
                }
                return response()->json([
                    "message"=>"Successfull",
                    "data"=>$result,
                    "total"=>$TotalNumber
                ]);
           }
           else
           {
                $farmers=DB::table('farmers')->where('site_id',$option)->get();

                $TotalNumber=DB::table('harvests')->join('farmers','farmers.farmer_id','harvests.farmer_id')->where('farmers.site_id',$option)->sum('harvest_quantity');
               
                foreach($farmers as $key=>$value)
                {
                    $get=DB::table('harvests')->join('farmers','farmers.farmer_id','harvests.farmer_id')->where(DB::raw('month(harvests.created_at)'),$month)->where('farmers.farmer_id',$value->farmer_id)->where('site_id',$option)->select(DB::raw('sum(harvest_quantity) as harvest_quantity,sum(harvest_totalPrice) as harvest_totalPrice'))->first();
                    $data=[
                    "farmer_id"=>$value->farmer_id,
                    "farmer_firstname"=>$value->farmer_firstname,
                    "farmer_lastname"=>$value->farmer_lastname,
                    "farmer_gender"=>$value->farmer_gender,
                    "farmer_phone"=>$value->farmer_phone,
                    "harvest_quantity"=>$get->harvest_quantity,
                    "harvest_totalPrice"=>$get->harvest_totalPrice
                    ];
                    array_push($result,$data);
                }
                return response()->json([
                    "message"=>"Successfull",
                    "data"=>$result,
                    "total"=>$TotalNumber
                ]);
           }
    }
    public function index(Request $request)
    {
        //    
        $start_date=$request->startDate;
        $end_date=$request->endDate;
        $phone=$request->phone;

        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d');   

        if(DB::table('agents')->where('agent_phone',$phone)->exists())
        {
            $site=DB::table('agents')->where('agent_phone',$phone)->first()->site_id;
            $result=[];
            $agents=DB::table('farmers')->where('site_id',$site)->get();

            foreach($agents as $key=>$value)
            {

                $get=DB::table('harvests')->join('farmers','farmers.farmer_id','harvests.farmer_id')->whereBetween(DB::raw('date(harvests.created_at)'),[$weekStartDate,$weekEndDate])->where('farmers.farmer_id',$value->farmer_id)->where('harvests.site_id',$site)->select(DB::raw('sum(harvest_quantity) as harvest_quantity,sum(harvest_totalPrice) as harvest_totalPrice'))->first();
                $data=[
                "farmer_id"=>$value->farmer_id,
                "farmer_firstname"=>$value->farmer_firstname,
                "farmer_lastname"=>$value->farmer_lastname,
                "harvest_quantity"=>$get->harvest_quantity,
                "harvest_totalPrice"=>$get->harvest_totalPrice
                ];
                array_push($result,$data);
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

    public function report($phone)
    {
        //                
        if(DB::table('agents')->where('agent_phone',$phone)->exists())
        {
            $site=DB::table('agents')->where('agent_phone',$phone)->first()->site_id;
            $data=DB::table('harvests')->join('farmers','harvests.farmer_id','farmers.farmer_id')->where('site_id',$site)->get();
            return response()->json([
                "message"=>"Successfull",
                "data"=>$data
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
    public function create(Request $request)
    {
        //
        $this->validate($request,[
            'quantity'=>"required",
            'farmer'=>"required"
        ]);

        $date=date('Y-m-d');

        
        $siteId=DB::table('farmers')->where('farmer_id',$request->farmer)->first()->site_id;

        $phone=DB::table('farmers')->where('farmer_id',$request->farmer)->first()->farmer_phone;
        $balanceHarvest=DB::table('balances')->where('farmer_id',$request->farmer)->first()->bTotal_harvest;
        $balanceAmount=DB::table('balances')->where('farmer_id',$request->farmer)->first()->bTotal_amount;
        $balanceId=DB::table('balances')->where('farmer_id',$request->farmer)->first()->balance_id;
        $initialPrice="100";
        
         if(DB::table('prices')->where('site_id',$siteId)->exists())
         {
             $initialPrice=DB::table('prices')->where('site_id',$siteId)->first()->price_amount;
         }
         $total_amount=$initialPrice*$request->quantity;
        $saved=harvest::create([
            'harvest_quantity'=>$request->quantity,
            'harvest_unitPrice'=>$initialPrice,
            'harvest_totalPrice'=>$total_amount,
            'farmer_id'=>$request->farmer,
            'site_id'=>$siteId
        ]);

        if($saved)
        {
            
            $totalHarvest=$balanceHarvest+$request->quantity;
            $totalAmount=$balanceAmount+$total_amount;

            $fname=DB::table('farmers')->where('farmer_id',$request->farmer)->first()->farmer_firstname;
            $lname=DB::table('farmers')->where('farmer_id',$request->farmer)->first()->farmer_lastname;
            $names=$fname." ".$lname;

            DB::table('balances')->where('balance_id',$balanceId)->update(["bTotal_harvest"=>$totalHarvest,"bTotal_amount"=>$totalAmount]);

            $key=config('app.sms_key');

            $response=Http::withHeaders([
                'x-api-key'=>$key
            ])->post('https://api.mista.io/sms',[
                'to'=>"25".$phone,
                'from'=>'kawayacu12',
                'unicode'=>'0',
                'sms'=>$names." Mwagemuye ibiro :".$request->quantity."kg  Bihwanye ".$total_amount."RWf"." itariki ".$date." Murakoze! from Muhura washing station",
                'action'=>'send-sms'
            ]);

            return response()->json([
                "message"=>"Successfull Done!",
                "sms"=>$response
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
     * @param  \App\Models\harvest  $harvest
     * @return \Illuminate\Http\Response
     */
    public function show(harvest $harvest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\harvest  $harvest
     * @return \Illuminate\Http\Response
     */
    public function edit(harvest $harvest)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\harvest  $harvest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, harvest $harvest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\harvest  $harvest
     * @return \Illuminate\Http\Response
     */
    public function destroy(harvest $harvest)
    {
        //
    }
}
