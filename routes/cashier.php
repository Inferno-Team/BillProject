<?php

use App\Http\Controllers\cashier\BillController;
use App\Http\Controllers\cashier\CatItem;
use App\Http\Controllers\manager\Category;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function ($route) {
    // $route->post('/add_item', [CatItem::class, 'addItemToCat']);
    $route->post('/create_bill', [BillController::class, 'createBill']);
    $route->post('/get_item',[Category::class,'getItem']);
    $route->post('/add_item_to_bill',[BillController::class,'addItemToBill']);
    $route->post('/remove_item_from_bill',[BillController::class,'removeItemFromBill']);
    $route->get('/get_all_cats', [Category::class, 'getCategories']);
    
});
