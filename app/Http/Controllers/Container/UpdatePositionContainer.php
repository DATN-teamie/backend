<?php

namespace App\Http\Controllers\Container;

use App\Events\UpdatedContainerPosition;
use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;

class UpdatePositionContainer extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedRequest = $request->validate([
            'containers' => 'required|array',
            'board_id' => 'required|integer|exists:boards,id',
        ]);

        $board_id = $validatedRequest['board_id'];
        $containers = $validatedRequest['containers'];

        foreach ($containers as $container) {
            Container::where('id', $container['id'])->update([
                'position' => $container['position'],
            ]);
        }
        broadcast(
            new UpdatedContainerPosition($board_id, $containers)
        )->toOthers();

        return response(
            [
                'message' => 'Container updated position successfully',
                'containers' => $containers,
            ],
            200
        );
    }
}
