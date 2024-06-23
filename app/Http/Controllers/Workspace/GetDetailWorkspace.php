<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInWorkspace;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetDetailWorkspace extends Controller
{
    public function __invoke(Request $request, string $workspaceId)
    {
        $workspace = Workspace::find($workspaceId);
        // if user not in workspace return 403
        $auth_id = Auth::id();
        $user = UserInWorkspace::where('workspace_id', $workspaceId)
            ->where('user_id', $auth_id)
            ->first();
        if (!$user) {
            return response()->json(
                [
                    'message' =>
                        'You do not have permission to access this workspace',
                ],
                403
            );
        }

        if (!$workspace) {
            return response()->json(
                [
                    'message' => 'Workspace not found',
                ],
                404
            );
        }
        return response()->json([
            'workspace' => $workspace,
        ]);
    }
}
