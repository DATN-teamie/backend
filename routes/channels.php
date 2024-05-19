<?php

use App\Models\UserInBoard;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('created-new-container_board.${board_id}', function (
    $user,
    $board_id
) {
    return UserInBoard::where('user_id', $user->id)
        ->where('board_id', $board_id)
        ->exists();
});
