<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class complaint extends Model
{
    /** @use HasFactory<\Database\Factories\ComplaintFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'date',
        'image',
        'description',
        'student_id',
        'status',
        'is_anonymous',
        'code',
    ];
    protected $casts = [
        'date' => 'datetime',
        'is_anonymous' => 'boolean',
    ];
    protected $appends = ['image_url'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/complaints' . $this->image) : null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($complaint) {
            $complaint->code = self::generateUniqueCode();
        });
    }

    private static function generateUniqueCode(): string
    {
        $maxAttempts = 100; // Prevent infinite loop
        $attempt = 0;

        do {
            $letters = chr(rand(65, 90)) . chr(rand(65, 90)); // Two random capital letters
            $numbers = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // Three digits
            $code = $letters . $numbers;
            $attempt++;

            $exists = self::where('code', $code)->exists();
        } while ($exists && $attempt < $maxAttempts);

        if ($attempt >= $maxAttempts) {
            throw new \RuntimeException('Unable to generate unique code after ' . $maxAttempts . ' attempts');
        }

        return $code;
    }
}
