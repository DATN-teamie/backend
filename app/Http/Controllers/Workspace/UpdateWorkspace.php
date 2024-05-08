<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateWorkspace extends Controller
{
    public function __invoke(Request $request, string $workspace_id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'cover_img' => ['nullable', 'image', 'max:5120'],
        ]);

        $workspace = Workspace::find($workspace_id);
        if (!$workspace) {
            return response(['message' => 'Workspace not found'], 404);
        }

        $workspace->name = $validated['name'];
        $workspace->description = $validated['description'];

        if ($request->file('cover_img')) {
            $workspace->cover_img = $this->changeCoverImage(
                $request,
                $workspace->cover_img
            );
        }
        $workspace->save();

        return response([
            'message' => 'Workspace updated successfully',
            'workspace' => $workspace,
        ]);
    }
    private function changeCoverImage($request, $old_cover_img)
    {
        $image = $request->file('cover_img');
        if ($old_cover_img) {
            Storage::disk('minio')->delete($old_cover_img);
        }
        return Storage::disk('minio')->put('Workspace_cover_img', $image);
    }
}
