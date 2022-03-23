<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class messageController extends Controller
{
    //

    public function sendSms(Request $request)
    {
        $this->validate($request,[
            'message'=>"required",
            "recepient"=>"required"
        ]);
          
      
        $rec=json_decode($request->recepient);

        
              foreach( $rec as $k=>$v)
              {
               
               
                $key=config('app.sms_key');
                
                $response=Http::withHeaders([
                    'x-api-key'=>$key
                ])->post('https://api.mista.io/sms',[
                    'to'=>'25'.$v->phone,
                    'from'=>'kawayacu12',
                    'unicode'=>'0',
                    'sms'=>$request->message,
                    'action'=>'send-sms'
                ]);
              }

        
        return response()->json([
            "message"=>"success"
          ]);


    }
}
