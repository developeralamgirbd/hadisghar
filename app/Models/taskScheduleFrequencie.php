<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class taskScheduleFrequencie extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function schedule(){
       return $this->hasMany(ScheduleList::class);
    }
}
