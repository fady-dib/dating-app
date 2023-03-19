<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Search extends Controller
{
    public function search(Request $request){
        if(empty($request)){
            return response()->json([
                'status'=>'failed',
                'message' => 'empty request',
             ],404);
        }

        if($request->has('first_name'&&'last_name')){
            $search = User::where('first_name','like','%'.$request->first_name.'%')->where('last_name','like','%'.$request->last_name.'%')->get();
            if (!empty($search)) {
                return response()->json([
                    'status'=>'success',
                    'users' => $search,
                 ],200);
            } else {
                return response()->json(['message' => 'No users found']);
            }
           
    
        }

        else if($request->has('first_name')){
        $search = User::where('first_name','like','%'.$request->first_name.'%')->get();
        if (!empty($search)) {
            return response()->json([
                'status'=>'success',
                'users' => $search,
             ],200);
        } else {
            return response()->json(['message' => 'No users found']);
        }
    }
    else if($request->has('last_name')){
        $search = User::where('last_name','like','%'.$request->last_name.'%')->get();
        if (!empty($search)) {
            return response()->json([
                'status'=>'success',
                'users' => $search,
             ],200);
        } else {
            return response()->json(['message' => 'No users found']);
        }

    }
    
}
}
