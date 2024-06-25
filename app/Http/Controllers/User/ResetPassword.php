<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'current_password' => 'required|string|min:4',
            'new_password' => 'required|string|min:4',
            'confirm_password' => 'required|string|min:4|same:new_password',
        ]);

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['current_password'],
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $validated['email'])->first();
        $hashPassword = Hash::make($validated['new_password']);
        $user->password = $hashPassword;
        $user->save();

        return response()->json(
            ['message' => 'Password updated successfully'],
            200
        );
    }
}
