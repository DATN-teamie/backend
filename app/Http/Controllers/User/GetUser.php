<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetUser extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json(['user' => Auth::user()]);
    }
}
