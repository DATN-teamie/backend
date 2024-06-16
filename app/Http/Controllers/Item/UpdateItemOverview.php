<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateItemOverview extends Controller
{
    public function __invoke(Request $request, string $item_id)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
        ]);

        $item = Item::find($item_id);
        if (!$item) {
            return response(['message' => 'Item not found'], 404);
        }

        $item->title = $validated['title'];
        
        // check start_date less than due_date
        if ($request->start_date && $request->due_date) {
            if ($validated['start_date'] > $validated['due_date']) {
                return response(['message' => 'Start date must be less than due date'], 400);
            }
        }

        if ($request->start_date) {
            $item->start_date = $validated['start_date'];
        }
        if ($request->due_date) {
            $item->due_date = $validated['due_date'];
        }
       
        $item->save();

        return response([
            'message' => 'Item updated successfully',
            'item' => $item,
        ]);
    }
}
