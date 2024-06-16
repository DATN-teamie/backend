<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GetUsersInItem extends Controller
{
    public function __invoke(Request $request, $item_id)
    {
        $users = User::whereIn('id', function ($query) use ($item_id) {
            $query
                ->select('user_id')
                ->from('user_in_item')
                ->where('item_id', $item_id);
        })->paginate(30);

        return response()->json(['users' => $users], 200);
    }
}
