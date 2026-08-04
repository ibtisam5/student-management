<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Academic Intelligence Report</title>

    <style>
        @page {
            margin: 34px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #172033;
            font-size: 12px;
            line-height: 1.65;
        }

        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 16px;
            margin-bottom: 22px;
        }

        .header-label {
            color: #2563eb;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        h1 {
            font-size: 25px;
            margin: 5px 0 3px;
        }

        h2 {
            font-size: 17px;
            margin: 24px 0 10px;
        }

        h3 {
            font-size: 14px;
            margin: 0 0 8px;
        }

        .muted {
            color: #667085;
        }

        .metrics {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin: 0 -8px 18px;
        }

        .metrics td {
            width: 25%;
            background: #f3f6fb;
            border: 1px solid #e1e7f0;
            padding: 13px;
            vertical-align: top;
        }

        .metric-label {
            color: #667085;
            font-size: 10px;
        }

        .metric-value {
            font-size: 18px;
            font-weight: bold;
            margin-top: 4px;
        }

        .section {
            border: 1px solid #e1e7f0;
            padding: 15px;
            margin-bottom: 15px;
        }

        .summary {
            border-left: 4px solid #2563eb;
        }

        .strength {
            border-left: 4px solid #198754;
            background: #f2fbf6;
        }

        .weakness {
            border-left: 4px solid #dc3545;
            background: #fff6f6;
        }

        .recommendation {
            border-left: 4px solid #2563eb;
            background: #f3f7ff;
        }

        .prediction {
            border-left: 4px solid #7c3aed;
            background: #f8f5ff;
        }

        ul,
        ol {
            margin: 6px 0 0;
            padding-left: 20px;
        }

        li {
            margin-bottom: 7px;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #dfe5ee;
            padding: 8px;
            text-align: left;
        }

        .details-table th {
            background: #f3f6fb;
            width: 34%;
        }

        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #dfe5ee;
            color: #667085;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>

<body>

@php
    $metrics = $analysis->metrics ?? [];
    $strengths = $analysis->strengths ?? [];
    $weaknesses = $analysis->weaknesses ?? [];

    $recommendations = collect(
        preg_split(
            '/(?<=[.!?])\s+/',
            $analysis->recommendations ?? '',
            -1,
            PREG_SPLIT_NO_EMPTY
        )
    );
@endphp

<div class="header">
    <div class="header-label">
        SMART STUDENT MANAGEMENT SYSTEM
    </div>

    <h1>Academic Intelligence Report</h1>

    <div class="muted">
        Generated {{ now()->format('d F Y, h:i A') }}
    </div>
</div>

<table class="details-table">
    <tr>
        <th>Student</th>
        <td>{{ $analysis->student->full_name }}</td>
    </tr>

    <tr>
        <th>Student Number</th>
        <td>{{ $analysis->student->student_number }}</td>
    </tr>

    <tr>
        <th>Major</th>
        <td>{{ $analysis->student->major }}</td>
    </tr>

    <tr>
        <th>Analysis Type</th>
        <td>{{ $analysis->analysis_type }}</td>
    </tr>
</table>

<h2>Academic Metrics</h2>

<table class="metrics">
    <tr>
        <td>
            <div class="metric-label">AVERAGE GRADE</div>

            <div class="metric-value">
                {{ $metrics['average_grade'] ?? 0 }}%
            </div>
        </td>

        <td>
            <div class="metric-label">ATTENDANCE RATE</div>

            <div class="metric-value">
                {{ $metrics['attendance_rate'] ?? 0 }}%
            </div>
        </td>

        <td>
            <div class="metric-label">RISK LEVEL</div>

            <div class="metric-value">
                {{ $analysis->risk_level ?? 'Unknown' }}
            </div>
        </td>

        <td>
            <div class="metric-label">RISK SCORE</div>

            <div class="metric-value">
                {{ $metrics['risk_score'] ?? 0 }}/100
            </div>
        </td>
    </tr>
</table>

<div class="section summary">
    <h3>Performance Summary</h3>

    <div>
        {{
            $analysis->performance_summary
                ?? $analysis->analysis
        }}
    </div>
</div>

<div class="section strength">
    <h3>Strengths</h3>

    @if(count($strengths))
        <ul>
            @foreach($strengths as $strength)
                <li>{{ $strength }}</li>
            @endforeach
        </ul>
    @else
        <div>No strengths were recorded.</div>
    @endif
</div>

<div class="section weakness">
    <h3>Areas for Improvement</h3>

    @if(count($weaknesses))
        <ul>
            @foreach($weaknesses as $weakness)
                <li>{{ $weakness }}</li>
            @endforeach
        </ul>
    @else
        <div>No significant weaknesses were recorded.</div>
    @endif
</div>

<div class="section recommendation">
    <h3>Recommended Actions</h3>

    @if($recommendations->isNotEmpty())
        <ol>
            @foreach($recommendations as $recommendation)
                <li>{{ $recommendation }}</li>
            @endforeach
        </ol>
    @else
        <div>No recommendations are currently available.</div>
    @endif
</div>

<div class="section prediction">
    <h3>Expected Performance</h3>

    <div>
        {{
            $analysis->prediction
                ?? 'No prediction is currently available.'
        }}
    </div>
</div>

<h2>Data Used</h2>

<table class="details-table">
    <tr>
        <th>Registered Courses</th>
        <td>{{ $metrics['enrollments_count'] ?? 0 }}</td>
    </tr>

    <tr>
        <th>Grade Records</th>
        <td>{{ $metrics['grade_records'] ?? 0 }}</td>
    </tr>

    <tr>
        <th>Attendance Records</th>
        <td>{{ $metrics['attendance_records'] ?? 0 }}</td>
    </tr>

    <tr>
        <th>Present Records</th>
        <td>{{ $metrics['present_count'] ?? 0 }}</td>
    </tr>

    <tr>
        <th>Absent Records</th>
        <td>{{ $metrics['absent_count'] ?? 0 }}</td>
    </tr>

    <tr>
        <th>Late Records</th>
        <td>{{ $metrics['late_count'] ?? 0 }}</td>
    </tr>
</table>

<h2>Analysis Information</h2>

<table class="details-table">
    <tr>
        <th>Provider</th>
        <td>{{ $analysis->provider }}</td>
    </tr>

    <tr>
        <th>Model</th>
        <td>{{ $analysis->model }}</td>
    </tr>

    <tr>
        <th>Last Generated</th>
        <td>
            {{ $analysis->updated_at->format('d F Y, h:i A') }}
        </td>
    </tr>
</table>

<div class="footer">
    This report is generated from the available academic records
    and provides rule-based indicators, not guaranteed outcomes.
</div>

</body>
</html>
