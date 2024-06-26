<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $request->validate([
            // 'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
            ],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', 'confirmed'],
        ]);

        $verify_email_token = Str::random(60);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verify_email_token' => $verify_email_token,
        ]);

        $verificationUrl =
            config('app.url') .
            "/api/verify-email/{$user->id}/{$verify_email_token}";

        Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

        return response(
            [
                'message' => 'User created successfully',
                'user' => $user,
            ],
            201
        );
    }
}
