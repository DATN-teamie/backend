<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\BoardRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateBoardRole extends Controller
{
    public function __invoke(
        Request $request,
        string $boardId,
        string $roleBoardId
    ) {
        $validated = $request->validate([
            'name' => 'required|string',
            'create_container' => 'required|boolean',
            'remove_container' => 'required|boolean',
            'create_item' => 'required|boolean',
            'remove_item' => 'required|boolean',
            'member_board_management' => 'required|boolean',
            'role_board_management' => 'required|boolean',
            'item_member_management' => 'required|boolean',
            'attachment_management' => 'required|boolean',
            'checklist_management' => 'required|boolean',
        ]);

        if (!$this->checkPermission($boardId)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage roles in this board',
                ],
                403
            );
        }

        $role = BoardRole::find($roleBoardId);
        $role->name = $validated['name'];
        $role->create_container = $validated['create_container'];
        $role->remove_container = $validated['remove_container'];
        $role->create_item = $validated['create_item'];
        $role->remove_item = $validated['remove_item'];
        $role->member_board_management = $validated['member_board_management'];
        $role->role_board_management = $validated['role_board_management'];
        $role->item_member_management = $validated['item_member_management'];
        $role->attachment_management = $validated['attachment_management'];
        $role->checklist_management = $validated['checklist_management'];

        $role->save();

        return response(
            [
                'message' => 'Role updated successfully.',
                'role' => $role,
            ],
            200
        );
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
}
