<?php

namespace App\Http\Controllers;

use App\Models\farmers;
use App\Models\balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FarmersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function AdminReport($option)
    {
        //
        if($option=="all")
        {
            $data=DB::table('farmers')->join('sites','sites.site_id','farmers.site_id')->get();
            $TotalNumber=DB::table('farmers')->join('sites','sites.site_id','farmers.site_id')->count();

            return response()->json([
                'message'=>"Successfull",
                "data"=>$data,
                "total"=>$TotalNumber
            ]);
        }
        else
        {
            $data=DB::table('farmers')->join('sites','sites.site_id','farmers.site_id')->where('farmers.site_id',$option)->get();
            $TotalNumber=DB::table('farmers')->join('sites','sites.site_id','farmers.site_id')->where('farmers.site_id',$option)->count();
            return response()->json([
                'message'=>"Successfull",
                "data"=>$data,
                "total"=>$TotalNumber
            ]);
        }
    }
    public function index($phone)
    {
        //
        if(DB::table('agents')->where('agent_phone',$phone)->exists())
        {
            $site=DB::table('agents')->where('agent_phone',$phone)->first()->site_id;

            $data=farmers::where('site_id',$site)->orderBy("farmer_id","DESC")->get();

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
            'firstname'=>"required",
            'lastname'=>"required",
            'gender'=>"required",
            'phone'=>"required",
            'agent_phone'=>"required",
        ]);

        $site=DB::table('agents')->where('agent_phone',$request->agent_phone)->first()->site_id;



        if(farmers::where('farmer_phone',$request->phone)->exists())
        {
            return response()->json([
                 "message"=>"Farmer with this phone exists"
            ],403);
        }
        else
        {
            $saved=farmers::create([
                'farmer_firstname'=>$request->firstname,
                'farmer_lastname'=>$request->lastname,
                'farmer_gender'=>$request->gender,
                'farmer_phone'=>$request->phone,
                'agent_phone'=>$request->agent_phone,
                'site_id'=>$site
            ]);
           
            if($saved)
            {
                $names=$request->firstname." ".$request->lastname;
                $balance=new balance();
                
                $balance->bTotal_harvest=0;
                $balance->bTotal_amount=0;
                $balance->site_id=$site;
                $balance->farmer_id=$saved->farmer_id;
                $balance->save();



                $key=config('app.sms_key');

                $response=Http::withHeaders([
                    'x-api-key'=>$key
                ])->post('https://api.mista.io/sms',[
                    'to'=>'25'.$request->phone,
                    'from'=>'kawayacu12',
                    'unicode'=>'0',
                    'sms'=>$names." kwiyandisha kwanyu kwemejwe",
                    'action'=>'send-sms'
                ]);

                
                return response()->json([
                    "message"=>"Successfull Done!",
                    "response"=>$response
               ]);
    
            }


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
     * @param  \App\Models\farmers  $farmers
     * @return \Illuminate\Http\Response
     */
    public function show(farmers $farmers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\farmers  $farmers
     * @return \Illuminate\Http\Response
     */
    public function edit(farmers $farmers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\farmers  $farmers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, farmers $farmers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\farmers  $farmers
     * @return \Illuminate\Http\Response
     */
    public function destroy(farmers $farmers)
    {
        //
    }
}
