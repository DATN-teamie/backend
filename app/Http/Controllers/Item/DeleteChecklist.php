<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;

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

        $checklist->delete();

        return response(
            [
                'message' => 'Checklist Item removed successfully',
            ],
            200
        );
    }
}
