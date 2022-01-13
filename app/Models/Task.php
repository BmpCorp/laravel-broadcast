<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';

    protected $fillable = [
        'name',
        'is_complete',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
