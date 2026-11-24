<?php

namespace App\Services;

use Anthropic\Client;
use App\Models\Student;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Turns the metrics computed by StudentAnalysisService into a written
 * academic report using Claude. The numbers (average, attendance, risk)
 * always come from the rules engine; Claude only interprets them.
 *
 * Returns null when no API key is configured or the request fails, so the
 * caller can keep the rule-based text instead.
 */
class AiNarrativeService
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
You are an academic advisor assistant inside a student management system used by teachers.
You receive a student's computed academic metrics and write a short, professional report for the teacher.

Rules:
- Base every statement only on the metrics provided. Never invent grades, courses or events.
- Keep the risk level exactly as given; explain what drives it.
- Be specific: mention course names and numbers where they matter.
- Recommendations must be practical actions a teacher or student can take this term.
- Write in clear English, supportive and professional in tone. Do not address the student directly.
PROMPT;

    private const SCHEMA = [
        'type' => 'object',
        'properties' => [
            'performance_summary' => [
                'type' => 'string',
                'description' => 'A 3-4 sentence overview of the student\'s academic situation.',
            ],
            'strengths' => [
                'type' => 'array',
                'items' => ['type' => 'string'],
                'description' => '2-3 short strengths, each one sentence.',
            ],
            'weaknesses' => [
                'type' => 'array',
                'items' => ['type' => 'string'],
                'description' => '2-3 short areas for improvement, each one sentence.',
            ],
            'recommendations' => [
                'type' => 'array',
                'items' => ['type' => 'string'],
                'description' => '3 concrete recommended actions, each one sentence ending with a period.',
            ],
            'prediction' => [
                'type' => 'string',
                'description' => '1-2 sentences on the expected outcome if the current pattern continues, framed as an estimate.',
            ],
        ],
        'required' => ['performance_summary', 'strengths', 'weaknesses', 'recommendations', 'prediction'],
        'additionalProperties' => false,
    ];

    public function isEnabled(): bool
    {
        return filled(config('services.anthropic.key'));
    }

    public function model(): string
    {
        return config('services.anthropic.model', 'claude-opus-5-5');
    }

    /**
     * @param  array<string, mixed>  $result  Output of StudentAnalysisService::analyze()
     * @return array<string, mixed>|null
     */
    public function write(Student $student, string $analysisType, array $result): ?array
    {
        if (! $this->isEnabled()) {
            return null;
        }

        $facts = [
            'student' => [
                'major' => $student->major,
                'academic_year' => $student->academic_year,
            ],
            'analysis_type' => $analysisType,
            'average_grade_percent' => $result['average_grade'],
            'attendance_rate_percent' => $result['attendance_rate'],
            'risk_level' => $result['risk_level'],
            'risk_score_out_of_100' => $result['risk_score'] ?? null,
            'metrics' => $result['metrics'] ?? [],
            'rule_engine_prediction' => $result['prediction'] ?? null,
        ];

        try {
            $client = new Client(apiKey: config('services.anthropic.key'));

            $message = $client->messages->create(
                model: $this->model(),
                maxTokens: 16000,
                system: self::SYSTEM_PROMPT,
                outputConfig: [
                    'effort' => 'medium',
                    'format' => ['type' => 'json_schema', 'schema' => self::SCHEMA],
                ],
                messages: [[
                    'role' => 'user',
                    'content' => "Write the academic report for this student.\n\n"
                        . json_encode($facts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                ]],
            );

            if ($message->stopReason !== 'end_turn') {
                Log::warning('AI narrative not used', ['stop_reason' => $message->stopReason]);

                return null;
            }

            $text = '';
            foreach ($message->content as $block) {
                if ($block->type === 'text') {
                    $text .= $block->text;
                }
            }

            $data = json_decode($text, true);

            if (! is_array($data) || empty($data['performance_summary'])) {
                Log::warning('AI narrative returned unexpected output');

                return null;
            }

            return $data;
        } catch (Throwable $e) {
            // Any API or network failure: keep the rule-based report.
            Log::error('AI narrative request failed', ['error' => $e->getMessage()]);

            return null;
        }
    }
}
