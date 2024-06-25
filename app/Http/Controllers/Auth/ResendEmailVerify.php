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

class ResendEmailVerify extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request): Response
    {
        $request->validate([
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'exists:users,email',
            ],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response(
                [
                    'message' => 'User not found',
                ],
                404
            );
        }

        if ($user->email_verified_at) {
            return response(
                [
                    'message' => 'you are already verified, please login',
                ],
                200
            );
        }

        $verify_email_token = Str::random(60);

        $user->verify_email_token = $verify_email_token;
        $user->save();

        $verificationUrl =
            config('app.url') .
            "/api/verify-email/{$user->id}/{$verify_email_token}";

        Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

        return response(
            [
                'message' => 'Email verification sent successfully',
                'user' => $user,
            ],
            201
        );
    }
}
