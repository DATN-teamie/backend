<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetUsersInBoard extends Controller
{
    public function __invoke(Request $request, $board_id)
    {
        $users = User::whereIn('id', function ($query) use ($board_id) {
            $query
                ->select('user_id')
                ->from('user_in_board')
                ->where('board_id', $board_id);
        })->paginate(30);

        foreach ($users as $user) {
            $board_role_id = DB::table('user_in_board')
                ->where('user_id', $user->id)
                ->where('board_id', $board_id)
                ->value('board_role_id');
            $board_role_name = DB::table('board_roles')
                ->where('id', $board_role_id)
                ->value('name');

            $user->board_role_id = $board_role_id;
            $user->board_role_name = $board_role_name;
        }
        return response()->json(['users' => $users], 200);
    }
}
