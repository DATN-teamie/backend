<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AddUsersToItem extends Controller
{
    public function __invoke(Request $request, $item_id)
    {
        $request->validate([
            'user_ids' => 'array',
        ]);
        $user_ids = $request->input('user_ids');


        DB::table('user_in_item')->insertOrIgnore(
            array_map(function ($user_id) use (
                $item_id,
            ) {
                return [
                    'user_id' => $user_id,
                    'item_id' => $item_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $user_ids)
        );


        return response()->json(
            ['message' => 'Users Added to Item successfully'],
            200
        );
    }

}
