<?php

namespace App\Http\Controllers;

use App\Models\Recharge;
use App\Services\Recharge as ServicesRecharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RechargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $recharge = Recharge::where("number", "LIKE", "%$request->search%")
            ->orderBy("id", "DESC")
            ->paginate(10);

        return response()->json($recharge);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Validator::make($request->all(), [
            "mobileNumber" => "required",
            "amount" => "required",
            "rechargePin" => "required"
        ])->validate();


        switch (Str::substr($request->mobileNumber, 0, 3)) {
            case "013":
                $operator = "GP";
                break;
            case "014":
                $operator = "BL";
                break;
            case "015":
                $operator = "TT";
                break;
            case "016":
                $operator = "AT";
                break;
            case "017":
                $operator = "GP";
                break;
            case "018":
                $operator = "RB";
                break;
            case "019":
                $operator = "BL";
                break;
            default:
                $operator = "unknown";
        }


        $recharge = new Recharge;

        $recharge->number = $request->mobileNumber;
        $recharge->amount = $request->amount;
        $recharge->operator = $operator;
        $recharge->status = "requested";
        $recharge->user_id = 1;

        $recharge->save();



        $rechargeResponse = ServicesRecharge::init()
            ->mobile($recharge->number)
            ->amount($recharge->amount)
            ->account_type("PREPAID")
            ->order_number($recharge->id)
            ->operator($recharge->operator)
            ->recharge();


        // 109 Topup Successful
        // 101 Topup Failed


        return $rechargeResponse;

        return response()->json($rechargeResponse);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recharge  $recharge
     * @return \Illuminate\Http\Response
     */
    public function show(Recharge $recharge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Recharge  $recharge
     * @return \Illuminate\Http\Response
     */
    public function edit(Recharge $recharge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recharge  $recharge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recharge $recharge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recharge  $recharge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recharge $recharge)
    {
        //
    }
}
