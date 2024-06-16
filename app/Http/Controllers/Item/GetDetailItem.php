<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Item;
use Illuminate\Http\Request;

class GetDetailItem extends Controller
{
    public function __invoke(Request $request, string $item_id)
    {
        $item = Item::find($item_id);

        if (!$item) {
            return response()->json(
                [
                    'message' => 'Board not found',
                ],
                404
            );
        }

        // get item with item_attachments, user_in_item, checklist_items table
        $item = Item::with([
            'attachments',
            'userInItem',
            'checklistItems',
        ])->find($item_id);

        return response()->json([
            'item' => $item,
        ]);
    }
}
