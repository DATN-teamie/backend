<?php

namespace App\Http\Controllers\Container;

use App\Http\Controllers\Controller;
use App\Models\Container;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetListContainer extends Controller
{
    public function __invoke(Request $request)
    {
        $board_id = $request['board_id'];
        $containers = Container::where('board_id', $board_id)
            ->select(['id', 'title', 'position'])
            ->with([
                'items' => function ($query) {
                    $query->with([
                        'attachments',
                        'userInItem' => function ($query) {
                            $query->leftJoin(
                                'users',
                                'user_in_item.user_id',
                                '=',
                                'users.id'
                            );
                        },
                        'checklistItems',
                    ]);
                },
            ])
            ->get();
        return response([
            'containers' => $containers,
        ]);
    }
}
