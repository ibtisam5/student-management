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
        $maximum = (float) $this->maximum_score;

        if ($maximum <= 0) {
            return 0;
        }

        return round(((float) $this->score / $maximum) * 100, 2);
    }
}
