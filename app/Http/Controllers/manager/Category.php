<?php

namespace App\Http\Controllers\manager;

use App\Http\Controllers\Controller;
use App\Models\CategoryItems;
use App\Models\Company;
use App\Models\ShopeCategory;
use App\Models\ShopeStaff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Category extends Controller
{
    public function addCat(Request $request)
    {
        if (isset($request->lang)) {
            app()->setLocale($request->lang);
        }
        $user = Auth::user();
        if ($user->type == 'Cashier') {
            $worker = ShopeStaff::where('worker_id', $user->id)->first();
            $creationDate = date('Y-m-d H:i:s', $request->creation_date / 1000);
            $expireDate = date('Y-m-d H:i:s', $request->expire_date / 1000);
            $item = ShopeCategory::where('shope_id', $worker->shope_id)
                ->where('comp_id', $request->comp_id)
                ->where('barcode', $request->barcode)->first();
            if (isset($item)) {
                return response()->json([
                    'code' => 500,
                    'msg' => __('category.add_cat_msg_faild'),
                    'cat' => $item
                ], 200);
            }
            $cat = ShopeCategory::create([
                'shope_id' => $worker->shope_id,
                'comp_id' => $request->comp_id,
                'category_name' => $request->category_name,
                'price' => $request->price,
                'stock_count' => $request->s_count,
                'expire_date' => $expireDate,
                'createion_date' =>  $creationDate,
                'barcode' => $request->barcode,
            ]);
            return response()->json([
                'code' => 200,
                'msg' => __('category.add_cat_msg_sucess'),
                'cat' => $cat
            ], 200);
        } else {
            return response()->json([
                'code' => 403,
                'msg' => 'u dont have the permission todo this ops.'
            ], 200);
        }
    }

    public function getCompanies()
    {
        $user = Auth::user();
        if ($user->type == 'Manager' || $user->type == 'Cashier') {
            $worker = ShopeStaff::where('worker_id', $user->id)->first();
            $comps = company::where('shop_id', $worker->shope_id)->with('cats')->get();
            for ($i = 0; $i < count($comps); $i++) {
                $comps[$i]->cat_count = count($comps[$i]->cats);
            }
            return response()->json($comps, 200);
        } else {
            return response()->json([
                'code' => 403,
                'msg' => 'u dont have the permission todo this ops.'
            ], 200);
        }
    }
    public function getCompanyCategories($comp_id)
    {
        $user = Auth::user();
        if ($user->type == 'Manager') {
            $worker = ShopeStaff::where('worker_id', $user->id)->first();
            // $comps = company::where('shop_id', $worker->shope_id)->with('cats')->first();
            $cats = ShopeCategory::whereHas('compName', fn ($query) => $query->where('id', $comp_id))
                ->with(['compName'])->get();
            
            return response()->json($cats, 200);
        } else {
            return response()->json([
                'code' => 403,
                'msg' => 'u dont have the permission todo this ops.'
            ], 200);
        }
    }
    public function getCategories()
    {
        $user = Auth::user();
        if ($user->type == 'Manager' || $user->type == 'Cashier') {
            $worker = ShopeStaff::where('worker_id', $user->id)->first();
            $cats = ShopeCategory::where('shope_id', $worker->shope_id)
                ->with(['compName'])->get();

            return response()->json($cats, 200);
        } else {
            return response()->json([
                'code' => 403,
                'msg' => 'u dont have the permission todo this ops.'
            ], 200);
        }
    }

    public function getItem(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'Manager' || $user->type == 'Cashier') {
            $item = ShopeCategory::where('barcode', $request->barcode)->first();
            if (isset($item))
                return response()->json(['item' => $item, 'msg' => 'item found', 'code' => 200], 200);
            else return response()->json(['item' => null, 'msg' => 'item not found', 'code' => 300], 200);
        } else {
            return response()->json([
                'code' => 403,
                'msg' => 'u dont have the permission todo this ops.'
            ], 200);
        }
    }

    public function addCompany(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'Manager') {
            $worker = ShopeStaff::where('worker_id', $user->id)->first();
            $comp = Company::where('name', $request->name)
                ->where('shop_id', $worker->shope_id)->first();
            if (isset($comp)) {
                return response()->json([
                    'code' => 301,
                    'msg' => 'This company already exists.',
                    'comp' => $comp
                ], 200);
            }
            $comp = Company::create([
                'name' => $request->name,
                'shop_id' => $worker->shope_id
            ]);
            return response()->json([
                'code' => 200,
                'msg' => 'Company created successfully.',
                'comp' => $comp
            ], 200);
        } else {
            return response()->json([
                'code' => 403,
                'msg' => 'u dont have the permission todo this ops.',
                'comp' => []
            ], 200);
        }
    }
}
