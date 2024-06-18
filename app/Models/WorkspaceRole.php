<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkspaceRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'name',
        'create_board',
        'update_board',
        'delete_board',
        'invite_user',
        'remove_user',
        'create_role',
        'update_role',
        'remove_role',
        'assign_role',
    ];
}
