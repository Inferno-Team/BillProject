<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\CategoryItems;
use App\Models\ShopeStaff;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatItem extends Controller
{
    public function addItemToCat(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'Cashier') {
            $creationDate = date('Y-m-d H:i:s', $request->creation_date / 1000);
            $expireDate = date('Y-m-d H:i:s', $request->expire_date / 1000);
            // $_creationDate = DateTime::createFromFormat('d-m-Y H:i:s', $creationDate);
            // $expireDate = DateTime::createFromFormat("d/m G:i", $request->expire_date);
            // return response()->json([
            //     'code' => $_creationDate->getTimestamp(),
            //     'message' => "added successfully",
            //     // 'item' => $creationDate->getTimestamp()
            // ], 200);
            $cat_item = CategoryItems::create([
                'category_id' => $request->category_id,
                'barcode' => $request->barcode,
                'createion_date' =>  $creationDate,
                'expire_date' => $expireDate,
                // 'expire_period' => $request->expire_period,
            ]);
            return response()->json([
                'code' => 200,
                'message' => "added successfully",
                'item' => $cat_item
            ], 200);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'you are not cashier'
            ], 200);
        }
    }
}
