<?php

namespace App\Http\Controllers\Container;

use App\Events\CreatedNewContainer;
use App\Http\Controllers\Controller;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateContainer extends Controller
{
    public function __invoke(Request $request)
    {
        $container = $request->validate([
            'board_id' => 'required|integer|exists:boards,id',
            'title' => 'required|string',
            'position' => 'required|integer',
        ]);

        if (!$this->checkPermission($container['board_id'])) {
            return response(
                ['message' => 'You do not have permission to create container'],
                403
            );
        }

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
    private function checkPermission($board_id)
    {
        $user_id = Auth::id();

        $create_container = DB::table('user_in_board')
            ->join(
                'board_roles',
                'user_in_board.board_role_id',
                '=',
                'board_roles.id'
            )
            ->where('user_in_board.user_id', $user_id)
            ->where('user_in_board.board_id', $board_id)
            ->select('board_roles.create_container')
            ->first()->create_container;

        return $create_container;
    }
}
