<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'semester',
        'academic_year',
        'enrolled_at',
    ];

    protected function casts(): array
    {
        return [
            'academic_year' => 'integer',
            'enrolled_at' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function attendancePercentage(): float
    {
        $total = $this->attendances()->count();

        if ($total === 0) {
            return 0;
        }

        $attended = $this->attendances()
            ->whereIn('status', ['Present', 'Late'])
            ->count();

        return round(($attended / $total) * 100, 2);
    }

    public function gradePercentage(): float
    {
        $earned = (float) $this->grades()->sum('score');
        $maximum = (float) $this->grades()->sum('maximum_score');

        if ($maximum <= 0) {
            return 0;
        }

        return round(($earned / $maximum) * 100, 2);
    }
}
