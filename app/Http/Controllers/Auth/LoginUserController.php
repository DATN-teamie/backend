<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class LoginUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (!Auth::user()->email_verified_at) {
            Auth::logout();
            return response(
                [
                    'message' => 'User is not verified Email',
                ],
                418
            );
        }

        return response([
            'message' => 'User logged in successfully',
            'user' => Auth::user(),
        ]);
    }
}
