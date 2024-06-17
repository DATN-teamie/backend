<?php

namespace App\Http\Controllers\Item;
use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use App\Models\ItemAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GetChecklistItem extends Controller
{
    public function __invoke(Request $request, $item_id)
    {
        $checklist_items = ChecklistItem::where('item_id', $item_id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response([
            'checklist_items' => $checklist_items,
        ]);
    }
}
