<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //validated Request body
        $request->validate([
            'email' =>  'required|string',
            'password' => 'required|string',
        ]);
        //Check if user exists and password is correct
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'check your credentials',
            ], 401);
        }
        //Generate token
        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;
        //Return user and token
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function register(AuthRequest $request)
    {
        //validated Request body
        $validated = $request->validated();
        //Create user
        $user = User::create(array_merge($validated, [
            'password' => Hash::make($request->password),
            'role_id' => 1
        ]));
        //Generate token
        $token = $user->createToken('auth_token')->plainTextToken;
        //Return user and token
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|min:8',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password does not match !'], 400);
        }

        $user->update(
            ['password' => Hash::make($request->password)]
        );

        return response()->json([
            'message' => 'Password successfully updated'
        ], 201);
    }

    public function changeUserData(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|min:3',
            'lastname' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        $user = $request->user();

        $user->update(
            [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
            ]
        );

        return response()->json([
            'message' => 'User successfully updated',
            'user' => $request->user(),
        ], 201);
    }

    public function logout(Request $request)
    {
        //Delete token
        $request->user()->tokens()->delete();
        //Return success message
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function get(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }
}
