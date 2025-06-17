<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Studyprogram extends Model
{
    protected $fillable = [
        'name',
    ];

    public function majoring(): BelongsTo
    {
        return $this->belongsTo(Majoring::class);
    }
}
