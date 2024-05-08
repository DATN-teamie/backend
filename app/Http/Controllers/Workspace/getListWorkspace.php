<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class getListWorkspace extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find(Auth::id());
        $workspaces_id = $user->user_in_workspace->pluck('workspace_id');
        $workspaces = [];
        if($workspaces_id->count() > 0) {
            $workspaces = Workspace::whereIn('id', $workspaces_id)->get();
        }
        return response([
            'workspaces' => $workspaces,
        ]);
    }
}
