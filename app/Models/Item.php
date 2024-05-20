<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Item extends Model
{
    use HasFactory, HasUuids;

    public function newUniqueId()
    {
        return 'item-'.Uuid::uuid4();
    }

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
