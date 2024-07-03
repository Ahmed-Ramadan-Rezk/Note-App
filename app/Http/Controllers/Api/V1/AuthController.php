<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        try {
            $credentials = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            if ($credentials->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'errors' => $credentials->errors()
                    ],
                    422
                );
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'User created successfully.',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Something went wrong.',
                    'error' => $th->getMessage()
                ],
                500
            );
        }
    }

    // Login a user
    public function login(Request $request)
    {
        try {
            $credentials = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'password' => ['required'],
            ]);

            if ($credentials->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'The given data was invalid.',
                        'errors' => $credentials->errors()
                    ],
                    422
                );
            }

            if (!Auth::attempt($request->only('name', 'password'))) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Invalid credentials.'
                    ],
                    401
                );
            }

            $user = User::where('name', $request->name)->firstOrFail();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'User logged in successfully.',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Something went wrong.',
                    'error' => $th->getMessage()
                ],
                500
            );
        }
    }

    // User profile
    public function profile(Request $request)
    {
        $userData = auth()->user();

        return response()->json(
            [
                'status' => true,
                'message' => 'Profile information.',
                'data' => $userData,
                'id' => $userData->id,
            ],
            200
        );
    }

    // Logout a user
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user instanceof \App\Models\User) {
            return $user->tokens()->delete();
        }

        return response()->json(
            [
                'status' => true,
                'message' => 'User logged out successfully.',
                'data' => [],
            ],
            200
        );
    }
}
