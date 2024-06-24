<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\BoardRole;
use App\Models\WorkspaceRole;
use Illuminate\Http\Request;

class GetListBoardRole extends Controller
{
    public function __invoke(Request $request, $board_id)
    {
        $board_roles = BoardRole::where('board_id', $board_id)
            ->orderBy('created_at', 'asc')
            ->get();
        return response([
            'boardRoles' => $board_roles,
        ]);
    }
}
