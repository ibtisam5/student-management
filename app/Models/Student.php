<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_number',
        'full_name',
        'email',
        'phone',
        'major',
        'academic_year',
        'status',
    ];
    public function enrollments(): HasMany
{
    return $this->hasMany(Enrollment::class);
}

public function courses(): BelongsToMany
{
    return $this->belongsToMany(Course::class, 'enrollments')
        ->withPivot([
            'id',
            'semester',
            'academic_year',
            'enrolled_at',
        ])
        ->withTimestamps();
}

public function aiAnalyses(): HasMany
{
    return $this->hasMany(AiAnalysis::class);
}
}

