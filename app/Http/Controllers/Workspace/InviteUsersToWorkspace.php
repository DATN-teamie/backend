<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserInWorkspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class InviteUsersToWorkspace extends Controller
{
    public function __invoke(Request $request, $workspace_id)
    {
        $request->validate([
            'user_ids' => 'array',
        ]);

        if (!$this->checkPermission($workspace_id)) {
            return response(
                ['message' => 'You do not have permission to invite users'],
                403
            );
        }
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

    private function checkPermission($workspace_id)
    {
        $user_id = Auth::id();

        $invite_user = DB::table('user_in_workspace')
            ->join(
                'workspace_roles',
                'user_in_workspace.workspace_role_id',
                '=',
                'workspace_roles.id'
            )
            ->where('user_in_workspace.user_id', $user_id)
            ->where('user_in_workspace.workspace_id', $workspace_id)
            ->select('workspace_roles.invite_user')
            ->first()->invite_user;

        return $invite_user;
    }

    private function addUserToPublicBoard($workspace_id, $user_id)
    {
        $public_board_ids = DB::table('boards')
            ->where('workspace_id', $workspace_id)
            ->where('is_private', false)
            ->pluck('id');

        if (!$public_board_ids || $public_board_ids->isEmpty()) {
            return;
        }

        $role_everyone_board_ids = DB::table('board_roles')
            ->whereIn('board_id', $public_board_ids)
            ->where('name', 'everyone')
            ->pluck('id');

        if (!$role_everyone_board_ids || $role_everyone_board_ids->isEmpty()) {
            return;
        }

        $records = [];

        foreach ($public_board_ids as $index => $public_board_id) {
            $records[] = [
                'user_id' => $user_id,
                'board_id' => $public_board_id,
                'board_role_id' => $role_everyone_board_ids[$index],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('user_in_board')->insertOrIgnore($records);
    }
}
