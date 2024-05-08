<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\UserInWorkspace;
use App\Models\Workspace;
use App\Models\WorkspaceRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreateWorkspace extends Controller
{
    public function __invoke(Request $request)
    {
        $workspace = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'cover_img' => ['nullable', 'image', 'max:5120'],
        ]);

        $workspace['cover_img'] = $this->uploadImage($request, 'cover_img');
        $workspace['owner_id'] = Auth::id();

        $workspace = Workspace::create($workspace);

        $owner_role = $this->createDefaultRole($workspace);

        $this->addRoleToOwner($workspace, $owner_role);

        return response(
            [
                'message' => 'Workspace created successfully',
                'workspace' => $workspace,
            ],
            201
        );
    }
    private function uploadImage(Request $request, $upload_file)
    {
        if ($request->file($upload_file)) {
            $image = $request->file($upload_file);
            return Storage::disk('minio')->put('Workspace_cover_img', $image);
        }
        return null;
    }

    private function createDefaultRole($workspace)
    {
        $owner_role = WorkspaceRole::create([
            'name' => 'owner',
            'workspace_id' => $workspace->id,
            'create_board' => true,
        ]);
        WorkspaceRole::create([
            'name' => 'everyone',
            'workspace_id' => $workspace->id,
            'create_board' => false,
        ]);
        return $owner_role;
    }

    private function addRoleToOwner($workspace, $owner_role)
    {
        UserInWorkspace::create([
            'user_id' => Auth::id(),
            'workspace_id' => $workspace->id,
            'workspace_role_id' => $owner_role->id,
        ]);
    }
}
