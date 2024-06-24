<?php

namespace App\Http\Controllers\Item;

use App\Events\CreatedNewItem;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateItem extends Controller
{
    public function __invoke(Request $request)
    {
        $item = $request->validate([
            'container_id' => 'required|string|exists:containers,id',
            'title' => 'required|string',
            'position' => 'required|integer',
        ]);

        $board_id = DB::table('containers')
            ->where('id', $item['container_id'])
            ->value('board_id');

        if (!$this->checkPermission($board_id)) {
            return response(
                ['message' => 'You do not have permission to create item'],
                403
            );
        }

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
    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $create_item = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.create_item')
            ->first()->create_item;

        return $create_item;
    }
}
