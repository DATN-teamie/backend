<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetListBoard extends Controller
{
    public function __invoke(Request $request)
    {
        $workspace_id = $request['workspace_id'];
        $user = User::find(Auth::id());
        $boards = $user->boards()->where('workspace_id', $workspace_id)->get();

        return response([
            'boards' => $boards,
        ]);
    }
}
