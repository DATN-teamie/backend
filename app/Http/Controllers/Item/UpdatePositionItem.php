<?php

namespace App\Http\Controllers\Item;

use App\Events\UpdatedItemPosition;
use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\Item;
use Illuminate\Http\Request;

class UpdatePositionItem extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedRequest = $request->validate([
            'board_id' => 'required|integer|exists:boards,id',
            'items' => 'required|array',
        ]);

        $board_id = $validatedRequest['board_id'];
        $items = $validatedRequest['items'];

        foreach ($items as $item) {
            Item::where('id', $item['id'])->update([
                'position' => $item['position'],
                'container_id' => $item['container_id'],
            ]);
        }

        $containers = Container::where('board_id', $board_id)
            ->select(['id', 'title', 'position'])
            ->with('items')
            ->get();

        // pusher to large
        // $containers = Container::where('board_id', $board_id)
        //     ->select(['id', 'title', 'position'])
        //     ->with([
        //         'items' => function ($query) {
        //             $query->with([
        //                 'attachments',
        //                 'checklistItems',
        //                 'userInItem' => function ($query) {
        //                     $query->leftJoin(
        //                         'users',
        //                         'user_in_item.user_id',
        //                         '=',
        //                         'users.id'
        //                     );
        //                 },
        //             ]);
        //         },
        //     ])
        //     ->get();

        broadcast(
            new UpdatedItemPosition($board_id, $items, $containers)
        )->toOthers();

        return response(
            [
                'message' => 'Container updated position successfully',
                'items' => $items,
                'containers' => $containers,
            ],
            200
        );
    }
}
