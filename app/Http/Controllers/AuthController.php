<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class AuthController extends Controller
{
	public function register(Request $request)
	{
		$validated = $request->validate([
			'first_name' => 'required|string|max:255',
			'last_name' => 'required|string|max:255',
			'username' => 'required|string|max:255|unique:users',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:8|confirmed',
		]);

		$user = User::create([
			'first_name' => $validated['first_name'],
			'last_name' => $validated['last_name'],
			'username' => $validated['username'],
			'email' => $validated['email'],
			'password' => $validated['password'],
		]);

		return response()->json([
			'message' => 'User registered successfully',
			'user' => $user,
		], 201);
	}

	public function login(Request $request)
	{
		$validated = $request->validate([
			'email' => 'required|string|email',
			'password' => 'required|string',
		]);

		$user = User::where('email', $validated['email'])->first();

		if (!$user || !Hash::check($validated['password'], $user->password)) {
			return response()->json(['message' => 'Invalid credentials'], 401);
		}

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json([
			'message' => 'Login successful',
			'token' => $token,
			'user' => $user,
		], 200);
	}

	public function user(Request $request)
	{
		return response()->json($request->user());
	}

	public function logout(Request $request)
	{
		$request->user()->tokens()->delete();

		return response()->json(['message' => 'Logged out successfully'], 200);
	}
}
