<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateBoard extends Controller
{
    public function __invoke(Request $request, string $board_id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'cover_img' => ['nullable', 'image', 'max:5120'],
        ]);

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
    private function changeCoverImage($request, $old_cover_img)
    {
        $image = $request->file('cover_img');
        if ($old_cover_img) {
            Storage::disk('minio')->delete($old_cover_img);
        }
        return Storage::disk('minio')->put('Board_cover_img', $image);
    }
}
