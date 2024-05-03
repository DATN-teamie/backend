<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['card_id', 'file_url'];
}
