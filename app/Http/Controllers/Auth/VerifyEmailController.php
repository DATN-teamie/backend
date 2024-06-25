<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(string $user_id, string $verify_email_token)
    {
        $user = User::findOrFail($user_id);
        if (
            $user->verify_email_token &&
            $user->verify_email_token == $verify_email_token
        ) {
            $user->markEmailAsVerified();
            $user->verify_email_token = null;
            $user->save();

            return redirect()->to(
                config('app.frontend_url') .
                    '/verify-email-success/' .
                    $user->email
            );
        }
        return redirect()->to(
            config('app.frontend_url') . '/verify-email-failed/' . $user->email
        );
    }
}
