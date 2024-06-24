<?php

namespace App\Http\Controllers\Container;

use App\Events\DeleteContainerEvent;
use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;

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
}
