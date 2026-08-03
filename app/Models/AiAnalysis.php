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
    'provider',
    'model',
];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
