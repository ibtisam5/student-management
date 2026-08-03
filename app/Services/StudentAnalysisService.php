<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Student;

class StudentAnalysisService
{
    public function analyze(Student $student): array
    {
        $student->load([
            'enrollments.attendances',
            'enrollments.grades',
        ]);

        $attendanceRate = $this->attendanceRate($student);
        $averageGrade = $this->averageGrade($student);

        $riskLevel = 'Low';
        $analysis = [];
        $recommendations = [];

        if ($attendanceRate < 70) {
            $riskLevel = 'High';
            $analysis[] = 'The student has a low attendance rate.';
            $recommendations[] =
                'Improve attendance and follow up on repeated absences.';
        } elseif ($attendanceRate < 80) {
            $riskLevel = 'Medium';
            $analysis[] =
                'The student attendance rate requires improvement.';
            $recommendations[] =
                'Monitor attendance and reduce absence and lateness.';
        } else {
            $analysis[] =
                'The student maintains a good attendance rate.';
        }

        if ($averageGrade < 60) {
            $riskLevel = 'High';
            $analysis[] =
                'The student academic performance is below the passing level.';
            $recommendations[] =
                'Provide academic support and a focused improvement plan.';
        } elseif ($averageGrade < 70) {
            if ($riskLevel !== 'High') {
                $riskLevel = 'Medium';
            }

            $analysis[] =
                'The student academic performance is acceptable but needs improvement.';
            $recommendations[] =
                'Review weak subjects and attend additional support sessions.';
        } elseif ($averageGrade >= 85) {
            $analysis[] =
                'The student demonstrates strong academic performance.';
            $recommendations[] =
                'Maintain the current performance and consider advanced activities.';
        } else {
            $analysis[] =
                'The student academic performance is good.';
        }

        if ($attendanceRate >= 90 && $averageGrade >= 85) {
            $riskLevel = 'Excellent';

            $analysis[] =
                'The student shows excellent academic commitment and consistency.';

            $recommendations[] =
                'Encourage participation in advanced courses and academic projects.';
        }

        if (empty($recommendations)) {
            $recommendations[] =
                'Continue the current study plan and monitor progress regularly.';
        }

        return [
            'attendance_rate' => $attendanceRate,
            'average_grade' => $averageGrade,
            'risk_level' => $riskLevel,
            'analysis' => implode(' ', array_unique($analysis)),
            'recommendations' =>
                implode(' ', array_unique($recommendations)),
        ];
    }

    private function attendanceRate(Student $student): float
    {
        $attendances = $student->enrollments
            ->flatMap(
                fn ($enrollment) => $enrollment->attendances
            );

        if ($attendances->isEmpty()) {
            return 0;
        }

        $attendedCount = $attendances
            ->whereIn('status', ['Present', 'Late'])
            ->count();

        return round(
            ($attendedCount / $attendances->count()) * 100,
            2
        );
    }

    private function averageGrade(Student $student): float
    {
        $grades = $student->enrollments
            ->flatMap(
                fn ($enrollment) => $enrollment->grades
            )
            ->filter(
                fn (Grade $grade) =>
                    (float) $grade->maximum_score > 0
            );

        if ($grades->isEmpty()) {
            return 0;
        }

        return round(
            $grades->average(
                fn (Grade $grade) => $grade->percentage()
            ),
            2
        );
    }
}
