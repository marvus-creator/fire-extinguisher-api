<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(path: '/api/register', summary: 'Register user', tags: ['Auth'],
        requestBody: new OA\RequestBody(required: true,
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'first_name', type: 'string'),
                new OA\Property(property: 'last_name', type: 'string'),
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
                new OA\Property(property: 'password_confirmation', type: 'string'),
                new OA\Property(property: 'role', type: 'string'),
            ])
        ),
        responses: [new OA\Response(response: 201, description: 'User registered')]
    )]
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6|confirmed',
            'role'       => 'in:admin,inspector,user',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role ?? 'user',
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->json(['message' => 'User registered successfully', 'user' => $user, 'token' => $token], 201);
    }

    #[OA\Post(path: '/api/login', summary: 'Login user', tags: ['Auth'],
        requestBody: new OA\RequestBody(required: true,
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'email', type: 'string'),
                new OA\Property(property: 'password', type: 'string'),
            ])
        ),
        responses: [new OA\Response(response: 200, description: 'Login successful')]
    )]
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        return response()->json(['message' => 'Login successful', 'token' => $token, 'user' => auth()->user()]);
    }

    #[OA\Post(path: '/api/logout', summary: 'Logout user', tags: ['Auth'],
        responses: [new OA\Response(response: 200, description: 'Logged out')]
    )]
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Logged out successfully']);
    }

    #[OA\Get(path: '/api/me', summary: 'Get current user', tags: ['Auth'],
        responses: [new OA\Response(response: 200, description: 'Current user')]
    )]
    public function me()
    {
        return response()->json(auth()->user());
    }

    #[OA\Put(path: '/api/profile', summary: 'Update profile', tags: ['Auth'],
        responses: [new OA\Response(response: 200, description: 'Profile updated')]
    )]
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $user->update($request->only('first_name', 'last_name'));
        return response()->json(['message' => 'Profile updated', 'user' => $user]);
    }

    #[OA\Put(path: '/api/change-password', summary: 'Change password', tags: ['Auth'],
        responses: [new OA\Response(response: 200, description: 'Password changed')]
    )]
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = auth()->user();
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Old password is incorrect'], 400);
        }
        $user->update(['password' => Hash::make($request->new_password)]);
        return response()->json(['message' => 'Password changed successfully']);
    }
}