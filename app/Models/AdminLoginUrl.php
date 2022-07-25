<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLoginUrl extends Model
{
    use HasFactory;

    protected $fillable = ['admin_login_url'];
}
