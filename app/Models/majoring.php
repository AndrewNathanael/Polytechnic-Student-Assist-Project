<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class majoring extends Model
{
    /** @use HasFactory<\Database\Factories\MajoringFactory> */
    use HasFactory;
    protected $fillable = [
        'name_majoring',
    ];

    public function studyprograms(): HasMany
    {
        return $this->hasMany(Studyprogram::class);
    }
}
