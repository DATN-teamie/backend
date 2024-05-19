<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    use HasFactory;

    protected $table = 'containers';

    protected $fillable = ['board_id', 'title', 'position'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
