<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimitLoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['attempt', 'second'];
}
