<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;

class GetDetailWorkspace extends Controller
{
    public function __invoke(Request $request, string $workspaceId)
    {
        $workspace = Workspace::find($workspaceId);
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
