<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\BillTable;
use App\Models\Shope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function addBill($id)
    {
        $user = Auth::user();
        if ($user->type == 'Customer') {
            $bill = BillTable::where('id', $id)->with('items.catItem', 'shope')->first();
            if (isset($bill)) {
                if (isset($bill->user_id)) {
                    if($user->id == $bill->user_id){
                        return response()->json([
                            'code' => 200,
                            'message' => "",
                            'bill' => $bill
                        ], 200);
                    }else{
                        return response()->json([
                            'code' => 404,
                            'message' => "bill already linked to another user.",
                            'bill' => null
                        ], 200);
                    }
                } else {
                    $bill->user_id = $user->id;
                    $bill->save();
                    return response()->json([
                        'code' => 200,
                        'message' => "your bill has been linked to your account",
                        'bill' => $bill
                    ], 200);
                }
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => "bill not found.",
                    'bill' => null
                ], 200);
            }
        } else {
            return response()->json([
                'code' => 403,
                'message' => "u don't have the permission to do this operation."
            ], 200);
        }
    }
    public function getMyBills()
    {
        $user = Auth::user();
        if ($user->type == 'Customer') {
            $bill = BillTable::where('user_id', $user->id)
                ->with(['cashier', 'shope.owner', 'items.catItem'])->get();
            return response()->json([
                'code' => 200,
                'bills' => $bill,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'code' => 403,
                'message' => "u don't have the permission to do this operation.",
                'bill' => []
            ], 200);
        }
    }
    public function removeMyBill($id)
    {
        $user = Auth::user();
        if ($user->type == 'Customer') {
            $bill = BillTable::where('id', $id)->first();
            if (isset($bill)) {
                if (isset($bill->user_id)) {
                    if ($bill->user_id == $user->id) {
                        $bill->delete();
                        return response()->json([
                            'code' => 200,
                            'message' => "your bill has been deleted"
                        ], 200);
                    } else {
                        return response()->json([
                            'code' => 403,
                            'message' => "not your bill to delete"
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'code' => 400,
                        'message' => "this bill does not have user yes"
                    ], 200);
                }
            }
        } else {
            return response()->json([
                'code' => 403,
                'message' => "u don't have the permission to do this operation."
            ], 200);
        }
    }

    public function getOtherShops(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'Customer') {
            $shops = Shope::where('owner_id', $request->owner_id)->get();
            return response()->json($shops, 200);
        } else {
            return response()->json([
                'code' => 403,
                'message' => "u don't have the permission to do this operation.",
                'bill' => []
            ], 200);
        }
    }
}
