<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AddShopeRequest;
use App\Models\Shope;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopeOwner extends Controller
{
    public function addShopeOwner(Request $request)
    {
        $type = Auth::user()->type;
        if ($type == 'Admin') {
            $request->type = "Owner";
            return  Admin::register($request);
        } else {
            return response()->json(['message' =>
            "you don't have permission to see this data "], 403);
        }
    }
    public function getShopeOwner(Request $request)
    {
        $type = Auth::user()->type;
        if ($type == 'Admin') {
            $shop = Shope::where('id', $request->id)->first();
            if (!isset($shope)) {
                return response()->json([
                    'message' => 'not found',
                    'owner' => null
                ], 400);
            } else {
                $user = $shop->user;
                return response()->json([
                    'message' => 'found',
                    'owner' => $user
                ], 200);
            }
        } else {
            return response()->json(['message' =>
            "you don't have permission to see this data "], 403);
        }
    }
    public function addShopeToShopeOwner(Request $request)
    {
        $type = Auth::user()->type;
        if ($type == 'Admin') {
            $user = User::where('id', $request->id)->first();
            if (!isset($user))
                return response()->json([
                    'code' => 400,
                    'message' => 'this shope owner not found'
                ], 200);
            $shope = Shope::create([
                'owner_id' => $user->id,
                'name' => $request->shope_name,
                'location' => $request->location
            ]);
            return response()->json([
                'code' => 200,
                'message' => 'shope created and added',
                'shope' => $shope
            ], 200);
        } else {
            return response()->json([
                'code' => 400,
                'message' => 'you dont have permission to add this data'
            ], 200);
        }
    }
    public function responseToAddRequest(Request $request)
    {
        $type = Auth::user()->type;
        if ($type == 'Admin') {
            $req = AddShopeRequest::where('id', $request->id)->first();
            $req->approved = true;
            $req->admin_id = Auth::user()->id;
            $req->save();
            $shope = Shope::create([
                'name' => $req->name,
                'owner_id' => $req->owner_id,
                'location' => $req->location,
                'approved' => true,
                'created_at'=>$req->created_at
            ]);
            $shope = AddShopeRequest::where('id',$request->id)->with(['owner','admin'])->first();
            return response()->json([
                'code' => 200,
                'message' => 'shope approved',
                'shope' => $shope
            ], 200);
        } else {
            return response()->json([
                'code' => 400,
                'message' => "you can't response to this request cuz you not an amdin"
            ], 200);
        }
    }

    public function editShope(Request $request)
    {
        $user = Auth::user();
        if ($user->type == 'Admin') {
            $editRequest = AddShopeRequest::where('id', $request->id)->first();
            if (
                $editRequest == null or $editRequest->shope_id == null
                or $editRequest->request_type == 'add'
            ) {
                return response()->json([
                    'code' => 400,
                    'message' => "This request not found or type error."
                ], 200);
            }
            $shope = Shope::where('id', $editRequest->shope_id)->first();
            $shope->name = $editRequest->name;
            $shope->location = $editRequest->location;
            $shope->approved = true;
            $editRequest->approved = true;
            $editRequest->admin_id = $user->id;
            $shope->save();
            $editRequest->save();
            return response()->json([
                'code' => 200,
                'message' => "shope edited successfully",
                'shope' => $shope
            ], 200);
        } else {
            return response()->json([
                'code' => 400,
                'message' => "you can't access this method cuz you are not an admin"
            ], 200);
        }
    }

    public function getAllRequests()
    {
        $user = Auth::user();
        if ($user->type == 'Admin') {
            $requests = AddShopeRequest::with(['owner','admin'])->get();
            return response()->json([
                'code' => 200,
                'requests' => $requests
            ], 200);
        } else {
            return response()->json([
                'code' => 403,
                'message' => "You can't see this data cuz ur not an admin"
            ], 200);
        }
    }
}
