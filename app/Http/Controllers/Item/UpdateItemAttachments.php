<?php

namespace App\Http\Controllers\Item;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateItemAttachments extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'attachments' => ['required', 'array'],
            'attachments.*' => ['required', 'file', 'max:5120'],
        ]);

        $item = Item::find($validated['item_id']);
        if (!$item || !$this->checkPermission($item->container->board_id)) {
            return response(
                [
                    'message' =>
                        'You do not have permission to manage attachments in item',
                ],
                403
            );
        }

        // for each attachment, store it in the storage
        foreach ($validated['attachments'] as $attachment) {
            $file_url = $this->storeAttachment($attachment);
            $attachment = ItemAttachment::create([
                'item_id' => $validated['item_id'],
                'file_url' => $file_url,
                'file_name' => $attachment->getClientOriginalName(),
                'file_type' => $attachment->getClientMimeType(),
            ]);
        }
        return response([
            'message' => 'Attachments uploaded successfully',
        ]);
    }
    private function storeAttachment($attachment)
    {
        $path = Storage::disk('minio')->put('Item_Attachments', $attachment);
        return $path;
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
