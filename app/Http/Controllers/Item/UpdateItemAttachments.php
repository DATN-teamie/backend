<?php

namespace App\Http\Controllers\Item;
use App\Http\Controllers\Controller;
use App\Models\ItemAttachment;
use Illuminate\Http\Request;
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
}
