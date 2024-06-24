<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InviteUsersToBoard extends Controller
{
    public function __invoke(Request $request, $board_id)
    {
        $request->validate([
            'user_ids' => 'array',
        ]);
        $user_ids = $request->input('user_ids');

        if (!$this->checkPermission($board_id)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage members in this board',
                ],
                403
            );
        }

        $role_everyone_id = DB::table('board_roles')
            ->where('board_id', $board_id)
            ->where('name', 'everyone')
            ->value('id');

        DB::table('user_in_board')->insertOrIgnore(
            array_map(function ($user_id) use ($board_id, $role_everyone_id) {
                return [
                    'user_id' => $user_id,
                    'board_id' => $board_id,
                    'board_role_id' => $role_everyone_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $user_ids)
        );

        return response()->json(
            ['message' => 'Users invited to Board successfully'],
            200
        );
    }

    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $member_board_management = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.member_board_management')
            ->first()->member_board_management;

        return $member_board_management;
    }
}
