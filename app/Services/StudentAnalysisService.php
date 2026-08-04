<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Student;
use Illuminate\Support\Collection;

class StudentAnalysisService
{
    public function analyze(Student $student): array
    {
        $student->load([
            'enrollments.course',
            'enrollments.grades',
            'enrollments.attendances',
        ]);

        $enrollments = $student->enrollments;

        $grades = $enrollments
            ->flatMap(
                fn ($enrollment) => $enrollment->grades
            )
            ->filter(
                fn (Grade $grade): bool =>
                    (float) $grade->maximum_score > 0
            );

        $attendances = $enrollments
            ->flatMap(
                fn ($enrollment) => $enrollment->attendances
            );

        $averageGrade = $this->averageGrade($grades);

        $attendanceMetrics = $this->attendanceMetrics(
            $attendances
        );

        $coursePerformance = $this->coursePerformance(
            $enrollments
        );

        $risk = $this->calculateRisk(
            $averageGrade,
            $attendanceMetrics['rate'],
            $grades->count(),
            $attendances->count()
        );

        $strengths = $this->buildStrengths(
            $averageGrade,
            $attendanceMetrics,
            $coursePerformance
        );

        $weaknesses = $this->buildWeaknesses(
            $averageGrade,
            $attendanceMetrics,
            $coursePerformance
        );

        $recommendations = $this->buildRecommendations(
            $risk['level'],
            $averageGrade,
            $attendanceMetrics,
            $coursePerformance
        );

        $prediction = $this->buildPrediction(
            $averageGrade,
            $attendanceMetrics['rate'],
            $risk['level'],
            $grades->count()
        );

        $performanceSummary = $this->buildSummary(
            $student,
            $averageGrade,
            $attendanceMetrics,
            $risk['level'],
            $coursePerformance
        );

        return [
            'attendance_rate' => $attendanceMetrics['rate'],
            'average_grade' => $averageGrade,
            'risk_level' => $risk['level'],
            'risk_score' => $risk['score'],

            'performance_summary' => $performanceSummary,

            'strengths' => $strengths,
            'weaknesses' => $weaknesses,

            'prediction' => $prediction,

            'analysis' => $performanceSummary,

            'recommendations_array' => $recommendations,

            'recommendations' => implode(
                ' ',
                $recommendations
            ),

            'metrics' => [
                'average_grade' => $averageGrade,

                'attendance_rate' =>
                    $attendanceMetrics['rate'],

                'present_count' =>
                    $attendanceMetrics['present'],

                'absent_count' =>
                    $attendanceMetrics['absent'],

                'late_count' =>
                    $attendanceMetrics['late'],

                'excused_count' =>
                    $attendanceMetrics['excused'],

                'grade_records' => $grades->count(),

                'attendance_records' =>
                    $attendances->count(),

                'enrollments_count' =>
                    $enrollments->count(),

                'risk_score' => $risk['score'],

                'strongest_course' =>
                    $coursePerformance['strongest'],

                'weakest_course' =>
                    $coursePerformance['weakest'],
            ],
        ];
    }

    private function averageGrade(Collection $grades): float
    {
        if ($grades->isEmpty()) {
            return 0;
        }

        return round(
            $grades->average(
                fn (Grade $grade): float =>
                    $grade->percentage()
            ),
            1
        );
    }

    private function attendanceMetrics(
        Collection $attendances
    ): array {
        if ($attendances->isEmpty()) {
            return [
                'rate' => 0,
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0,
            ];
        }

        $present = $attendances
            ->where('status', 'Present')
            ->count();

        $late = $attendances
            ->where('status', 'Late')
            ->count();

        $absent = $attendances
            ->where('status', 'Absent')
            ->count();

        $excused = $attendances
            ->where('status', 'Excused')
            ->count();

        $attended = $present + $late;

        $rate = round(
            ($attended / $attendances->count()) * 100,
            1
        );

        return [
            'rate' => $rate,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
        ];
    }

    private function coursePerformance(
        Collection $enrollments
    ): array {
        $courses = $enrollments
            ->map(function ($enrollment): ?array {
                $validGrades = $enrollment->grades
                    ->filter(
                        fn (Grade $grade): bool =>
                            (float) $grade->maximum_score > 0
                    );

                if ($validGrades->isEmpty()) {
                    return null;
                }

                $average = round(
                    $validGrades->average(
                        fn (Grade $grade): float =>
                            $grade->percentage()
                    ),
                    1
                );

                return [
                    'course_code' =>
                        $enrollment->course?->course_code
                        ?? 'Unknown',

                    'course_name' =>
                        $enrollment->course?->course_name
                        ?? 'Unknown Course',

                    'average' => $average,
                ];
            })
            ->filter()
            ->values();

        if ($courses->isEmpty()) {
            return [
                'strongest' => null,
                'weakest' => null,
                'all' => [],
            ];
        }

        return [
            'strongest' => $courses
                ->sortByDesc('average')
                ->first(),

            'weakest' => $courses
                ->sortBy('average')
                ->first(),

            'all' => $courses->all(),
        ];
    }

    private function calculateRisk(
        float $averageGrade,
        float $attendanceRate,
        int $gradeRecords,
        int $attendanceRecords
    ): array {
        if ($gradeRecords === 0 && $attendanceRecords === 0) {
            return [
                'level' => 'Insufficient Data',
                'score' => 0,
            ];
        }

        $score = 0;

        if ($gradeRecords === 0) {
            $score += 20;
        } elseif ($averageGrade < 50) {
            $score += 50;
        } elseif ($averageGrade < 60) {
            $score += 40;
        } elseif ($averageGrade < 70) {
            $score += 25;
        } elseif ($averageGrade < 80) {
            $score += 10;
        }

        if ($attendanceRecords === 0) {
            $score += 15;
        } elseif ($attendanceRate < 60) {
            $score += 45;
        } elseif ($attendanceRate < 70) {
            $score += 35;
        } elseif ($attendanceRate < 80) {
            $score += 20;
        } elseif ($attendanceRate < 90) {
            $score += 5;
        }

        $score = min($score, 100);

        $level = match (true) {
            $score >= 70 => 'Critical',
            $score >= 45 => 'High',
            $score >= 25 => 'Medium',
            default => 'Low',
        };

        return [
            'level' => $level,
            'score' => $score,
        ];
    }

    private function buildStrengths(
        float $averageGrade,
        array $attendance,
        array $courses
    ): array {
        $strengths = [];

        if ($averageGrade >= 85) {
            $strengths[] =
                'Strong overall academic performance.';
        } elseif ($averageGrade >= 75) {
            $strengths[] =
                'Good academic performance across assessments.';
        }

        if ($attendance['rate'] >= 90) {
            $strengths[] =
                'Excellent attendance and academic commitment.';
        } elseif ($attendance['rate'] >= 80) {
            $strengths[] =
                'Good attendance consistency.';
        }

        if (
            $courses['strongest']
            && $courses['strongest']['average'] >= 80
        ) {
            $strengths[] = sprintf(
                'Strong performance in %s (%s) with an average of %.1f%%.',
                $courses['strongest']['course_name'],
                $courses['strongest']['course_code'],
                $courses['strongest']['average']
            );
        }

        if (empty($strengths)) {
            $strengths[] =
                'The available data shows potential for improvement.';
        }

        return array_values(array_unique($strengths));
    }

    private function buildWeaknesses(
        float $averageGrade,
        array $attendance,
        array $courses
    ): array {
        $weaknesses = [];

        if ($averageGrade < 60) {
            $weaknesses[] =
                'Overall grade is below the passing threshold.';
        } elseif ($averageGrade < 70) {
            $weaknesses[] =
                'Overall grade requires further improvement.';
        }

        if ($attendance['rate'] < 70) {
            $weaknesses[] =
                'Attendance rate is at a high-risk level.';
        } elseif ($attendance['rate'] < 80) {
            $weaknesses[] =
                'Attendance consistency requires improvement.';
        }

        if ($attendance['absent'] > 0) {
            $weaknesses[] = sprintf(
                '%d absence records were identified.',
                $attendance['absent']
            );
        }

        if ($attendance['late'] >= 3) {
            $weaknesses[] = sprintf(
                '%d late attendance records may affect learning consistency.',
                $attendance['late']
            );
        }

        if (
            $courses['weakest']
            && $courses['weakest']['average'] < 70
        ) {
            $weaknesses[] = sprintf(
                'Performance in %s (%s) requires attention; current average is %.1f%%.',
                $courses['weakest']['course_name'],
                $courses['weakest']['course_code'],
                $courses['weakest']['average']
            );
        }

        if (empty($weaknesses)) {
            $weaknesses[] =
                'No significant academic weaknesses were detected.';
        }

        return array_values(array_unique($weaknesses));
    }

    private function buildRecommendations(
        string $riskLevel,
        float $averageGrade,
        array $attendance,
        array $courses
    ): array {
        $recommendations = [];

        if ($riskLevel === 'Insufficient Data') {
            return [
                'Add grade and attendance records before making an academic decision.',
            ];
        }

        if (in_array($riskLevel, ['Critical', 'High'], true)) {
            $recommendations[] =
                'Schedule an academic support meeting as soon as possible.';

            $recommendations[] =
                'Create a weekly progress plan and review it regularly.';
        }

        if ($averageGrade < 60) {
            $recommendations[] =
                'Provide focused tutoring and assessment revision support.';
        } elseif ($averageGrade < 70) {
            $recommendations[] =
                'Increase weekly revision time and practice weak assessment areas.';
        } elseif ($averageGrade >= 85) {
            $recommendations[] =
                'Consider advanced academic activities or enrichment projects.';
        }

        if ($attendance['rate'] < 75) {
            $recommendations[] =
                'Improve attendance immediately and investigate repeated absences.';
        } elseif ($attendance['rate'] < 90) {
            $recommendations[] =
                'Aim to maintain attendance above 90%.';
        }

        if (
            $courses['weakest']
            && $courses['weakest']['average'] < 70
        ) {
            $recommendations[] = sprintf(
                'Prioritize additional study sessions for %s.',
                $courses['weakest']['course_name']
            );
        }

        if ($attendance['late'] >= 3) {
            $recommendations[] =
                'Reduce late arrivals by improving schedule planning.';
        }

        if (empty($recommendations)) {
            $recommendations[] =
                'Continue the current study plan and monitor progress regularly.';
        }

        return array_values(
            array_unique($recommendations)
        );
    }

    private function buildPrediction(
        float $averageGrade,
        float $attendanceRate,
        string $riskLevel,
        int $gradeRecords
    ): string {
        if (
            $gradeRecords === 0
            || $riskLevel === 'Insufficient Data'
        ) {
            return 'A performance estimate cannot be generated until sufficient grade data is available.';
        }

        $adjustment = match (true) {
            $attendanceRate >= 90 => 3,
            $attendanceRate >= 80 => 1,
            $attendanceRate >= 70 => -2,
            default => -5,
        };

        $predictedAverage = max(
            0,
            min(100, $averageGrade + $adjustment)
        );

        $lower = max(
            0,
            round($predictedAverage - 3, 1)
        );

        $upper = min(
            100,
            round($predictedAverage + 3, 1)
        );

        return sprintf(
            'Rule-based estimate: if the current pattern continues, the expected final average may range between %.1f%% and %.1f%%. This is an academic indicator, not a guaranteed result.',
            $lower,
            $upper
        );
    }

    private function buildSummary(
        Student $student,
        float $averageGrade,
        array $attendance,
        string $riskLevel,
        array $courses
    ): string {
        if ($riskLevel === 'Insufficient Data') {
            return sprintf(
                '%s does not currently have enough grade or attendance data for a complete academic evaluation.',
                $student->full_name
            );
        }

        $gradeDescription = match (true) {
            $averageGrade >= 85 =>
                'strong academic performance',

            $averageGrade >= 75 =>
                'good academic performance',

            $averageGrade >= 60 =>
                'moderate academic performance',

            default =>
                'academic performance below the passing level',
        };

        $attendanceDescription = match (true) {
            $attendance['rate'] >= 90 =>
                'excellent attendance consistency',

            $attendance['rate'] >= 80 =>
                'good attendance consistency',

            $attendance['rate'] >= 70 =>
                'attendance that requires improvement',

            default =>
                'a high-risk attendance pattern',
        };

        $summary = sprintf(
            '%s demonstrates %s with an overall average of %.1f%% and %s at %.1f%%. The current academic risk level is %s.',
            $student->full_name,
            $gradeDescription,
            $averageGrade,
            $attendanceDescription,
            $attendance['rate'],
            $riskLevel
        );

        if ($courses['strongest']) {
            $summary .= sprintf(
                ' The strongest recorded course is %s with an average of %.1f%%.',
                $courses['strongest']['course_name'],
                $courses['strongest']['average']
            );
        }

        if (
            $courses['weakest']
            && $courses['weakest']['average'] < 70
        ) {
            $summary .= sprintf(
                ' Additional attention is recommended for %s.',
                $courses['weakest']['course_name']
            );
        }

        return $summary;
    }
}
