<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
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
        return response([
            'message' => 'Workspace created successfully',
            'workspace' => $workspace,
        ]);
    }
    private function uploadImage(Request $request, $upload_file)
    {
        if ($request->file($upload_file)) {
            $image = $request->file($upload_file);
            return Storage::disk('minio')->put('Workspace_cover_img', $image);
        }
        return null;
    }
}
