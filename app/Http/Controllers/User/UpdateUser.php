<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateUser extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'avatar' => ['nullable', 'image', 'max:5120'],
        ]);

        $user = User::find(Auth::id());
        if (!$user) {
            return response(['message' => 'User not found'], 404);
        }

        $user->name = $validated['name'];
        $user->description = $validated['description'];

        if ($request->file('avatar')) {
            $user->avatar = $this->changeAvatar(
                $request,
                $user->avatar
            );
        }
        $user->save();

        return response([
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }
    private function changeAvatar($request, $old_avatar)
    {
        $image = $request->file('avatar');
        if ($old_avatar) {
            Storage::disk('minio')->delete($old_avatar);
        }
        return Storage::disk('minio')->put('User_Avatar', $image);
    }
}
