<?php

namespace App\Http\Controllers\Item;

use App\Events\DeleteContainerEvent;
use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Item;
use Illuminate\Http\Request;

class DeleteItem extends Controller
{
    public function __invoke(Request $request, string $itemId)
    {
        $item = Item::find($itemId);
        if (!$item) {
            return response(
                [
                    'message' => 'Item not found',
                ],
                404
            );
        }

        $this->updateItemPositions($item);

        $item->delete();

        // broadcast
        $containers = Container::where('board_id', $item->container->board_id)
            ->select(['id', 'title', 'position'])
            ->with('items')
            ->get();

        DeleteContainerEvent::dispatch($item->container->board_id, $containers);

        return response(
            [
                'message' => 'Item deleted successfully',
            ],
            200
        );
    }
    private function updateItemPositions($deleteItem)
    {
        $items = Item::where('container_id', $deleteItem->container_id)
            ->where('position', '>', $deleteItem->position)
            ->orderBy('position', 'asc')
            ->get();

        foreach ($items as $item) {
            $item->position--;
            $item->save();
        }
    }
}
