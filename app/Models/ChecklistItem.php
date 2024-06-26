<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'name', 'is_completed'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
