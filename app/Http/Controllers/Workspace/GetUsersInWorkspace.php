<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetUsersInWorkspace extends Controller
{
    public function __invoke(Request $request, $workspace_id)
    {
        $users = User::whereIn('id', function ($query) use ($workspace_id) {
            $query
                ->select('user_id')
                ->from('user_in_workspace')
                ->where('workspace_id', $workspace_id);
        })->paginate(30);

        foreach ($users as $user) {
            $workspace_role_id = DB::table('user_in_workspace')
                ->where('user_id', $user->id)
                ->where('workspace_id', $workspace_id)
                ->value('workspace_role_id');
            $workspace_role_name = DB::table('workspace_roles')
                ->where('id', $workspace_role_id)
                ->value('name');

            $user->workspace_role_id = $workspace_role_id;
            $user->workspace_role_name = $workspace_role_name;
        }
        return response()->json(['users' => $users], 200);
    }
}
