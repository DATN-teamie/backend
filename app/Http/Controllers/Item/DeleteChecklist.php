<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteChecklist extends Controller
{
    public function __invoke(Request $request, string $checklist_id)
    {
        $checklist = ChecklistItem::find($checklist_id);
        if (!$checklist) {
            return response(
                [
                    'message' => 'Checklist Item not found',
                ],
                404
            );
        }

        $item = Item::find($checklist->item_id);
        if (!$item || !$this->checkPermission($item->container->board_id)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage checklist in item',
                ],
                403
            );
        }

        $checklist->delete();

        return response(
            [
                'message' => 'Checklist Item removed successfully',
            ],
            200
        );
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
