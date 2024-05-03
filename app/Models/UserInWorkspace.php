<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInWorkspace extends Model
{
    use HasFactory;

    protected $table = 'user_in_workspace';

    protected $fillable = ['user_id', 'workspace_id', 'workspace_role_id'];
}
