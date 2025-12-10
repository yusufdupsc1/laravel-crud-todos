<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    /**
     * Mass-assignable attributes for create/update calls.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'is_done',
    ];
}
