<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'workspace_id',
        'owner_id',
        'name',
        'cover_img',
        'is_private',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    public function containers()
    {
        return $this->hasMany(Container::class);
    }
}
