<?php

namespace App\Http\Controllers\Item;
use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateChecklistItem extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'checklist_item_id' => 'required|exists:checklist_items,id',
            'name' => 'required|string',
            'is_completed' => 'required|boolean',
        ]);

        $checklist_item = ChecklistItem::find($validated['checklist_item_id']);
        if (!$checklist_item) {
            return response(
                [
                    'message' => 'Checklist item not found',
                ],
                404
            );
        }

        $item = Item::find($checklist_item->item_id);
        if (!$item || !$this->checkPermission($item->container->board_id)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage checklist in item',
                ],
                403
            );
        }

        $checklist_item->update([
            'name' => $validated['name'],
            'is_completed' => $validated['is_completed'],
        ]);

        return response([
            'message' => 'Checklist item updated successfully',
        ]);
    }

    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $checklist_management = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.checklist_management')
            ->first()->checklist_management;

        return $checklist_management;
    }
}
