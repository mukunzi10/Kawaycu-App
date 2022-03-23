<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function login(Request $request)
    { 
        $this->validate($request,[
            'phone'=>["required"],
            'password'=>"required"
        ]);
         $user=User::where('phone',$request->phone)->first();

        if($user)
        {
           
            if(Hash::check($request->password,$user->password))
            {
                $token=$user->createToken('special')->plainTextToken;
                 
                return response()->json([
                    "message"=>"LogedIn",
                    "data"=>[
                        'name'=>$user->name,
                        'phone'=>$user->phone,
                        'token'=>$token,
                        'type'=>$user->type
                    ]
                ]);
            }
            else
            {
                return response()->json([
                    "message"=>"Incorrect password"
                 ],403);
            }


        }
        else
        {
            return response()->json([
                   "message"=>"Incorrect phone"
            ],403);
        }
    }
    public function store(Request $request)
    {
        $this->validate($request,[
             "name"=>"required",
             "phone"=>"required",
             "password"=>"required"
        ]);

        $date=date('Y-m-d H:m:i');

        $user=User::create([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'password'=>Hash::make($request->password),
            'verify_code'=>"102078",
            "phone_verified_at"=>$date,
            'type'=>'admin'
        ]);

        if($user)
        {
            return response()->json([
                "message"=>"Successfull Done!"
            ],200);
        }
        else
        {
            return response()->json([
                "message"=>"Failed"
            ],403);
        }

    }
    public function verify(Request $request)
    {
           $this->validate($request,[
               'phone'=>"required",
               "code"=>"required"
           ]);
            $date=date('Y-m-d H:m:i');
           if(User::where('verify_code',$request->code)->where('phone',$request->phone)->exists())
           {
                 $id=User::where('verify_code',$request->code)->where('phone',$request->phone)->first()->id;
                 $update=DB::table('users')->where('id',$id)->update(["phone_verified_at"=>$date]);
                 return response()->json([
                    "message"=>"successfull"
                    ]);
           }
           else
           {
                return response()->json([
                        "message"=>"Does not match"
                 ],403);
           }
    }
    public function changePassword(Request $request)
    {
        $this->validate($request,[
             'phone'=>"required",
             "password"=>"required"
        ]);
        $passw=Hash::make($request->password);
        DB::table('users')->where('phone',$request->phone)->update(["password"=>$passw]);
        return response()->json([
            'message'=>"success"
        ]);
    }
}
