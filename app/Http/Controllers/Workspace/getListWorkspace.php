<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\Request;

class getListWorkspace extends Controller
{
    public function __invoke(Request $request)
    {
        $workspaces = Workspace::all();
        return response([
            'workspaces' => $workspaces,
        ]);
    }
}
