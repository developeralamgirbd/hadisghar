<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleList extends Model
{
    use HasFactory;
    protected $fillable = ['cron','command_name', 'last_run', 'next_run','frequency_id'];

    public function frequency(){
       return $this->belongsTo(taskScheduleFrequencie::class);
    }
}
