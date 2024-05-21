<?php

namespace App\Http\Controllers\Container;

use App\Events\CreatedNewContainer;
use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;

class CreateContainer extends Controller
{
    public function __invoke(Request $request)
    {
        $container = $request->validate([
            'board_id' => 'required|integer|exists:boards,id',
            'title' => 'required|string',
            'position' => 'required|integer',
        ]);

        $container = Container::create($container);

        broadcast(new CreatedNewContainer($container))->toOthers();

        return response(
            [
                'message' => 'Container created successfully',
                'container' => $container,
            ],
            201
        );
    }
}
