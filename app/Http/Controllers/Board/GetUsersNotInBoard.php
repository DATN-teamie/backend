<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GetUsersNotInBoard extends Controller
{
    public function __invoke(Request $request, $board_id)
    {
        $workspace_id = $request->get('workspace_id');
        $search = $request->get('search');

        $users = User::whereIn('id', function ($query) use ($workspace_id) {
            $query
                ->select('user_id')
                ->from('user_in_workspace')
                ->where('workspace_id', $workspace_id);
        })
            ->whereNotIn('id', function ($query) use ($board_id) {
                $query
                    ->select('user_id')
                    ->from('user_in_board')
                    ->where('board_id', $board_id);
            })
            ->where(function ($query) use ($search) {
                $query
                    ->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            })
            ->paginate(10);
        return response()->json(['users' => $users], 200);
    }
}
