<?php

namespace App\Http\Controllers;

use App\Models\Topup;
use App\Services\Topup as TopupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TopupController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $topup = Topup::where("number", "LIKE", "%$request->search%")
            ->where("user_id", "=", auth()->user()->id)
            ->orderBy("id", "DESC")
            ->paginate(10);

        return response()->json($topup);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $user = auth()->user();

        if ($user->rechargePin !== $request->rechargePin) {
            return response()->json(["message" => "Recharge pin invalid"], 401);
        }

        Validator::make($request->all(), [
            "mobileNumber" => "required",
            "amount" => "required|min:2|max:3",
            "accountType" => "required|in:prepaid,postpaid",
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


        $topup = new Topup;

        $topup->number = $request->mobileNumber;
        $topup->type = $request->accountType;
        $topup->amount = $request->amount;
        $topup->operator = $operator;
        $topup->status = "requested";
        $topup->user_id = $user->id;

        $topup->save();


        if ($user->balance >= $topup->amount) {
            $topupResponse = TopupService::init()
                ->mobile($topup->number)
                ->amount($topup->amount)
                ->account_type($topup->type)
                ->order_number($topup->id)
                ->operator($topup->operator)
                ->recharge();
            if ($topupResponse['error_code'] == 109) {
                $topup->status = "successfull";
                $topup->order_id = $topupResponse['order_id'];
                $topup->tx_id = $topupResponse['recharge_id'];
                $topup->save();
                $user->balance -= $topup->amount;
                $user->save();
                return response()->json(['message' => "Topup Successful", 'status' => "success"], 200);
            }
            $topup->status = "failed";
            $topup->save();
            return response()->json(['message' => "Topup Failed", 'status' => "failed"], 200);
        } else {
            $topup->status = "failed";
            $topup->save();
            return response()->json(['message' => "Balance low", 'status' => "failed"], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Topup  $topup
     * @return \Illuminate\Http\Response
     */
    public function show(Topup $topup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Topup  $topup
     * @return \Illuminate\Http\Response
     */
    public function edit(Topup $topup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Topup  $topup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topup $topup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Topup  $topup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topup $topup)
    {
        //
    }
}
