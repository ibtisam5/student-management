<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'analysis_type',
        'input_summary',
        'analysis',
        'recommendations',
        'performance_summary',
        'strengths',
        'weaknesses',
        'risk_level',
        'prediction',
        'metrics',
        'provider',
        'model',
    ];

    protected function casts(): array
    {
        return [
            'strengths' => 'array',
            'weaknesses' => 'array',
            'metrics' => 'array',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
