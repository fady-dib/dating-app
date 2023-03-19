<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'test','user','editProfile', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }
    public function test()
    {
        dd('hi');
    }


    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'bio' => 'string|max:255',
            'gender_id' => 'integer|max:1',
            'dob' => 'string|max:255',
        ]);

        $userExists = User::where('email', $request->email)->first();

        if ($userExists) {
            return response()->json([
                'status' => 'error',
                'message' => 'User with this email already exists',
            ]);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'bio' => $request->bio,
            'gender_id' => $request->gender_id,
            'dob' => $request->dob,
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    // public function editProfile(Request $request, $id)
    // {
    //    //one uppercase letter, one lowercase letter, one number, and one special character.regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/
    //     $request->validate([
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255',
    //         'password' => 'required|string|min:6',
    //         'bio' => 'string|max:255',
    //         'gender_id' => 'integer|max:1',
    //         'dob' => 'string|max:255',
    //     ]);
    //     // $user = User::where('id', $request->id)->first();
    //     $user = User::find($id);
    //     if($user){
    //         $user->update($request->all());
    //         $user->save();
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Profile updated',
    //         ],200);
    //     }
    //     else{
    //         return response()->json([
    //             'status' => 'failed',
    //         ],404);
    //     }
        // $user->first_name = $request['first_name'];
        // $user->last_name = $request['last_name'];
        // $user->email = $request['email'];
        // $user->password = Hash::make($request->password);
        // $user->bio = $request['bio'];
        // $user->gender_id = $request['gender_id'];
        // $user->dob = $request['dob'];
        //$user->save();
       
    //}

//     public function getUsers($id)
//     {
//        $users = User::where('gender_id','!=', $id)->get();
//        if($users){
//        return response()->json($users);
//        }
//        else{
//         return response()->json(['message' => 'No users']);
//        }
//     }

 }

