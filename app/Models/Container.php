<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;


class Container extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'containers';

    protected $fillable = ['board_id', 'title', 'position'];

    public function newUniqueId()
    {
        return 'container-'.Uuid::uuid4();
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

}
