<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function _login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' =>  'required|email',
                'password' =>  'required'
            ]
        );
        $user = User::where('email', $request->email)->first();
        if (!isset($user)) {
            return response()->json(['status_code' => 400, 'message' => 'User not found'], 200);
        }
        if ($validator->fails())
            return response()->json(['status_code' => 400, 'message' => 'Bad Request']);

        if (!Hash::check($request->password, $user->password))
            return response()->json(['message' => 'Do not match our records!!'], 200);

        $tokenResult = $user->createToken('authToken')->plainTextToken;
        return response()->json(['token' => $tokenResult, 'message' => 'good', 'type' => $user->type], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'code' => 200,
            'msg' => 'token deleted successfully'
        ], 200);
    }
    public function signUp(Request $request)
    {
        $customer = User::create([
            'email' => $request->email,
            'user_name' => $request->user_name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'type' => 'Customer'
        ]);
        $tokenResult = $customer->createToken('authToken')->plainTextToken;

        return response()->json([
            'code' => '200',
            'message' => "good",
            'token' => $tokenResult,
            'type' => $customer->type
        ], 200);
    }
}
