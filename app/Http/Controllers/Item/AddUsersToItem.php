<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddUsersToItem extends Controller
{
    public function __invoke(Request $request, $item_id)
    {
        $request->validate([
            'user_ids' => 'array',
        ]);

        $item = Item::find($item_id);
        if (!$item || !$this->checkPermission($item->container->board_id)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage users in item',
                ],
                403
            );
        }

        $user_ids = $request->input('user_ids');

        DB::table('user_in_item')->insertOrIgnore(
            array_map(function ($user_id) use ($item_id) {
                return [
                    'user_id' => $user_id,
                    'item_id' => $item_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $user_ids)
        );

        return response()->json(
            ['message' => 'Users Added to Item successfully'],
            200
        );
    }
    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $item_member_management = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.item_member_management')
            ->first()->item_member_management;

        return $item_member_management;
    }
}
