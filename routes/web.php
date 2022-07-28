<?php

use App\Models\Shope;
use App\Models\ShopeCategory;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/add_shope', function () {
    return Shope::create([
        'name' => 'Created By PHP add_shope',
        'owner_id' => 1,
        'location' => 'Location Location Location'
    ]);
});
Route::get('/create_user', function () {
    return User::create([
        'user_name' => 'user_name',
        'email' => 'email',
        'password' => 'password',
        'phone' => 'phone',
        'type' => 'تاجر',
    ]);
});

Route::get('/get_user_shopes/{id}', function ($id) {
    return User::find(1)->shopes;
});

Route::get('/get_shope_cats/{id}', function ($id) {
    return Shope::where('id', $id)
        ->with(['cats', 'cats.items'])
        ->first();
});
