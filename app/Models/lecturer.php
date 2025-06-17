<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lecturer extends Model
{
    /** @use HasFactory<\Database\Factories\LecturerFactory> */
    use HasFactory;

    public function apointment(){
        return $this->hasMany(apointment::class);
    }

    public function student(){
        return $this->hasMany(student::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
