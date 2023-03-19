<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserAction extends Controller
{
    public function editProfile(Request $request, $id)
    {
       //one uppercase letter, one lowercase letter, one number, and one special character.regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'bio' => 'string|max:255',
            'gender_id' => 'integer|max:1',
            'dob' => 'date',
        ]);
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

}
