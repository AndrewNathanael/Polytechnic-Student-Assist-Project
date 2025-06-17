<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lecturer_schedule extends Model
{
    /** @use HasFactory<\Database\Factories\LecturerScheduleFactory> */
    use HasFactory;

    public function lecturer(){
        return $this->belongsTo(lecturer::class);
    }
}
