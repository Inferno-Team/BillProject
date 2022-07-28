<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class Admin extends Controller
{
    public static function register(Request $request)
    {
        $user = Auth::user();
        if ($user->type == "Admin") {
            $user = User::create([
                'user_name' => $request->user_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'type' => $request->type
            ]);
            return response()->json([
                'code' => 200,
                'msg' => "user created successfully.",
                'user' => $user
            ], 200);
        } else return response()->json([
            "code" => 403,
            "msg" => "u can't create new user as an admin cuz ur not an admin"
        ], 200);
    }
    public function removeAdmin(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'Admin') {
            $removeableAdminId = $request->id;
            $admin = User::where('id', $removeableAdminId)->first();
            if ($admin == null or $admin->type != 'Admin')
                return response()->json([
                    'code' => 400,
                    'message' => "You can't remove this user cuz it's not found or its not an admin."
                ], 200);
            else {
                $admin->delete();
                return response()->json([
                    'code' => 200,
                    'message' => "This admin is no longer alive ;(",
                    'user' => $admin
                ], 200);
            }
        } else {
            return response()->json([
                'code' => 400,
                'message' => "You can't remove admin cuz ur not an admin."
            ], 200);
        }
    }
    public function getAllAdmins()
    {
        $user = Auth::user();
        if ($user->type == "Admin") {
            $users = User::where('type','Admin')
            ->where("id","!=",$user->id)
            ->get();
            return response()->json($users, 200);
        } else return response()->json(['code'=>401], 200);
    }
}
