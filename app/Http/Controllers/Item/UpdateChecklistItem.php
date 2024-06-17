<?php

namespace App\Http\Controllers\Item;
use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;

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

        $checklist_item->update([
            'name' => $validated['name'],
            'is_completed' => $validated['is_completed'],
        ]);

        return response([
            'message' => 'Checklist item updated successfully',
        ]);
    }
}
