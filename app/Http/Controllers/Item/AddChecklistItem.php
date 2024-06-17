<?php

namespace App\Http\Controllers\Item;
use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use App\Models\ItemAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AddChecklistItem extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'name' => 'required|string',
        ]);

        ChecklistItem::create([
            'item_id' => $validated['item_id'],
            'name' => $validated['name'],
            'is_completed' => false,
        ]);

        return response([
            'message' => 'Add checklist successfully',
        ]);
    }
}
