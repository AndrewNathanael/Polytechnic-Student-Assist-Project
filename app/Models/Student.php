<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    /** @use HasFactory<\Database\Factories\StudentFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'gender',
        'gender',
        'nim',
        'phone',
        'address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function apointment(){
        return $this->hasMany(apointment::class);
    }

    public function majoring(){
        return $this->belongsTo(majoring::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
