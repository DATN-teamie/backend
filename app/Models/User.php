<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'description',
        'phone',
        'address',
        'title',
        'password',
        'verify_email_token',
        'forgot_pass_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function workspaces()
    {
        return $this->belongsToMany(
            Workspace::class,
            'user_in_workspace',
            'user_id',
            'workspace_id'
        );
    }

    public function boards()
    {
        return $this->belongsToMany(
            Board::class,
            'user_in_board',
            'user_id',
            'board_id'
        );
    }

    public function items()
    {
        return $this->belongsToMany(
            Item::class,
            'user_in_item',
            'user_id',
            'item_id'
        );
    }
}
