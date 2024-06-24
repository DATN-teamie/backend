<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\BoardRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateBoardRole extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'board_id' => 'required|integer|exists:boards,id',
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

        if (!$this->checkPermission($validated['board_id'])) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage roles in this board',
                ],
                403
            );
        }

        $role = BoardRole::create([
            'name' => $validated['name'],
            'board_id' => $validated['board_id'],
            'create_container' => $validated['create_container'],
            'remove_container' => $validated['remove_container'],
            'create_item' => $validated['create_item'],
            'remove_item' => $validated['remove_item'],
            'member_board_management' => $validated['member_board_management'],
            'role_board_management' => $validated['role_board_management'],
            'item_member_management' => $validated['item_member_management'],
            'attachment_management' => $validated['attachment_management'],
            'checklist_management' => $validated['checklist_management'],
        ]);

        return response(
            [
                'message' => 'Role created successfully',
                'role' => $role,
            ],
            201
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
