<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\ShopeStaff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Cashier extends Controller
{
    public function addCashier(Request $request)
    {
        $user = Auth::user();
        $worker = ShopeStaff::where("worker_id", $user->id)->first();
        if ($user->type == 'Manager') {
            $cashier = User::where('email', $request->email)->first();
            if (!isset($cashier)) {
                $cashier = User::create([
                    'email' => $request->email,
                    'user_name' => $request->user_name,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'type' => 'Cashier'
                ]);
            } else if ($cashier->type != "Cashier") {
                return response()->json([
                    'code' => 400,
                    'message' => "your provided email is belong to user not a Cashier"
                ], 200);
            }
            $staff = ShopeStaff::create([
                'postion' => 'Cashier',
                'shope_id' => $worker->shope_id,
                'worker_id' => $cashier->id,
            ]);
            return response()->json([
                'code' => '200',
                'message' => "added successfully",
                'staff' => $staff
            ], 200);
        } else {
            return response()->json([
                'code' => 403,
                'message' => "u don't have the permission to do this operation."
            ], 200);
        }
    }

    public function getAllCashiers(Request $request)
    {
        $user = Auth::user();
        $worker = ShopeStaff::where("worker_id", $user->id)->first();
        if ($user->type == 'Manager') {
            $cashiers = ShopeStaff::where('shope_id', $worker->shope_id)
                ->where('postion', 'like', 'Cashier')->with('worker')->get();
            return response()->json($cashiers, 200);
        } else {
            return response()->json([
                'code' => 403,
                'message' => "u don't have the permission to do this operation."
            ], 200);
        }
    }

    public function removeCashier($id)
    {
        $user = Auth::user();
        $worker = ShopeStaff::where("worker_id", $user->id)->first();
        $cahierWorker = ShopeStaff::where("worker_id", $id)->first();
        if ($user->type == 'Manager') {
            if ($worker->shope_id == $cahierWorker->shope_id) {
                $cashier = User::where('id', $cahierWorker->worker_id)->first();
                $cahierWorker->delete();
                $cashier->delete();
                return response()->json([
                    'code' => 200,
                    'message' => "user removed sucessfully"
                ], 200);
            } else {
                return response()->json([
                    'code' => 400,
                    'message' => "you can't remove cashier doesn't work in your shop"
                ], 200);
            }
            // return response()->json($cashiers, 200);
        } else {
            return response()->json([
                'code' => 403,
                'message' => "u don't have the permission to do this operation."
            ], 200);
        }
    }
}
