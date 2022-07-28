<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use App\Models\AddShopeRequest;
use App\Models\Shope;
use App\Models\ShopeStaff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class Owner extends Controller
{
    public function login(Request $request)
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
            return response()->json(['message' => 'Do not match our records!!'], 404);

        $tokenResult = $user->createToken('authToken')->plainTextToken;
        return response()->json(['token' => $tokenResult, 'message' => 'good', 'type' => $user->type], 250);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'code' => 200,
            'message' => 'token deleted successfully'
        ], 200);
    }
    public function addShope(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'Owner') {
            $shope = AddShopeRequest::create([
                'owner_id' => $user->id,
                'name' => $request->name,
                'location' => $request->location,
                'approved' => false,
                'type' => 'add'
            ]);
            return response()->json([
                'code' => 200,
                'message' => "this shope added and waitin for admin approve",
                'shope' => $shope
            ], 200);
        } else {
            return response()->json([
                'code' => 400,
                'message' => "you can't access this method cuz you are not an Owner"
            ], 200);
        }
    }

    public function editShope(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'Owner') {
            $shope = AddShopeRequest::create([
                'owner_id' => $user->id,
                'name' => $request->name,
                'location' => $request->location,
                'approved' => false,
                'request_type' => 'edit',
                'shope_id' => $request->shop_id
            ]);
            return response()->json([
                'code' => 200,
                'message' => "This Edit Request scheuled and waiting for admin approve",
                'shope' => $shope
            ], 200);
        } else {
            return response()->json([
                'code' => 400,
                'message' => "you can't access this method cuz you are not an Owner",
                'shope' => []
            ], 200);
        }
    }
    public function removeRequest(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'Owner') {
            $_request = AddShopeRequest::find($request->id);
            // check if this request is approved so you need to del the approved shop
            // and remove all of components
            $is = $_request->delete();
            info($is);
            return response()->json([
                'code' => 200,
                'message' => 'deleted successfully',
                'shope' =>$_request
            ], 200);
        } else {
            return response()->json([
                'code' => 400,
                'message' => "you can't access this method cuz you are not an Owner",
                'shope' => []
            ], 200);
        }
    }

    public function addManager(Request $request)
    {
        $_user = Auth::user();
        $type = $_user->type;
        $shope = Shope::where('id', $request->shope_id)->first();
        if (!isset($shope)) {
            return response()->json([
                'code' => '400',
                'message' => "This shope is not exists."
            ], 200);
        } else {
            if ($shope->owner_id != $_user->id)
                return response()->json([
                    'code' => '400',
                    'message' => "This shope doesn't belong to you."
                ], 200);
            $shopeStaff = ShopeStaff::where('shope_id', $shope->id)
                ->where('postion', 'Manager')->first();
            if (isset($shopeStaff)) {
                return response()->json([
                    'code' => 400,
                    'message' => "This shope already have a Manager."
                ], 200);
            }
        }
        $worker = array();
        if ($type == 'Owner') {
            $user = User::where('email', $request->email)->first();
            if (!isset($user)) {
                $user = User::create([
                    'user_name' => $request->user_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'type' => 'Manager'
                ]);
                $worker = ShopeStaff::create([
                    'worker_id' => $user->id,
                    'shope_id' => $request->shope_id,
                    'postion' => 'Manager',
                ]);
                return response()->json([
                    'code' => 200,
                    'message' => 'Manager created successfully',
                    'user' => $user,
                    'staff' => $worker
                ], 200);
            } else {
                return response()->json([
                    'code' => 400,
                    'message' => 'This user is exsits.',
                ], 200);
            }
        } else {
            return response()->json([
                'code' => 400,
                'message' => "you can't add manager cuz you are not a Shope Owner"
            ], 200);
        }
    }

    public function moveManager(Request $request)
    {
        $_user = Auth::user();
        $type = $_user->type;
        $shope = Shope::where('id', $request->shope_id)->first();
        if (!isset($shope)) {
            return response()->json([
                'code' => '400',
                'message' => "This shope is not exists."
            ], 200);
        } else {
            if ($shope->owner_id != $_user->id)
                return response()->json([
                    'code' => '400',
                    'message' => "This shope doesn't belong to you."
                ], 200);
            $shopeStaff = ShopeStaff::where('shope_id', $shope->id)
                ->where('postion', 'Manager')->first();
            if (isset($shopeStaff)) {
                return response()->json([
                    'code' => 400,
                    'message' => "This shope already have a Manager."
                ], 200);
            }
        }
        if ($type == 'Owner') {
            $user = User::where('email', $request->email)->first();
            if (!isset($user)) {
                return response()->json([
                    'code' => 400,
                    'message' => "This User is not exists."
                ], 200);
            } else if ($user->type != 'Manager') {
                return response()->json([
                    'code' => 400,
                    'message' => "your provided email is belong to user not a Manager"
                ], 200);
            } else {
                $worker = ShopeStaff::where('worker_id', $user->id)->first();
                $worker->shope_id = $request->shope_id;
                $worker->save();
            }
        } else {
            return response()->json([
                'code' => 400,
                'message' => "you can't add manager cuz you are not a Shope Owner"
            ], 200);
        }
    }
    public function getOwnerShops()
    {
        $user = Auth::user();
        $type = $user->type;
        if ($type == 'Owner') {
            $shops = Shope::where('owner_id', $user->id)->with(['shopeStaff.worker'])->get()->toArray();
            return response($shops, 200);
        } else {
            return response([
                'code' => 400,
                'msg' => 'ur not an shop owner so u dont have any shops ;)'
            ], 200);
        }
    }
    public function getOwnerRequests()
    {
        $user = Auth::user();
        $type = $user->type;
        if ($type == 'Owner') {
            $shop_requests = AddShopeRequest::where('owner_id', $user->id)->get()->toArray();
            return response($shop_requests, 200);
        } else {
            return response([
                'code' => 400,
                'msg' => 'u r not an shop owner so u dont have any shops ;)'
            ], 200);
        }
    }
}
