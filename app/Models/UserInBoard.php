<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInBoard extends Model
{
    use HasFactory;

    protected $table = 'user_in_board';

    protected $fillable = ['user_id', 'board_id', 'board_role_id'];
}
