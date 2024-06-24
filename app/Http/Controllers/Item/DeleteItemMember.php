<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\UserInItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteItemMember extends Controller
{
    public function __invoke(Request $request, string $item_id, string $user_id)
    {
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
        $user_in_item = UserInItem::where('item_id', $item_id)
            ->where('user_id', $user_id)
            ->first();
        if (!$user_in_item) {
            return response(
                [
                    'message' => 'User not found',
                ],
                404
            );
        }

        $user_in_item->delete();

        return response(
            [
                'message' => 'User removed successfully',
            ],
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
