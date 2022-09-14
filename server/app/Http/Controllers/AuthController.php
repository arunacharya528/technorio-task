<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // dd($request);
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'There was problem logging into your account',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $userPermissions = [
                'admin' => [
                    'access:all'
                ],
                'recruiter' => [
                    "job:post",
                    "candidate:index"
                ],
                'interviewer' => [
                    "take:interview"
                ]
            ];
            $permissions = [
                1 => $userPermissions['admin'],
                2 => $userPermissions['recruiter'],
                3 => $userPermissions['interviewer']
            ];

            $user = User::where('email', $request->email)->first();
            $user['token'] = $user->createToken("token", $permissions[$user->role])->plainTextToken;

            return response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
