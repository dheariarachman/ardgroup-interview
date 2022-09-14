<?php

namespace App\Http\Controllers;

use App\Models\Savings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->validate([
            'name'          => 'required|string|min:3',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:8',
            'birth_date'    => 'required|date',
            'address'       => 'required'
        ]);

        $user = User::create([
            'name'          => $input['name'],
            'email'         => $input['email'],
            'birth_date'    => $input['birth_date'],
            'address'       => $input['address'],
            'password'      => Hash::make($input['password']),
        ]);

        if ($user) {
            $savings = new Savings();
            $savings->user_id = $user->id;
            $savings->balance                = 0;
            $savings->saving_at              = date('Y-m-d');
            $savings->last_transaction_type  = 'open';
            $savings->last_transaction_date  = date('Y-m-d');
            $savings->save();
        }

        $token = $user->createToken('accessToken')->plainTextToken;

        return response()->json([
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Login information is invalid'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('accessToken')->plainTextToken;

        return response()->json([
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }
}
