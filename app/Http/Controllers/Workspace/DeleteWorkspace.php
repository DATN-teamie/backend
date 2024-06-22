<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteWorkspace extends Controller
{
    public function __invoke(Request $request, string $workspaceId)
    {
        $workspace = Workspace::find($workspaceId);
        if (!$workspace) {
            return response(['message' => 'Workspace not found'], 404);
        }
        if ($workspace->owner_id != Auth::id()) {
            return response(
                [
                    'message' =>
                        'You are not authorized to delete this workspace',
                ],
                403
            );
        }
        $workspace->delete();

        return response(['message' => 'Workspace deleted successfully'], 200);
    }
}
