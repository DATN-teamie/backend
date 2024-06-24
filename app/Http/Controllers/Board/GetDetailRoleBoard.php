<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\BoardRole;
use Illuminate\Http\Request;

class GetDetailRoleBoard extends Controller
{
    public function __invoke(
        Request $request,
        string $boardId,
        string $roleBoardId
    ) {
        $role = BoardRole::find($roleBoardId);

        if (!$role) {
            return response()->json(
                [
                    'message' => 'Role not found',
                ],
                404
            );
        }
        return response()->json([
            'roleBoard' => $role,
        ]);
    }
}
