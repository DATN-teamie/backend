<?php

namespace App\Http\Controllers\Container;

use App\Events\UpdateContainerTitleEvent;
use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;

class UpdateContainerTitle extends Controller
{
    public function __invoke(Request $request, $containerId)
    {
        $validated = $request->validate([
            'title' => 'required|string',
        ]);

        $container = Container::find($containerId);
        if (!$container) {
            return response(
                [
                    'message' => 'Container not found',
                ],
                404
            );
        }
        $container->title = $validated['title'];
        $container->save();

        UpdateContainerTitleEvent::dispatch($container);

        return response(
            [
                'message' => 'Container title updated successfully',
                'container' => $container,
            ],
            200
        );
    }
}
