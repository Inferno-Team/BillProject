<?php

use App\Http\Controllers\owner\Owner;
use App\Http\Controllers\owner\OwnerManager;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth:sanctum']], function ($route) {
    $route->post('/add_shop', [Owner::class, 'addShope']);
    $route->post('/edit_shop', [Owner::class, 'editShope']);
    $route->post('/remove_request', [Owner::class, 'removeRequest']);
    $route->post('/create_manager', [Owner::class, 'addManager']);
    $route->post('/move_manager', [Owner::class, 'moveManager']);
    $route->post('/remove_manager',[OwnerManager::class,'removeManager']);
    $route->get('/get_owner_shops',[Owner::class,'getOwnerShops']);
    $route->get('/get_owner_requests',[Owner::class,'getOwnerRequests']);
});
