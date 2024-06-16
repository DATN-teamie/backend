<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'file_url', 'file_name', 'file_type'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
