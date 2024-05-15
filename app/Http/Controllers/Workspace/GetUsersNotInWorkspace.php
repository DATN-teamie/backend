<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GetUsersNotInWorkspace extends Controller
{
    public function __invoke(Request $request, $workspace_id)
    {
        $search = $request->get('search');
        
        $users = User::whereNotIn('id', function ($query) use ($workspace_id) {
            $query
                ->select('user_id')
                ->from('user_in_workspace')
                ->where('workspace_id', $workspace_id);
        })
            ->when($search, function ($query, $search) {
                return $query
                    ->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            })
            ->paginate(10);

        return response()->json(['users' => $users], 200);
    }
}
