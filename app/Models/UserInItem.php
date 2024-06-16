<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInItem extends Model
{
    use HasFactory;

    protected $table = 'user_in_item';

    protected $fillable = ['user_id', 'item_id'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
