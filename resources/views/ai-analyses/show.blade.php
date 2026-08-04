<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Academic Intelligence Report</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f4f7fb;
            color: #172033;
        }

        .page-container {
            max-width: 1300px;
            margin: auto;
        }

        .report-card,
        .metric-card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 10px 32px rgba(29, 48, 85, 0.08);
        }

        .metric-card {
            height: 100%;
        }

        .section-line {
            border-left: 4px solid #0d6efd;
            padding-left: 22px;
        }

        .strength-item,
        .weakness-item,
        .recommendation-item {
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 12px;
        }

        .strength-item {
            background: #eaf8f0;
            border-left: 4px solid #198754;
        }

        .weakness-item {
            background: #fff3f3;
            border-left: 4px solid #dc3545;
        }

        .recommendation-item {
            background: #eef5ff;
            border-left: 4px solid #0d6efd;
        }

        .risk-progress {
            height: 12px;
            border-radius: 999px;
        }

        .information-row {
            padding: 13px 0;
            border-bottom: 1px solid #edf0f5;
        }
    </style>
</head>

<body>

@php
    $metrics = $aiAnalysis->metrics ?? [];

    $averageGrade =
        $metrics['average_grade'] ?? 0;

    $attendanceRate =
        $metrics['attendance_rate'] ?? 0;

    $riskScore =
        $metrics['risk_score'] ?? 0;

    $riskLevel =
        $aiAnalysis->risk_level ?? 'Unknown';

    $riskClass = match ($riskLevel) {
        'Low' => 'success',
        'Medium' => 'warning',
        'High' => 'danger',
        'Critical' => 'dark',
        'Insufficient Data' => 'secondary',
        default => 'secondary',
    };

    $recommendations = collect(
        preg_split(
            '/(?<=[.!?])\s+/',
            $aiAnalysis->recommendations ?? '',
            -1,
            PREG_SPLIT_NO_EMPTY
        )
    );
@endphp

<div class="page-container px-3 px-lg-4 py-5">

    <div
        class="d-flex flex-wrap justify-content-between
               align-items-center gap-3 mb-4"
    >
        <div>
            <p class="text-primary fw-semibold mb-1">
                ACADEMIC INTELLIGENCE REPORT
            </p>

            <h1 class="display-6 fw-bold mb-1">
                {{ $aiAnalysis->student->full_name }}
            </h1>

            <p class="text-muted mb-0">
                {{ $aiAnalysis->student->student_number }}
                · {{ $aiAnalysis->student->major }}
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <a
                href="{{ route('ai-analyses.pdf', $aiAnalysis) }}"
                class="btn btn-success"
            >
                Download PDF
            </a>

            <form
                method="POST"
                action="{{ route(
                    'ai-analyses.regenerate',
                    $aiAnalysis
                ) }}"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Regenerate Analysis
                </button>
            </form>

            <a
                href="{{ route('ai-analyses.index') }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>
    </div>

    @if(session('success'))
        <div
            class="alert alert-success
                   alert-dismissible fade show"
        >
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    <div class="row g-3 mb-4">

        <div class="col-lg-3 col-md-6">
            <div class="card metric-card">
                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Average Grade
                    </p>

                    <h2 class="fw-bold text-primary mb-0">
                        {{ $averageGrade }}%
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card metric-card">
                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Attendance Rate
                    </p>

                    <h2 class="fw-bold text-success mb-0">
                        {{ $attendanceRate }}%
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card metric-card">
                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Risk Level
                    </p>

                    <span
                        class="badge fs-5
                               text-bg-{{ $riskClass }}"
                    >
                        {{ $riskLevel }}
                    </span>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card metric-card">
                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Registered Courses
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $metrics['enrollments_count'] ?? 0 }}
                    </h2>

                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card report-card mb-4">
                <div class="card-body p-4 p-md-5">

                    <div class="section-line">

                        <p
                            class="text-primary
                                   fw-semibold mb-2"
                        >
                            PERFORMANCE SUMMARY
                        </p>

                        <h3 class="fw-bold mb-3">
                            Academic Overview
                        </h3>

                        <p class="fs-5 lh-lg mb-0">
                            {{
                                $aiAnalysis->performance_summary
                                ?? $aiAnalysis->analysis
                            }}
                        </p>

                    </div>

                </div>
            </div>

            <div class="row g-4 mb-4">

                <div class="col-md-6">
                    <div class="card report-card h-100">
                        <div class="card-body p-4">

                            <h4 class="fw-bold mb-4">
                                Strengths
                            </h4>

                            @forelse(
                                $aiAnalysis->strengths ?? []
                                as $strength
                            )
                                <div class="strength-item">
                                    ✓ {{ $strength }}
                                </div>
                            @empty
                                <p class="text-muted mb-0">
                                    No strengths were recorded.
                                </p>
                            @endforelse

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card report-card h-100">
                        <div class="card-body p-4">

                            <h4 class="fw-bold mb-4">
                                Areas for Improvement
                            </h4>

                            @forelse(
                                $aiAnalysis->weaknesses ?? []
                                as $weakness
                            )
                                <div class="weakness-item">
                                    • {{ $weakness }}
                                </div>
                            @empty
                                <p class="text-muted mb-0">
                                    No weaknesses were recorded.
                                </p>
                            @endforelse

                        </div>
                    </div>
                </div>

            </div>

            <div class="card report-card mb-4">
                <div class="card-body p-4 p-md-5">

                    <h3 class="fw-bold mb-4">
                        Recommended Actions
                    </h3>

                    @forelse(
                        $recommendations
                        as $recommendation
                    )
                        <div class="recommendation-item">
                            {{ $loop->iteration }}.
                            {{ $recommendation }}
                        </div>
                    @empty
                        <p class="text-muted mb-0">
                            No recommendations available.
                        </p>
                    @endforelse

                </div>
            </div>

            <div class="card report-card">
                <div class="card-body p-4 p-md-5">

                    <p
                        class="text-primary
                               fw-semibold mb-2"
                    >
                        RULE-BASED PREDICTION
                    </p>

                    <h3 class="fw-bold mb-3">
                        Expected Performance
                    </h3>

                    <p class="fs-5 lh-lg mb-0">
                        {{
                            $aiAnalysis->prediction
                            ?? 'No prediction is currently available.'
                        }}
                    </p>

                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="card report-card mb-4">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-4">
                        Risk Assessment
                    </h4>

                    <div
                        class="d-flex justify-content-between
                               mb-2"
                    >
                        <span class="text-muted">
                            Risk score
                        </span>

                        <strong>
                            {{ $riskScore }}/100
                        </strong>
                    </div>

                    <div class="progress risk-progress mb-3">

                        <div
                            class="progress-bar
                                   bg-{{ $riskClass }}"
                            role="progressbar"
                            style="width:
                                {{ min($riskScore, 100) }}%"
                            aria-valuenow="{{ min($riskScore, 100) }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                        ></div>

                    </div>

                    <span
                        class="badge fs-6
                               text-bg-{{ $riskClass }}"
                    >
                        {{ $riskLevel }}
                    </span>

                </div>
            </div>

            <div class="card report-card mb-4">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-3">
                        Data Used
                    </h4>

                    <div class="information-row">
                        <div
                            class="d-flex
                                   justify-content-between"
                        >
                            <span class="text-muted">
                                Registered courses
                            </span>

                            <strong>
                                {{
                                    $metrics['enrollments_count']
                                    ?? 0
                                }}
                            </strong>
                        </div>
                    </div>

                    <div class="information-row">
                        <div
                            class="d-flex
                                   justify-content-between"
                        >
                            <span class="text-muted">
                                Grade records
                            </span>

                            <strong>
                                {{
                                    $metrics['grade_records']
                                    ?? 0
                                }}
                            </strong>
                        </div>
                    </div>

                    <div class="information-row">
                        <div
                            class="d-flex
                                   justify-content-between"
                        >
                            <span class="text-muted">
                                Attendance records
                            </span>

                            <strong>
                                {{
                                    $metrics[
                                        'attendance_records'
                                    ] ?? 0
                                }}
                            </strong>
                        </div>
                    </div>

                    <div class="information-row">
                        <div
                            class="d-flex
                                   justify-content-between"
                        >
                            <span class="text-muted">
                                Present
                            </span>

                            <strong class="text-success">
                                {{
                                    $metrics['present_count']
                                    ?? 0
                                }}
                            </strong>
                        </div>
                    </div>

                    <div class="information-row">
                        <div
                            class="d-flex
                                   justify-content-between"
                        >
                            <span class="text-muted">
                                Absent
                            </span>

                            <strong class="text-danger">
                                {{
                                    $metrics['absent_count']
                                    ?? 0
                                }}
                            </strong>
                        </div>
                    </div>

                    <div class="information-row">
                        <div
                            class="d-flex
                                   justify-content-between"
                        >
                            <span class="text-muted">
                                Late
                            </span>

                            <strong class="text-warning">
                                {{
                                    $metrics['late_count']
                                    ?? 0
                                }}
                            </strong>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card report-card">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-4">
                        Analysis Information
                    </h4>

                    <p class="text-muted mb-1">
                        Analysis Type
                    </p>

                    <p class="fw-semibold">
                        {{ $aiAnalysis->analysis_type }}
                    </p>

                    <p class="text-muted mb-1">
                        Provider
                    </p>

                    <p class="fw-semibold">
                        {{ $aiAnalysis->provider }}
                    </p>

                    <p class="text-muted mb-1">
                        Model
                    </p>

                    <p class="fw-semibold">
                        {{ $aiAnalysis->model }}
                    </p>

                    <p class="text-muted mb-1">
                        Last Generated
                    </p>

                    <p class="fw-semibold mb-0">
                        {{
                            $aiAnalysis->updated_at
                                ->format('d F Y, h:i A')
                        }}
                    </p>

                </div>
            </div>

        </div>

    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>
