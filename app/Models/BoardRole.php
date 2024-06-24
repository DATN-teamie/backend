<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'board_id',
        'name',
        'create_container',
        'remove_container',
        'create_item',
        'remove_item',
        'member_board_management',
        'role_board_management',
        'item_member_management',
        'attachment_management',
        'checklist_management',
    ];
}
