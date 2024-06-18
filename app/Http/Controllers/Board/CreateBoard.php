<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\UserInBoard;
use App\Models\Board;
use App\Models\BoardRole;
use App\Models\UserInWorkspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateBoard extends Controller
{
    public function __invoke(Request $request)
    {
        $board = $request->validate([
            'name' => 'required|string',
            'workspace_id' => 'required|exists:workspaces,id',
            'is_private' => 'required|boolean',
            'cover_img' => ['nullable', 'image', 'max:5120'],
        ]);

        if (!$this->checkPermission($board['workspace_id'])) {
            return response(
                ['message' => 'You do not have permission to create board'],
                403
            );
        }

        $board['cover_img'] = $this->uploadImage($request, 'cover_img');
        $board['owner_id'] = Auth::id();

        $board = Board::create($board);

        [$owner_role, $everyone_role] = $this->createDefaultRole($board);

        $this->addOwnerToBoard($board, $owner_role);
        $this->addUsersToPublicBoard($board, $everyone_role);

        return response(
            [
                'message' => 'Board created successfully',
                'board' => $board,
            ],
            201
        );
    }

    private function checkPermission($workspace_id)
    {
        $user_id = Auth::id();

        $create_board = DB::table('user_in_workspace')
            ->join(
                'workspace_roles',
                'user_in_workspace.workspace_role_id',
                '=',
                'workspace_roles.id'
            )
            ->where('user_in_workspace.user_id', $user_id)
            ->where('user_in_workspace.workspace_id', $workspace_id)
            ->select('workspace_roles.create_board')
            ->first()->create_board;

        return $create_board;
    }

    private function uploadImage(Request $request, $upload_file)
    {
        if ($request->file($upload_file)) {
            $image = $request->file($upload_file);
            return Storage::disk('minio')->put('Board_cover_img', $image);
        }
        return null;
    }

    private function createDefaultRole($board)
    {
        $owner_role = BoardRole::create([
            'name' => 'owner',
            'board_id' => $board->id,
            'create_list' => true,
        ]);
        $everyone_role = BoardRole::create([
            'name' => 'everyone',
            'board_id' => $board->id,
            'create_list' => false,
        ]);
        return [$owner_role, $everyone_role];
    }

    private function addOwnerToBoard($board, $owner_role)
    {
        UserInBoard::create([
            'user_id' => Auth::id(),
            'board_id' => $board->id,
            'board_role_id' => $owner_role->id,
        ]);
    }
    private function addUsersToPublicBoard($board, $everyone_role)
    {
        if ($board->is_private) {
            return;
        }
        $users = UserInWorkspace::where(
            'workspace_id',
            $board->workspace->id
        )->get();

        foreach ($users as $user) {
            if ($this->isUserInBoard($board, $user->user_id)) {
                continue;
            }
            UserInBoard::create([
                'user_id' => $user->user_id,
                'board_id' => $board->id,
                'board_role_id' => $everyone_role->id,
            ]);
        }
    }

    private function isUserInBoard($board, $user_id)
    {
        return UserInBoard::where('board_id', $board->id)
            ->where('user_id', $user_id)
            ->exists();
    }
}
