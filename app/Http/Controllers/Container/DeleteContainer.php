<?php

namespace App\Http\Controllers\Container;

use App\Events\DeleteContainerEvent;
use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteContainer extends Controller
{
    public function __invoke(Request $request, $containerId)
    {
        $container = Container::find($containerId);

        if (!$container) {
            return response(
                [
                    'message' => 'Container not found',
                ],
                404
            );
        }

        if (!$this->checkPermission($container->board_id)) {
            return response(
                ['message' => 'You do not have permission to remove container'],
                403
            );
        }

        $this->updateContainerPositions($container->position);

        $container->delete();

        // broadcast
        $containers = Container::where('board_id', $container->board_id)
            ->select(['id', 'title', 'position'])
            ->with('items')
            ->get();

        DeleteContainerEvent::dispatch($container->board_id, $containers);

        return response(
            [
                'message' => 'Container deleted successfully',
            ],
            200
        );
    }
    private function updateContainerPositions($deletedPosition)
    {
        $containers = Container::where('position', '>', $deletedPosition)
            ->orderBy('position', 'asc')
            ->get();

        foreach ($containers as $container) {
            $container->position--;
            $container->save();
        }
    }
    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $remove_container = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.remove_container')
            ->first()->remove_container;

        return $remove_container;
    }
}
