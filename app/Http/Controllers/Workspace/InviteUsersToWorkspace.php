<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInWorkspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class InviteUsersToWorkspace extends Controller
{
    public function __invoke(Request $request, $workspace_id)
    {
        $request->validate([
            'user_ids' => 'array',
        ]);
        $user_ids = $request->input('user_ids');

        $role_everyone_id = DB::table('workspace_roles')
            ->where('workspace_id', $workspace_id)
            ->where('name', 'everyone')
            ->value('id');

        DB::table('user_in_workspace')->insertOrIgnore(
            array_map(function ($user_id) use (
                $workspace_id,
                $role_everyone_id
            ) {
                return [
                    'user_id' => $user_id,
                    'workspace_id' => $workspace_id,
                    'workspace_role_id' => $role_everyone_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $user_ids)
        );

        foreach ($user_ids as $user_id) {
            $this->addUserToPublicBoard($workspace_id, $user_id);
        }

        return response()->json(
            ['message' => 'Users invited successfully'],
            200
        );
    }

    private function addUserToPublicBoard($workspace_id, $user_id)
    {
        $public_board_id = DB::table('boards')
            ->where('workspace_id', $workspace_id)
            ->where('is_private', false)
            ->value('id');

        if (!$public_board_id) {
            return;
        }

        $role_everyone_board_id = DB::table('board_roles')
            ->where('board_id', $public_board_id)
            ->where('name', 'everyone')
            ->value('id');

        if (!$role_everyone_board_id) {
            return;
        }

        DB::table('user_in_board')->insertOrIgnore([
            'user_id' => $user_id,
            'board_id' => $public_board_id,
            'board_role_id' => $role_everyone_board_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
