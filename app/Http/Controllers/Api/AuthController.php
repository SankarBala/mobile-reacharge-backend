<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function __construct(){
        
        $this->middleware('auth:sanctum')->except(['login', 'register']);
    }

    public function register(Request $input)
    {

        Validator::make($input->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => 'required|string|confirmed',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        return $this->apiToken($user);
    }

    public function login(Request $request)
    {
        Validator::make($request->all(), [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'exists:users,email'
            ],
            'password' => 'required|string'
        ])->validate();


        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            return $this->apiToken($user);
        } else {
            return response()->json(["message" => "Invalid email or password"], 401);
        }
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(["message" => "Logout successful"], 200);
    }

    public function apiToken($user)
    {
        $token = $user->createToken('authToken');

        return ['token' => $token->plainTextToken];
    }

}
