<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletUp;
use Illuminate\Http\Request;
use smasif\ShurjopayLaravelPackage\ShurjopayService;

class WalletUpController extends Controller
{


    public function createPayment($amount)
    {
        $walletUp = new WalletUp();
        $shurjopay_service = new ShurjopayService;
        $walletUp->tx_id = $shurjopay_service->generateTxId();
        $walletUp->amount = $amount;
        $walletUp->paid_by = auth()->user()->id;
        $walletUp->save();
        return response()->json(["paymentUrl" => route('payNow',  $walletUp->tx_id)]);
    }

    public function payNow(Request $request, $txid)
    {

        if ($txid == null) {
            return response()->json(["error" => "Invoice ID not provided"]);
        }

        $walletUp = WalletUp::where('tx_id', $txid)->first();

        $shurjopay_service = new ShurjopayService;
        $shurjopay_service->generateTxId(substr($walletUp->tx_id, 3));
        $shurjopay_service->sendPayment($walletUp->amount, route('paymentResponse'));
    }


    public function paymentResponse(Request $request)
    {

        $walletUp = WalletUp::where('tx_id', '=', $request->tx_id)->first();

        if ($walletUp == null) {
            return response()->json(["error" => "Invoice ID not found"]);
        }

        if ($walletUp->status == 'Success') {
            return response()->json(["message" => "Already Payment Completed"]);
        } elseif ($walletUp->status == 'Failed') {
            return response()->json(["status" => "Invoice rejected with ID $request->tx_id. For more information please contact support with the Invoice ID."]);
        } else {
            $walletUp->bank_tx_id = $request->bank_tx_id;
            $walletUp->amount = $request->amount;
            $walletUp->sp_code = $request->sp_code;
            $walletUp->sp_code_des = $request->sp_code_des;
            $walletUp->sp_payment_option = $request->sp_payment_option;
            $walletUp->user_agent = $request->user_agent;
            $walletUp->ip_address = $request->ip_address;
            $walletUp->status = $request->status;
            $walletUp->save();
            if ($request->status == 'Success') {
                $user = User::find($walletUp->paid_by);
                $user->balance += $walletUp->amount;
                $user->save();
                return response()->json(["message" => "Payment Success"]);
            } else {
                return response()->json(["status" => "Payment Failed"]);
            }
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\WalletUp  $walletUp
     * @return \Illuminate\Http\Response
     */
    public function show(WalletUp $walletUp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WalletUp  $walletUp
     * @return \Illuminate\Http\Response
     */
    public function edit(WalletUp $walletUp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WalletUp  $walletUp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WalletUp $walletUp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WalletUp  $walletUp
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletUp $walletUp)
    {
        //
    }
}
