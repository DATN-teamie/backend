<?php

namespace App\Http\Controllers\Item;
use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use App\Models\Item;
use App\Models\ItemAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AddChecklistItem extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'name' => 'required|string',
        ]);

        $item = Item::find($validated['item_id']);

        if (!$item || !$this->checkPermission($item->container->board_id)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage checklist in item',
                ],
                403
            );
        }

        ChecklistItem::create([
            'item_id' => $validated['item_id'],
            'name' => $validated['name'],
            'is_completed' => false,
        ]);

        return response([
            'message' => 'Add checklist successfully',
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
