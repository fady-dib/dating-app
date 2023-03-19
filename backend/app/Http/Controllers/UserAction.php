<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAction extends Controller
{
    public function editProfile(Request $request, $id)
    {
       //one uppercase letter, one lowercase letter, one number, and one special character.regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/
       $validator = Validator::make($request->all(),[ 'first_name' => 'required|string|max:255',
       'last_name' => 'required|string|max:255',
       'email' => 'required|string|email|max:255',
       'password' => 'required|string|min:6',
       'bio' => 'string|max:255',
       'gender_id' => 'integer|max:1',
       'dob' => 'date',]);
       if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

        // $user = User::where('id', $request->id)->first();
        $user = User::find($id);
        if($user){
            $user->update($request->all());
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated',
            ],200);
        }
        else{
            dd('user not found');
            return response()->json([
                'status' => 'failed',
            ],404);
        }
       
    }

    public function getUsers($gender_id)
    {
       $users = User::where('gender_id','!=', $gender_id)->get();
       if($users){
       return response()->json($users);
       }
       else{
        return response()->json(['message' => 'No users']);
       }
    }

    public function test()
    {
        dd('hi');
    }

    public function block(Request $request){
        $validator = Validator::make($request->all(),[
            'blocked_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 401);
        }

        $user_id = Auth::id();
        $blocked_id = $request->blocked_id;

        $already_blocked = Block::where('user_id', $user_id)->where('blocked_id',$blocked_id)->exists();
        if($already_blocked){
            return response()->json([
                'status' => 'failed',
                'message' => 'already blocked'
            ]);
        }
        else{
             Block::create([
                'user_id' => $user_id,
                'blocked_id' => $blocked_id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'successfully blocked the user'
            ]);
        }
    }

    public function unblock(Request $request) {
        $validator = Validator::make($request->all(),[
            'blocked_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 401);
        }

        $user_id = Auth::id();
        $blocked_id = $request->blocked_id;
        $already_blocked = Block::where('user_id', $user_id)->where('blocked_id',$blocked_id);
        if($already_blocked->exists()){
            $already_blocked->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'user unblocked successfully'
            ]);
        }
        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'user is not blocked'
            ]);
        }
}
}
