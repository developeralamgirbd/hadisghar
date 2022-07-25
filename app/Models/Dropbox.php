<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dropbox extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['app_key','app_secret','redirect_url','refresh_token','access_token', 'email'];
}
