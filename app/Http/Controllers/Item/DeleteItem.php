<?php

namespace App\Http\Controllers\Item;

use App\Events\DeleteContainerEvent;
use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if (!$this->checkPermission($item->container->board_id)) {
            return response(
                ['message' => 'You do not have permission to delete item'],
                403
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
    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $remove_item = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.remove_item')
            ->first()->remove_item;

        return $remove_item;
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
