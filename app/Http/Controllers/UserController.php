<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function registerUser(Request $request)
    {


        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'status' => 'required|in:approved,pending'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => "user",
            'status' => $request->input('status')
        ]);
        return response()->json(["user" => $user, 'message' => 'User registered successfully'], 201);
    }
    public function loginUser(Request $request)
    {

        $credentials = $request->only('email', 'password');
        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        $user = auth()->user();
        $token = $user->createToken('Token')->plainTextToken;

        return response()->json(['user' => $user, 'access_token' => $token]);
    }
    public function verifyUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["error" => "User not found"], 404);
        }

        if ($user->role == "admin") {
            return response()->json(["error" => "You Cannot change the status of admin"], 403);
        }

        if (!$user) {
            return response()->json(["error" => "User not found"], 404);
        }
        if ($user->status == "approved") {
            return response()->json(["error" => "User already verified"], 400);
        }
        $user->status = "approved";
        $user->save();
        return response()->json(["message" => "User verified successfully"]);
    }
}
