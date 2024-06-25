<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetForgotPassword extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|string|exists:users,id',
            'token' => 'required|string',
            'new_password' => 'required|string|min:4',
            'confirm_password' => 'required|string|min:4|same:new_password',
        ]);

        $user = User::find($validated['user_id']);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!$user->forgot_pass_token) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        if ($user->forgot_pass_token !== $validated['token']) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $hashPassword = Hash::make($validated['new_password']);
        $user->password = $hashPassword;
        $user->forgot_pass_token = null;
        $user->save();

        return response()->json(
            ['message' => 'Password reset successfully'],
            200
        );
    }
}
