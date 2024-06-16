<?php

namespace App\Http\Controllers\Item;
use App\Http\Controllers\Controller;
use App\Models\ItemAttachment;
use Illuminate\Http\Request;

class GetListItemAttachments extends Controller
{
    public function __invoke(Request $request, $item_id)
    {
        $attachments = ItemAttachment::where('item_id', $item_id)->get();
        return response([
            'attachments' => $attachments,
        ]);
    }
}
