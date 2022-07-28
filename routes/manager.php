<?php

use App\Http\Controllers\manager\Cashier;
use App\Http\Controllers\manager\Category;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function ($route) {
    $route->post('/add_cashier', [Cashier::class, "addCashier"]);
    $route->post('/add_cat', [Category::class, 'addCat']);
    $route->post('/add_company', [Category::class, 'addCompany']);
    $route->get('/get_all_companies', [Category::class, 'getCompanies']);
    $route->get('/get_all_cats', [Category::class, 'getCategories']);
    $route->get('/get_all_cashiers', [Cashier::class, 'getAllCashiers']);
    $route->get('/get_company_cats/{comp_id}', [Category::class, 'getCompanyCategories']);
    $route->post('/remove_cashier/{id}', [Cashier::class, 'removeCashier']);
});
