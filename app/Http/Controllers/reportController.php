<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class reportController extends Controller
{
    //

    public function reports()
    {     


           $month=date('m');

           $farmers=DB::table("farmers")->count();
           $sites=DB::table("sites")->count();
           $agents=DB::table("agents")->count();
           $harvest=DB::table("harvests")->where(DB::raw("month(created_at)"),$month)->sum('harvest_quantity');
           $payments=DB::table("payments")->where(DB::raw("month(created_at)"),$month)->count();
           $paymentsAmounts=DB::table("payments")->where(DB::raw("month(created_at)"),$month)->sum("paid_amount");

           $data=["farmers"=>$farmers,"sites"=>$sites,"agents"=>$agents,"harvest"=>$harvest,"numberOfPayments"=>$payments,"totalPaymentAmounts"=>$paymentsAmounts];
           return response()->json([
              "data"=>[$sites,$agents,$farmers,$harvest,$payments,$paymentsAmounts]
           ]);
    }
}
