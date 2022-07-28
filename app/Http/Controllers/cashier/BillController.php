<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\BillItems;
use App\Models\BillTable;
use App\Models\ShopeCategory;
use App\Models\ShopeStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function createBill(Request $request)
    {
        // error_log($request);
        // error_log($request->check);
        info($request->all());
        $barcodes = $request->barcodes;
        $cashier_id = Auth::user()->id;
        $worker = ShopeStaff::where('worker_id', $cashier_id)->first();
        $shop_id = $worker->shope_id;

        $bill = BillTable::create([
            'cashier_id' => $cashier_id,
            'shope_id' => $shop_id,
            'check' => $request->check,
        ]);

        for ($i = 0; $i < count($barcodes); $i++) {
            $barcode = $barcodes[$i];
            $_count = $request->counts[$i];
            $category = ShopeCategory::where('barcode', $barcode)->first();
            BillItems::create([
                'bill_id' => $bill->id,
                'item_id' => $category->id,
                'count' => $_count
            ]);
        }
        return response()->json([
            'code' => 200,
            'msg' => 'bill added successfully , waiting for customer to scan the QR',
            'data'=>$bill->id
        ], 200);
    }

    public function addItemToBill(Request $request)
    {
        $cat = ShopeCategory::where('barcode', $request->barcode)->first();
        if ($cat->stock_count >= $request->count) {
            $cat->stock_count = $cat->stock_count - $request->count;
            $cat->save();
            return response()->json([
                'code' => 200,
                'msg' => 'item added to bill and removed from shop'
            ], 200);
        } else {
            return response()->json([
                'code' => 300,
                'msg' => "there is no enough item in the stock"
            ], 200);
        }
    }
    public function removeItemFromBill(Request $request)
    {
        $cat = ShopeCategory::where('barcode', $request->barcode)->first();
        $cat->stock_count = $cat->stock_count + $request->count;
        $cat->save();
        return response()->json([
            'code' => 200,
            'msg' => 'item removed from bill and restored to shop'
        ], 200);
    }
}
