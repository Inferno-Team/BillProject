<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\customer\CustomerController;
use App\Models\BillTable;
use App\Models\ShopeStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(["middleware" => ["auth:sanctum"]], function ($route) {
    $route->post('/logout', [Controller::class, 'logout']);
    $route->get('/user', function () {
        return Auth::user();
    });
    $route->get('/get_worker', function () {
        $user = Auth::user();
        $worker = ShopeStaff::where('worker_id', $user->id)->first();
        return response()->json($worker, 200);
    });

    $route->post('/add_bill/{id}',[CustomerController::class,'addBill']);
    $route->post('/remove_my_bill/{id}',[CustomerController::class,'removeMyBill']);
    $route->get('/get_my_bills',[CustomerController::class,'getMyBills']);
    $route->get('/get_other_shops',[CustomerController::class,'getOtherShops']);

});
Route::post('/login', [Controller::class, '_login']);
Route::post('/signup', [Controller::class, 'signUp']);
// Route::get('/get_bills',function(Request $request){
//     $bills = BillTable::with('cats')->get();
//     return response()->json($bills);
// });
