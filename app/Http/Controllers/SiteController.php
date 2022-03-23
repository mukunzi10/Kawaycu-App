<?php

namespace App\Http\Controllers;

use App\Models\site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data=site::all();
        return response()->json([
            "message"=>"Successfull Done",
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
            'name'=>"required",
            "sector"=>"required",
            "cell"=>"required",
            "village"=>"required"
        ]);

         $name=ucwords($request->name);
         $siteId=$request->id;

         if($siteId)
         {
            site::where('site_id',$siteId)->update([
                'site_name'=>$name,
                'sector'=>$request->sector,
                'cell'=>$request->cell,
                'village'=>$request->village,
                'site_status'=>'Change Record',


                
            ]);
         }
         else
         {
            if(site::where('site_name',$name)->exists())
            {
                  return response()->json([
                     'message'=>"Site exists"
                  ],403);
            }
            else
            {
                site::create([
                    'site_name'=>$name,
                    'sector'=>$request->sector,
                    'cell'=>$request->cell,
                    'village'=>$request->village,
                    'site_status'=>"Change Record"
                    
                ]);
                return response()->json([
                    'message'=>"Successfull"
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
     * @param  \App\Models\site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(site $site)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(site $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, site $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\site  $site
     * @return \Illuminate\Http\Response
     */
    public function delete($siteId)
    {
        //
        DB::table('sites')->where('site_id',$siteId)->delete();
        return response()->json([
          "message"=>"Successfull"
        ]);
    }
}
