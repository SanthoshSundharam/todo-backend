<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tasks extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'tasks_table';

    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];
}
