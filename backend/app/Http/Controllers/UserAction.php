<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Favorite;
use App\Models\Message;
use App\Models\Picture;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAction extends Controller
{
    public function editProfile(Request $request)
    {

        if(empty($request->all())){
            return response()->json([
                'status' => 'error',
                'message' => 'at least 1 field required',
            ], 200);
        }
        //one uppercase letter, one lowercase letter, one number, and one special character.regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/
        $validator = Validator::make($request->all(), [
            'first_name' => 'min:3',
            'last_name' => 'min:3',
            'email' => 'string|email|max:255',
            'password' => 'string|min:6',
            'bio' => 'string|max:255',
            'gender_id' => 'integer|max:1',
            'dob' => 'date',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $user_id = Auth::id();
        // dd($user_id);
        if ($user_id) {
            $user = User::where('id', $user_id)->first();
            $user->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated',
            ], 200);
        } else {
            // dd('user not found');
            return response()->json([
                'status' => 'failed',
            ], 404);
        }

    }

    public function getUsers()
    {
        $user_id = Auth::id();
        $user_gender = User::where('id', $user_id)->value('gender_id');
         $opposite = User::leftjoin('pictures','pictures.user_id','=','users.id')
         ->select('*', 'users.id as id') ->where('users.gender_id','!=',$user_gender)->get();
        //  dd($opposite);
        // $users = User::where('gender_id','!=',$user_gender)->get();
        // $picture = Picture::where('user_id',$user_id)->get();
        // $data = [
        //     "users"=>$users,
        //     "pictures"=>$picture];
        if (!empty($opposite)) {
            return response()->json([
                'status' => 'success',
                'data' => $opposite
            ]);
        } else {
            return response()->json(['message' => 'No users']);
        }
    }

    public function test()
    {
        dd('hi');
    }

    public function block(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        $already_blocked = Block::where('user_id', $user_id)->where('blocked_id', $blocked_id)->exists();
        if ($already_blocked) {
            return response()->json([
                'status' => 'failed',
                'message' => 'already blocked'
            ]);
        } else {
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

    public function unblock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blocked_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 400);
        }

        $user_id = Auth::id();
        $blocked_id = $request->blocked_id;
        $already_blocked = Block::where('user_id', $user_id)->where('blocked_id', $blocked_id);
        if ($already_blocked->exists()) {
            $already_blocked->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'user unblocked successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'user is not blocked'
            ]);
        }
    }

    public function favorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'favorite_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 400);
        }
        $user_id = Auth::id();
        $favorite_id = $request->favorite_id;
        $already = Favorite::where('user_id', $user_id)->where('favorite_id', $favorite_id)->exists();
        if ($already) {
            return response()->json([
                'status' => 'failed',
                'message' => 'this user is already in your favorites'
            ]);
        } else {
            Favorite::create([
                'user_id' => $user_id,
                'favorite_id' => $favorite_id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'successfully added to your favorites'
            ]);
        }
    }

    public function unfavorite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'favorite_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 400);
        }

        $user_id = Auth::id();
        $favorite_id = $request->favorite_id;
        $already = Favorite::where('user_id', $user_id)->where('favorite_id', $favorite_id);
        if ($already->exists()) {
            $already->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'user removed from your favorites'
            ]);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'user is not in your favorites'
            ]);
        }
    }

    public function addPicture(Request $request){
        $user_id = Auth::id();
        $file =$request->file('picture');
        $base64Image= base64_encode(file_get_contents($file->path()));
        $picture = new Picture;
        $picture ->user_id =$user_id;
        $picture->link = $base64Image;
        $picture->save();
        return response()->json([
            'message' => 'Picture added successfully'
        ]);
    }
    public function updatePicture(Request $request)
    {
        $user_id = Auth::id();
        $picture = Picture::find($user_id);
        if (!$picture) {
            return response()->json(['message' => 'Picture not found'], 404);
        }
        $file = $request->file('picture');
        $base64Image = base64_encode(file_get_contents($file->path()));
        $picture->link = $base64Image;
        $picture->save();
        return response()->json([
            'message' => 'Picture updated successfully'
        ]);
    }

    public function deletePicture(Request $request){
        $user_id = Auth::id();
        $picture = Picture::find($user_id);
        if (!$picture) {
            return response()->json(['message' => 'Picture not found'], 404);
        }
        else{
            $picture->delete();
        }
    }

    public function sendMessage(Request $request){
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required',
            "message"=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validator->errors()
            ], 400);
        }

        $user_id = Auth::id();
        $receiver_id = $request->receiver_id;
        $message = $request -> message;

        Message::create([
                'user_id' => $user_id,
                'reciever_id' => $receiver_id,
                'message' => $message
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Message sent successfuly'
            ]);
        

    }
    
    
    
    
    
}