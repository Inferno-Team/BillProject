<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function addCompany(Request $request)
    {

        $user = Auth::user();
        if ($user->type == 'Admin') {
            Company::create([
                'name'=>$request->name
            ]);
            return response()->json([
                'msg'=>'company add successfully'
            ],200);
        } else return response()->json([
            'msg' => 'u dony have access to this operation cuz u r not an admin'
        ], 200);
    }
}
