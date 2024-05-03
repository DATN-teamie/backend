<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInCard extends Model
{
    use HasFactory;

    protected $table = 'user_in_card';

    protected $fillable = ['user_id', 'card_id'];
}
