<?php

namespace App\Http\Controllers\Item;

use App\Events\CreatedNewItem;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class CreateItem extends Controller
{
    public function __invoke(Request $request)
    {
        $item = $request->validate([
            'container_id' => 'required|string|exists:containers,id',
            'title' => 'required|string',
            'position' => 'required|integer',
        ]);

        $item = Item::create($item);

        broadcast(new CreatedNewItem($item))->toOthers();

        return response(
            [
                'message' => 'Item created successfully',
                'item' => $item,
            ],
            201
        );
    }
}
