<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyLogin extends Controller
{
    //
    public function __invoke(Request $request)
    {
        if(Auth::check()){
            return response([
                'message' => 'User is already logged in',
                'user' => Auth::user(),
            ], 200);
        }
        return response([
            'message' => 'User is not logged in',
        ], 401);   
    }
}
