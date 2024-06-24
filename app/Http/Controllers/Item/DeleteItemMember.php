<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\UserInItem;
use Illuminate\Http\Request;

class DeleteItemMember extends Controller
{
    public function __invoke(Request $request, string $item_id, string $user_id)
    {
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
}
