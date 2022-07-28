<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use App\Models\ShopeStaff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerManager extends Controller
{
    function removeManager(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'Owner') {
            // id is the shop id
            $shopId = $request->shop_id;
            // get the manager of this shop
            $worker = ShopeStaff::where('shope_id', $shopId)
                ->where('postion', 'Manager')->first();
            
            $managerId = $worker->worker_id;
            $manager = User::where('id', $managerId)
                ->with(['workerShopeRow.shope.owner'])
                ->first();
            if ($manager == null)
                return response()->json([
                    'code' => 400,
                    'message' => 'Manager With this Id not found.'
                ], 200);
            $owner = $manager->workerShopeRow->shope->owner;
            if ($owner->id == $user->id) {
                ShopeStaff::where('worker_id', $managerId)->delete();
                User::where('id', $managerId)->delete();
                return response()->json([
                    'code' => 200,
                    'message' => 'Manager deleted successfully'
                ], 200);
            } else return response()->json([
                'code' => 400,
                'message' => "couldn't delete this manager plz try again later."
            ], 200);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'you cant remove manager cuz ur not a shopeOwner'
            ], 200);
        }
    }
}
