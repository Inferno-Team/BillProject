<?php

use App\Http\Controllers\admin\ShopeOwner;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Admin;
use App\Http\Controllers\admin\CompanyController;

Route::group(['middleware' => ['auth:sanctum']], function ($route) {
    $route->post('/add_new_admin',[Admin::class,'register']);
    $route->post('/get_all_admins',[Admin::class,'getAllAdmins']);
    $route->post('/remove_admin',[Admin::class,'removeAdmin']);
    $route->post('/add_comapny',[CompanyController::class , 'addCompany']);    
    $route->post('/add_shope_owner', [ShopeOwner::class, 'addShopeOwner']);
    $route->post('/edit_shope', [ShopeOwner::class, 'editShope']);
    $route->post('/response2add_request', [ShopeOwner::class, 'responseToAddRequest']);
    $route->get('/get_shope_owner', [ShopeOwner::class, 'getShopeOwner']);
    $route->get('/get_all_requests',[ShopeOwner::class,'getAllRequests']);

});
