<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'title',
        'position',
        'description',
        'checklist_name',
        'start_date',
        'due_date',
    ];
}
