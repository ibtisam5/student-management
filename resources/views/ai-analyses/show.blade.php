<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>AI Analysis Report</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f4f7fb;
        }

        .report-card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 10px 32px rgba(29, 48, 85, 0.08);
        }

        .metric-card {
            border: 0;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 8px 24px rgba(29, 48, 85, 0.07);
        }

        .report-section {
            border-left: 4px solid #0d6efd;
        }
    </style>
</head>

<body>

@php
    $riskLevel = $currentResult['risk_level'];

    $riskClass = match ($riskLevel) {
        'Excellent' => 'success',
        'Low' => 'primary',
        'Medium' => 'warning',
        'High' => 'danger',
        default => 'secondary',
    };
@endphp

<div class="container py-5" style="max-width: 1200px;">

    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">

        <div>
            <p class="text-primary fw-semibold mb-1">
                AI STUDENT REPORT
            </p>

            <h1 class="fw-bold mb-1">
                {{ $aiAnalysis->student->full_name }}
            </h1>

            <p class="text-muted mb-0">
                {{ $aiAnalysis->student->student_number }}
                · {{ $aiAnalysis->student->major }}
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

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
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card metric-card h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Average Grade
                    </p>

                    <h2 class="fw-bold text-primary mb-0">
                        {{ $currentResult['average_grade'] }}%
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Attendance Rate
                    </p>

                    <h2 class="fw-bold text-success mb-0">
                        {{ $currentResult['attendance_rate'] }}%
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Risk Level
                    </p>

                    <span
                        class="badge fs-5 text-bg-{{ $riskClass }}"
                    >
                        {{ $riskLevel }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card metric-card h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Registered Courses
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $enrollmentsCount }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card report-card mb-4">
                <div class="card-body p-4 p-md-5">

                    <div class="report-section ps-4">

                        <p class="text-primary fw-semibold mb-2">
                            INTELLIGENT ANALYSIS
                        </p>

                        <h3 class="fw-bold mb-3">
                            Performance Summary
                        </h3>

                        <p class="fs-5 mb-0">
                            {{ $aiAnalysis->analysis }}
                        </p>

                    </div>

                </div>
            </div>

            <div class="card report-card">
                <div class="card-body p-4 p-md-5">

                    <div class="report-section ps-4">

                        <p class="text-success fw-semibold mb-2">
                            RECOMMENDED ACTIONS
                        </p>

                        <h3 class="fw-bold mb-3">
                            Recommendations
                        </h3>

                        <p class="fs-5 mb-0">
                            {{ $aiAnalysis->recommendations }}
                        </p>

                    </div>

                </div>
            </div>

        </div>

        <div class="col-lg-4">

            <div class="card report-card mb-4">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        Analysis Information
                    </h5>

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
                        Generated
                    </p>

                    <p class="fw-semibold mb-0">
                        {{ $aiAnalysis->updated_at
                            ->format('d F Y, h:i A') }}
                    </p>

                </div>
            </div>

            <div class="card report-card">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        Data Used
                    </h5>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">
                            Enrollments
                        </span>

                        <strong>
                            {{ $enrollmentsCount }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">
                            Grade Records
                        </span>

                        <strong>
                            {{ $gradesCount }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">
                            Attendance Records
                        </span>

                        <strong>
                            {{ $attendanceRecordsCount }}
                        </strong>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>
