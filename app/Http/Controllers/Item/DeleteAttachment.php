<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteAttachment extends Controller
{
    public function __invoke(Request $request, string $attachment_id)
    {
        $file = ItemAttachment::find($attachment_id);
        if (!$file) {
            return response(
                [
                    'message' => 'File attachment not found',
                ],
                404
            );
        }

        $item = Item::find($file->item_id);
        if (!$item || !$this->checkPermission($item->container->board_id)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage attachments in item',
                ],
                403
            );
        }

        $file->delete();

        return response(
            [
                'message' => 'File attachment removed successfully',
            ],
            200
        );
    }
    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $attachment_management = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.attachment_management')
            ->first()->attachment_management;

        return $attachment_management;
    }
}
