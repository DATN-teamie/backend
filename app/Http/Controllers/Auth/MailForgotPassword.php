<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MailForgotPassword extends Controller
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

        $forgot_pass_token = Str::random(60);

        $user->forgot_pass_token = $forgot_pass_token;
        $user->save();

        $forgotUrl =
            config('app.frontend_url') .
            "/mail-forgot-password/{$user->id}/{$forgot_pass_token}";

        Mail::to($user->email)->send(new ForgotPassEmail($user, $forgotUrl));

        return response(
            [
                'message' => 'Mail reset password sent successfully',
                'user' => $user,
            ],
            201
        );
    }
}
