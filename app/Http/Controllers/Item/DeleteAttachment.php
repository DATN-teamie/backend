<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\ItemAttachment;
use Illuminate\Http\Request;

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

        $file->delete();

        return response(
            [
                'message' => 'File attachment removed successfully',
            ],
            200
        );
    }
}
