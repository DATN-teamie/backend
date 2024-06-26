<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetListWorkspace extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find(Auth::id());
        return response([
            'workspaces' => $user->workspaces,
        ]);
    }
}
