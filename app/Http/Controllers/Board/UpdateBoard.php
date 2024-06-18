<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateBoard extends Controller
{
    public function __invoke(Request $request, string $board_id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'cover_img' => ['nullable', 'image', 'max:5120'],
        ]);

        if (!$this->checkPermission($board_id)) {
            return response(
                ['message' => 'You do not have permission to update board'],
                403
            );
        }

        $board = Board::find($board_id);
        if (!$board) {
            return response(['message' => 'Board not found'], 404);
        }

        $board->name = $validated['name'];

        if ($request->file('cover_img')) {
            $board->cover_img = $this->changeCoverImage(
                $request,
                $board->cover_img
            );
        }
        $board->save();

        return response([
            'message' => 'Board updated successfully',
            'board' => $board,
        ]);
    }
    private function checkPermission($workspace_id)
    {
        $user_id = Auth::id();

        $update_board = DB::table('user_in_workspace')
            ->join(
                'workspace_roles',
                'user_in_workspace.workspace_role_id',
                '=',
                'workspace_roles.id'
            )
            ->where('user_in_workspace.user_id', $user_id)
            ->where('user_in_workspace.workspace_id', $workspace_id)
            ->select('workspace_roles.update_board')
            ->first()->update_board;

        return $update_board;
    }

    private function changeCoverImage($request, $old_cover_img)
    {
        $image = $request->file('cover_img');
        if ($old_cover_img) {
            Storage::disk('minio')->delete($old_cover_img);
        }
        return Storage::disk('minio')->put('Board_cover_img', $image);
    }
}
