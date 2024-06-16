<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GetUsersNotInItem extends Controller
{
    public function __invoke(Request $request, $item_id)
    {
        $board_id = $request->get('board_id');
        $search = $request->get('search');

        $users = User::whereIn('id', function ($query) use ($board_id) {
            $query
                ->select('user_id')
                ->from('user_in_board')
                ->where('board_id', $board_id);
        })
            ->whereNotIn('id', function ($query) use ($item_id) {
                $query
                    ->select('user_id')
                    ->from('user_in_item')
                    ->where('item_id', $item_id);
            })
            ->where(function ($query) use ($search) {
                $query
                    ->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            })
            ->paginate(10);
        return response()->json(['users' => $users], 200);
    }
}
