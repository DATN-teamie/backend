<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\WorkspaceRole;
use Illuminate\Http\Request;

class GetDetailRoleWsp extends Controller
{
    public function __invoke(
        Request $request,
        string $workspaceId,
        string $roleWspId
    ) {
        $role = WorkspaceRole::find($roleWspId);

        if (!$role) {
            return response()->json(
                [
                    'message' => 'Role not found',
                ],
                404
            );
        }
        return response()->json([
            'roleWsp' => $role,
        ]);
    }
}
