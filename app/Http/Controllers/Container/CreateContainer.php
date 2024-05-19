<?php

namespace App\Http\Controllers\Container;

use App\Http\Controllers\Controller;
use App\Models\UserInBoard;
use App\Models\Board;
use App\Models\BoardRole;
use App\Models\Container;
use App\Models\UserInWorkspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateContainer extends Controller
{
    public function __invoke(Request $request)
    {
        $container = $request->validate([
            'board_id' => 'required|integer',
            'title' => 'required|string',
            'position' => 'required|integer',
        ]);

        $container = Container::create($container);

        return response(
            [
                'message' => 'Container created successfully',
                'container' => $container,
            ],
            201
        );
    }
}
