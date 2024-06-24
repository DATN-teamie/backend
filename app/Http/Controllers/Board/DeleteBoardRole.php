<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\BoardRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteBoardRole extends Controller
{
    public function __invoke(Request $request, string $boardId, string $roleId)
    {
        if (!$this->checkPermission($boardId)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage roles in this board',
                ],
                403
            );
        }
        if ($this->checkRoleAssigned($boardId, $roleId)) {
            return response(
                [
                    'message' =>
                        'Role is already assigned to user(s) in this board',
                ],
                403
            );
        }

        BoardRole::where('id', $roleId)->delete();

        return response()->json(['message' => 'Role deleted from board']);
    }

    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $role_board_management = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.role_board_management')
            ->first()->role_board_management;

        return $role_board_management;
    }

    private function checkRoleAssigned($boardId, $roleId)
    {
        $role_assigned = DB::table('user_in_board')
            ->where('board_id', $boardId)
            ->where('board_role_id', $roleId)
            ->count();

        if ($role_assigned > 0) {
            return true;
        } else {
            return false;
        }
    }
}
