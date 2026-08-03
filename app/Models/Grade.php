<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'enrollment_id',
        'assessment_name',
        'assessment_type',
        'score',
        'maximum_score',
        'weight',
        'assessment_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'maximum_score' => 'decimal:2',
            'weight' => 'decimal:2',
            'assessment_date' => 'date',
        ];
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function percentage(): float
    {
        $maximumScore = (float) $this->maximum_score;

        if ($maximumScore <= 0) {
            return 0;
        }

        return round(
            ((float) $this->score / $maximumScore) * 100,
            2
        );
    }

    public function letterGrade(): string
    {
        $percentage = $this->percentage();

        return match (true) {
            $percentage >= 95 => 'A+',
            $percentage >= 90 => 'A',
            $percentage >= 85 => 'B+',
            $percentage >= 80 => 'B',
            $percentage >= 75 => 'C+',
            $percentage >= 70 => 'C',
            $percentage >= 65 => 'D+',
            $percentage >= 60 => 'D',
            default => 'F',
        };
    }

    public function gradeBadgeClass(): string
    {
        return match ($this->letterGrade()) {
            'A+', 'A' => 'success',
            'B+', 'B' => 'primary',
            'C+', 'C' => 'info',
            'D+', 'D' => 'warning',
            default => 'danger',
        };
    }
}
