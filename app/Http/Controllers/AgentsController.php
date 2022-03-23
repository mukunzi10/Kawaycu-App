<?php

namespace App\Http\Controllers;

use App\Models\agents;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AgentsController extends Controller
{
    
    public function index($option)
    {
        //
        if($option=="all")
        {
            $data=DB::table('agents')->join('sites','sites.site_id','agents.site_id')->select(DB::raw('site_name,agents.*'))->get();
            $TotalNumber=DB::table('agents')->join('sites','sites.site_id','agents.site_id')->count();
            return response()->json([
                'message'=>"Successfull",
                "data"=>$data,
                "total"=>$TotalNumber
            ]);
        }
        else
        {
            $data=DB::table('agents')->join('sites','sites.site_id','agents.site_id')->where('agents.site_id',$option)->get();
            $TotalNumber=DB::table('agents')->join('sites','sites.site_id','agents.site_id')->where('agents.site_id',$option)->count();
            return response()->json([
                'message'=>"Successfull",
                "data"=>$data,
                "total"=>$TotalNumber
            ]);
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
            'name'=>"required",
            "gender"=>"required",
            "site"=>"required|numeric",
            "phone"=>"required",
            "sector"=>"required",
            "cell"=>"required",
            "village"=>"required"
        ]);
        $id=$request->id;
        if($id)
        {
            agents::where('agent_id',$id)->update([
                'agent_names'=>$request->name,
                'agent_phone'=>$request->phone,
                'agent_gender'=>$request->gender,
                'site_id'=>$request->site,
                'sector'=>$request->sector,
                'cell'=>$request->cell,
                'village'=>$request->village,
                'agent_status'=>'Make Change'
            ]);

            return response()->json([
                'message'=>"Successfull Done!"
            ]);

        }
        else
        {
            if(agents::where('agent_phone',$request->phone)->exists())
            {
                  return response()->json([
                      'message'=>"Agent with this phone exists"
                  ],403);
            }
            if(agents::where('site_id',$request->site)->exists())
            {
                  return response()->json([
                      'message'=>"Agent with this site exists"
                  ],403);
            }
            else
            {
                $saved=agents::create([
                    'agent_names'=>$request->name,
                    'agent_phone'=>$request->phone,
                    'agent_gender'=>$request->gender,
                    'site_id'=>$request->site,
                    'sector'=>$request->sector,
                    'cell'=>$request->cell,
                    'village'=>$request->village,
                    'agent_status'=>"Make Change"
                ]);
                if($saved)
                {
                    $user=User::create([
                        'name'=>$request->name,
                        'phone'=>$request->phone,
                        'password'=>Hash::make('12345'),
                        'verify_code'=>"102078",
                        'type'=>'agent'
                    ]);
                }
    
                return response()->json([
                    'message'=>"Successfull Done!"
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
     * @param  \App\Models\agents  $agents
     * @return \Illuminate\Http\Response
     */
    public function show(agents $agents)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\agents  $agents
     * @return \Illuminate\Http\Response
     */
    public function edit(agents $agents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\agents  $agents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, agents $agents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\agents  $agents
     * @return \Illuminate\Http\Response
     */
    public function delete($agentId)
    {
        $delete=DB::table('agents')->where('agent_id',$agentId)->delete();

        if($delete)
        {
            return response()->json([
                "message"=>"Successfull"
              ]);
        }
    }

    public function status($agentId)
    {
        $status=DB::table('agents')->where('agent_id',$agentId)->first()->agent_status;

        if($status=="Deactive")
        {
            DB::table('agents')->where('agent_id',$agentId)->update(['agent_status'=>'Active']);
            return response()->json([
                "message"=>"Successfull"
              ]);
        }
        else
        {
            DB::table('agents')->where('agent_id',$agentId)->update(['agent_status'=>'Deactive']);
            return response()->json([
                "message"=>"Successfull"
              ]);
        }
    }
}
