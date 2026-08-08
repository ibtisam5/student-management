<x-app-layout>
    @php

         $totalStudents = \App\Models\Student::count();
        $activeStudents = \App\Models\Student::where('status', 'Active')->count();

        $totalCourses = \App\Models\Course::count();
        $activeCourses = \App\Models\Course::where('is_active', true)->count();

        $totalEnrollments = \App\Models\Enrollment::count();
        $totalAttendances = \App\Models\Attendance::count();
        $totalGrades = \App\Models\Grade::count();
        $totalAnalyses = \App\Models\AiAnalysis::count();

        $presentCount = \App\Models\Attendance::where('status', 'Present')->count();
        $absentCount = \App\Models\Attendance::where('status', 'Absent')->count();
        $lateCount = \App\Models\Attendance::where('status', 'Late')->count();
        $excusedCount = \App\Models\Attendance::where('status', 'Excused')->count();
    $attendanceRate = $totalAttendances > 0
        ? round(
            (($presentCount + $lateCount) / $totalAttendances) * 100,
            1
        )
        : 0;

    $recentStudents = \App\Models\Student::latest()
        ->take(5)
        ->get();

    $recentAnalyses = \App\Models\AiAnalysis::with('student')
        ->latest()
        ->take(5)
        ->get();

    $studentsByMajor = \App\Models\Student::query()
        ->selectRaw('major, COUNT(*) as total')
        ->groupBy('major')
        ->orderByDesc('total')
        ->take(6)
        ->get();

    $majorLabels = $studentsByMajor
        ->pluck('major')
        ->values();

    $majorValues = $studentsByMajor
        ->pluck('total')
        ->values();

    $riskCounts = [
        'Low' => \App\Models\AiAnalysis::where(
            'risk_level',
            'Low'
        )->count(),

        'Medium' => \App\Models\AiAnalysis::where(
            'risk_level',
            'Medium'
        )->count(),

        'High' => \App\Models\AiAnalysis::where(
            'risk_level',
            'High'
        )->count(),

        'Critical' => \App\Models\AiAnalysis::where(
            'risk_level',
            'Critical'
        )->count(),
    ];
@endphp

    <style>
        .dashboard-page {
            min-height: calc(100vh - 64px);
            background:
                radial-gradient(
                    circle at top right,
                    rgba(37, 99, 235, 0.08),
                    transparent 28%
                ),
                #f4f7fb;
        }

        .dashboard-container {
            width: min(1450px, calc(100% - 32px));
            margin: 0 auto;
            padding: 36px 0 52px;
        }

        .dashboard-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 24px;
            margin-bottom: 26px;
        }

        .eyebrow {
            margin: 0 0 8px;
            color: #2563eb;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .dashboard-title {
            margin: 0;
            color: #172033;
            font-size: clamp(34px, 4vw, 54px);
            font-weight: 900;
            line-height: 1.08;
        }

        .dashboard-subtitle {
            margin: 10px 0 0;
            color: #667085;
            font-size: 17px;
        }

        .updated-card {
            min-width: 190px;
            padding: 15px 18px;
            border: 1px solid #e4eaf2;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: 0 12px 35px rgba(31, 45, 74, 0.06);
            text-align: right;
        }

        .updated-card span {
            display: block;
            color: #667085;
            font-size: 13px;
        }

        .updated-card strong {
            display: block;
            margin-top: 4px;
            color: #172033;
            font-size: 15px;
        }

        .navigation-card {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 26px;
            padding: 14px;
            border: 1px solid #e8edf4;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 15px 45px rgba(31, 45, 74, 0.07);
        }

        .navigation-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 10px 18px;
            border: 1px solid #cfd7e4;
            border-radius: 13px;
            color: #536174;
            font-weight: 700;
            text-decoration: none;
            transition: 0.2s ease;
        }

        .navigation-link:hover {
            border-color: #2563eb;
            color: #2563eb;
            transform: translateY(-1px);
        }

        .navigation-link.active {
            border-color: #2563eb;
            background: #2563eb;
            color: #ffffff;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            min-height: 178px;
            padding: 25px;
            border: 1px solid #e8edf4;
            border-radius: 22px;
            background: #ffffff;
            box-shadow: 0 16px 42px rgba(31, 45, 74, 0.07);
        }

        .stat-card::after {
            content: "";
            position: absolute;
            right: -28px;
            bottom: -38px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--card-accent);
            opacity: 0.08;
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .stat-label {
            margin: 0;
            color: #667085;
            font-size: 15px;
            font-weight: 700;
        }

        .stat-value {
            margin: 14px 0 4px;
            color: #172033;
            font-size: 38px;
            font-weight: 900;
            line-height: 1;
        }

        .stat-note {
            margin: 0;
            color: #667085;
            font-size: 14px;
        }

        .stat-note.success {
            color: #168651;
        }

        .stat-note.primary {
            color: #2563eb;
        }

        .stat-icon {
            display: grid;
            width: 58px;
            height: 58px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 18px;
            background: var(--icon-background);
            font-size: 27px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 22px;
            margin-bottom: 24px;
        }

        .content-card {
            min-width: 0;
            padding: 26px;
            border: 1px solid #e8edf4;
            border-radius: 22px;
            background: #ffffff;
            box-shadow: 0 16px 42px rgba(31, 45, 74, 0.07);
        }

        .card-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 20px;
        }

        .card-title {
            margin: 0;
            color: #172033;
            font-size: 22px;
            font-weight: 900;
        }

        .card-description {
            margin: 5px 0 0;
            color: #667085;
            font-size: 14px;
        }

        .card-link {
            flex-shrink: 0;
            color: #2563eb;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
        }

        .chart-wrapper {
            position: relative;
            height: 350px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
        }

        .dashboard-table th {
            padding: 13px 12px;
            border-bottom: 1px solid #e5eaf1;
            color: #667085;
            font-size: 12px;
            font-weight: 800;
            text-align: left;
            text-transform: uppercase;
        }

        .dashboard-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #edf1f5;
            color: #273449;
            font-size: 14px;
            vertical-align: middle;
        }

        .dashboard-table tr:last-child td {
            border-bottom: 0;
        }

        .student-name {
            color: #172033;
            font-weight: 800;
        }

        .student-number {
            display: block;
            margin-top: 3px;
            color: #7a8699;
            font-size: 12px;
        }

        .status-badge,
        .risk-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 74px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .status-active,
        .risk-low {
            background: #e9f8ef;
            color: #168651;
        }

        .status-inactive {
            background: #eef1f5;
            color: #657184;
        }

        .risk-medium {
            background: #fff5d8;
            color: #b87800;
        }

        .risk-high {
            background: #ffe9e9;
            color: #d92d20;
        }

        .risk-critical {
            background: #2a2f3a;
            color: #ffffff;
        }

        .empty-state {
            padding: 44px 16px;
            color: #7a8699;
            text-align: center;
        }

        .empty-icon {
            display: block;
            margin-bottom: 10px;
            font-size: 34px;
        }

        @media (max-width: 1150px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 850px) {
            .dashboard-heading,
            .card-header-row {
                flex-direction: column;
            }

            .updated-card {
                width: 100%;
                text-align: left;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 620px) {
            .dashboard-container {
                width: min(100% - 20px, 1450px);
                padding-top: 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card,
            .content-card {
                border-radius: 18px;
            }

            .navigation-link {
                flex: 1 1 calc(50% - 10px);
                padding-inline: 10px;
                font-size: 13px;
            }

            .chart-wrapper {
                height: 300px;
            }
        }
    </style>

    <div class="dashboard-page">
        <main class="dashboard-container">

            <section class="dashboard-heading">
                <div>
                    <p class="eyebrow">
                        Smart Student Management System
                    </p>

                    <h1 class="dashboard-title">
                        Academic Dashboard
                    </h1>

                    <p class="dashboard-subtitle">
                        Monitor student records, attendance, grades,
                        enrolments and academic intelligence.
                    </p>
                </div>

                <div class="updated-card">
                    <span>Dashboard updated</span>

                    <strong>
                        {{ now()->format('d F Y') }}
                    </strong>
                </div>
            </section>

            <nav class="navigation-card">
                <a
                    href="{{ route('dashboard') }}"
                    class="navigation-link active"
                >
                    Dashboard
                </a>

                @if(Route::has('students.index'))
                    <a
                        href="{{ route('students.index') }}"
                        class="navigation-link"
                    >
                        Students
                    </a>
                @endif

                @if(Route::has('courses.index'))
                    <a
                        href="{{ route('courses.index') }}"
                        class="navigation-link"
                    >
                        Courses
                    </a>
                @endif

                @if(Route::has('enrollments.index'))
                    <a
                        href="{{ route('enrollments.index') }}"
                        class="navigation-link"
                    >
                        Enrollments
                    </a>
                @endif

                @if(Route::has('attendances.index'))
                    <a
                        href="{{ route('attendances.index') }}"
                        class="navigation-link"
                    >
                        Attendance
                    </a>
                @endif

                @if(Route::has('grades.index'))
                    <a
                        href="{{ route('grades.index') }}"
                        class="navigation-link"
                    >
                        Grades
                    </a>
                @endif

                @if(Route::has('ai-analyses.index'))
                    <a
                        href="{{ route('ai-analyses.index') }}"
                        class="navigation-link"
                    >
                        AI Analysis
                    </a>
                @endif
            </nav>

            <section class="stats-grid">
                <article
                    class="stat-card"
                    style="
                        --card-accent: #2563eb;
                        --icon-background: #dbe8ff;
                    "
                >
                    <div class="stat-top">
                        <div>
                            <p class="stat-label">Total Students</p>

                            <p class="stat-value">
                                {{ number_format($totalStudents) }}
                            </p>

                            <p class="stat-note success">
                                {{ number_format($activeStudents) }}
                                active students
                            </p>
                        </div>

                        <div class="stat-icon">🎓</div>
                    </div>
                </article>

                <article
                    class="stat-card"
                    style="
                        --card-accent: #16a36a;
                        --icon-background: #dcf4e9;
                    "
                >
                    <div class="stat-top">
                        <div>
                            <p class="stat-label">Courses</p>

                            <p class="stat-value">
                                {{ number_format($totalCourses) }}
                            </p>

                            <p class="stat-note success">
                                {{ number_format($activeCourses) }}
                                active courses
                            </p>
                        </div>

                        <div class="stat-icon">📚</div>
                    </div>
                </article>

                <article
                    class="stat-card"
                    style="
                        --card-accent: #7c3aed;
                        --icon-background: #eee5ff;
                    "
                >
                    <div class="stat-top">
                        <div>
                            <p class="stat-label">Enrollments</p>

                            <p class="stat-value">
                                {{ number_format($totalEnrollments) }}
                            </p>

                            <p class="stat-note">
                                Course registrations
                            </p>
                        </div>

                        <div class="stat-icon">📝</div>
                    </div>
                </article>

                <article
                    class="stat-card"
                    style="
                        --card-accent: #10b981;
                        --icon-background: #dcf8ef;
                    "
                >
                    <div class="stat-top">
                        <div>
                            <p class="stat-label">
                                Attendance Records
                            </p>

                            <p class="stat-value">
                                {{ number_format($totalAttendances) }}
                            </p>

                            <p class="stat-note primary">
                                {{ $attendanceRate }}%
                                attendance rate
                            </p>
                        </div>

                        <div class="stat-icon">✅</div>
                    </div>
                </article>

                <article
                    class="stat-card"
                    style="
                        --card-accent: #f04438;
                        --icon-background: #ffe5e5;
                    "
                >
                    <div class="stat-top">
                        <div>
                            <p class="stat-label">Total Grades</p>

                            <p class="stat-value">
                                {{ number_format($totalGrades) }}
                            </p>

                            <p class="stat-note">
                                Assessment records
                            </p>
                        </div>

                        <div class="stat-icon">📋</div>
                    </div>
                </article>

                <article
                    class="stat-card"
                    style="
                        --card-accent: #f59e0b;
                        --icon-background: #fff0cb;
                    "
                >
                    <div class="stat-top">
                        <div>
                            <p class="stat-label">Present Records</p>

                            <p class="stat-value">
                                {{ number_format($presentCount) }}
                            </p>

                            <p class="stat-note success">
                                Students marked present
                            </p>
                        </div>

                        <div class="stat-icon">🙋</div>
                    </div>
                </article>

                <article
                    class="stat-card"
                    style="
                        --card-accent: #06b6d4;
                        --icon-background: #d9f6fb;
                    "
                >
                    <div class="stat-top">
                        <div>
                            <p class="stat-label">AI Analyses</p>

                            <p class="stat-value">
                                {{ number_format($totalAnalyses) }}
                            </p>

                            <p class="stat-note primary">
                                Intelligent student reports
                            </p>
                        </div>

                        <div class="stat-icon">🤖</div>
                    </div>
                </article>

                <article
                    class="stat-card"
                    style="
                        --card-accent: #ef4444;
                        --icon-background: #ffe5e5;
                    "
                >
                    <div class="stat-top">
                        <div>
                            <p class="stat-label">Absent Records</p>

                            <p class="stat-value">
                                {{ number_format($absentCount) }}
                            </p>

                            <p class="stat-note">
                                Recorded absences
                            </p>
                        </div>

                        <div class="stat-icon">⚠️</div>
                    </div>
                </article>
            </section>

            <section class="content-grid">
                <article class="content-card">
                    <div class="card-header-row">
                        <div>
                            <h2 class="card-title">
                                Attendance Distribution
                            </h2>

                            <p class="card-description">
                                Distribution of attendance statuses
                            </p>
                        </div>

                        @if(Route::has('attendances.index'))
                            <a
                                href="{{ route('attendances.index') }}"
                                class="card-link"
                            >
                                View Attendance
                            </a>
                        @endif
                    </div>

                    <div class="chart-wrapper">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </article>

                <article class="content-card">
                    <div class="card-header-row">
                        <div>
                            <h2 class="card-title">
                                Students by Major
                            </h2>

                            <p class="card-description">
                                Student distribution across programs
                            </p>
                        </div>

                        @if(Route::has('students.index'))
                            <a
                                href="{{ route('students.index') }}"
                                class="card-link"
                            >
                                View Students
                            </a>
                        @endif
                    </div>

                    <div class="chart-wrapper">
                        <canvas id="majorsChart"></canvas>
                    </div>
                </article>
            </section>

            <section class="content-grid">
                <article class="content-card">
                    <div class="card-header-row">
                        <div>
                            <h2 class="card-title">
                                Recent Students
                            </h2>

                            <p class="card-description">
                                Latest students registered in the system
                            </p>
                        </div>

                        @if(Route::has('students.index'))
                            <a
                                href="{{ route('students.index') }}"
                                class="card-link"
                            >
                                Show All
                            </a>
                        @endif
                    </div>

                    <div class="table-wrapper">
                        @if($recentStudents->isNotEmpty())
                            <table class="dashboard-table">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Major</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($recentStudents as $student)
                                        <tr>
                                            <td>
                                                <span class="student-name">
                                                    {{ $student->full_name }}
                                                </span>

                                                <span class="student-number">
                                                    {{
                                                        $student
                                                            ->student_number
                                                    }}
                                                </span>
                                            </td>

                                            <td>{{ $student->major }}</td>

                                            <td>
                                                {{
                                                    $student->academic_year
                                                }}
                                            </td>

                                            <td>
                                                <span
                                                    class="status-badge
                                                        {{
                                                            $student->status
                                                            === 'Active'
                                                                ? 'status-active'
                                                                : 'status-inactive'
                                                        }}"
                                                >
                                                    {{ $student->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <span class="empty-icon">🎓</span>
                                No students have been added yet.
                            </div>
                        @endif
                    </div>
                </article>

                <article class="content-card">
                    <div class="card-header-row">
                        <div>
                            <h2 class="card-title">
                                Recent AI Analyses
                            </h2>

                            <p class="card-description">
                                Latest intelligent academic reports
                            </p>
                        </div>

                        @if(Route::has('ai-analyses.index'))
                            <a
                                href="{{ route('ai-analyses.index') }}"
                                class="card-link"
                            >
                                Show All
                            </a>
                        @endif
                    </div>

                    <div class="table-wrapper">
                        @if($recentAnalyses->isNotEmpty())
                            <table class="dashboard-table">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Type</th>
                                        <th>Risk</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($recentAnalyses as $analysis)
                                        @php
                                            $riskClass = match(
                                                $analysis->risk_level
                                            ) {
                                                'Low' => 'risk-low',
                                                'Medium' => 'risk-medium',
                                                'High' => 'risk-high',
                                                'Critical' =>
                                                    'risk-critical',
                                                default =>
                                                    'status-inactive',
                                            };
                                        @endphp

                                        <tr>
                                            <td>
                                                <span class="student-name">
                                                    {{
                                                        $analysis->student
                                                            ?->full_name
                                                        ?? 'Unknown Student'
                                                    }}
                                                </span>

                                                <span class="student-number">
                                                    {{
                                                        $analysis->student
                                                            ?->student_number
                                                        ?? '—'
                                                    }}
                                                </span>
                                            </td>

                                            <td>
                                                {{
                                                    $analysis
                                                        ->analysis_type
                                                }}
                                            </td>

                                            <td>
                                                <span
                                                    class="risk-badge
                                                           {{ $riskClass }}"
                                                >
                                                    {{
                                                        $analysis
                                                            ->risk_level
                                                        ?? 'Unknown'
                                                    }}
                                                </span>
                                            </td>

                                            <td>
                                                {{
                                                    $analysis->created_at
                                                        ->format('d M Y')
                                                }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <span class="empty-icon">🤖</span>
                                No AI analyses have been generated yet.
                            </div>
                        @endif
                    </div>
                </article>
            </section>

            <section class="content-grid">
                <article class="content-card">
                    <div class="card-header-row">
                        <div>
                            <h2 class="card-title">
                                AI Risk Overview
                            </h2>

                            <p class="card-description">
                                Students grouped by their latest
                                academic risk levels
                            </p>
                        </div>
                    </div>

                    <div class="chart-wrapper">
                        <canvas id="riskChart"></canvas>
                    </div>
                </article>

                <article class="content-card">
                    <div class="card-header-row">
                        <div>
                            <h2 class="card-title">
                                Quick Actions
                            </h2>

                            <p class="card-description">
                                Open the most frequently used pages
                            </p>
                        </div>
                    </div>

                    <div
                        style="
                            display: grid;
                            grid-template-columns:
                                repeat(2, minmax(0, 1fr));
                            gap: 14px;
                        "
                    >
                        @if(Route::has('students.create'))
                            <a
                                href="{{ route('students.create') }}"
                                class="navigation-link"
                            >
                                + Add Student
                            </a>
                        @endif

                        @if(Route::has('courses.create'))
                            <a
                                href="{{ route('courses.create') }}"
                                class="navigation-link"
                            >
                                + Add Course
                            </a>
                        @endif

                        @if(Route::has('enrollments.create'))
                            <a
                                href="{{ route('enrollments.create') }}"
                                class="navigation-link"
                            >
                                + Add Enrollment
                            </a>
                        @endif

                        @if(Route::has('attendances.create'))
                            <a
                                href="{{ route('attendances.create') }}"
                                class="navigation-link"
                            >
                                + Add Attendance
                            </a>
                        @endif

                        @if(Route::has('grades.create'))
                            <a
                                href="{{ route('grades.create') }}"
                                class="navigation-link"
                            >
                                + Add Grade
                            </a>
                        @endif

                        @if(Route::has('ai-analyses.create'))
                            <a
                                href="{{ route('ai-analyses.create') }}"
                                class="navigation-link"
                            >
                                + New AI Analysis
                            </a>
                        @endif
                    </div>
                </article>
            </section>

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const chartTextColor = '#667085';
        const chartGridColor = 'rgba(152, 162, 179, 0.20)';

        new Chart(
            document.getElementById('attendanceChart'),
            {
                type: 'doughnut',

                data: {
                    labels: [
                        'Present',
                        'Absent',
                        'Late',
                        'Excused'
                    ],

                    datasets: [{
                        data: [
                            {{ $presentCount }},
                            {{ $absentCount }},
                            {{ $lateCount }},
                            {{ $excusedCount }}
                        ],

                        backgroundColor: [
                            '#198754',
                            '#dc3545',
                            '#f4b400',
                            '#15b8d6'
                        ],

                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',

                    plugins: {
                        legend: {
                            position: 'bottom',

                            labels: {
                                color: chartTextColor,
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            }
        );

        new Chart(
            document.getElementById('majorsChart'),
            {
                type: 'bar',

                data: {
                    labels: @json($majorLabels),

                    datasets: [{
                        label: 'Students',
                        data: @json($majorValues),
                        backgroundColor: '#198754',
                        borderRadius: 10,
                        borderSkipped: false,
                        barThickness: 34
                    }]
                },

                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,

                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: chartTextColor,
                                precision: 0
                            },
                            grid: {
                                color: chartGridColor
                            }
                        },

                        y: {
                            ticks: {
                                color: chartTextColor
                            },
                            grid: {
                                display: false
                            }
                        }
                    },

                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            }
        );

        new Chart(
            document.getElementById('riskChart'),
            {
                type: 'bar',

                data: {
                    labels: [
                        'Low',
                        'Medium',
                        'High',
                        'Critical'
                    ],

                    datasets: [{
                        label: 'Analyses',
                        data: [
                            {{ $riskCounts['Low'] }},
                            {{ $riskCounts['Medium'] }},
                            {{ $riskCounts['High'] }},
                            {{ $riskCounts['Critical'] }}
                        ],

                        backgroundColor: [
                            '#198754',
                            '#f4b400',
                            '#dc3545',
                            '#222831'
                        ],

                        borderRadius: 10,
                        borderSkipped: false
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    scales: {
                        x: {
                            ticks: {
                                color: chartTextColor
                            },

                            grid: {
                                display: false
                            }
                        },

                        y: {
                            beginAtZero: true,

                            ticks: {
                                color: chartTextColor,
                                precision: 0
                            },

                            grid: {
                                color: chartGridColor
                            }
                        }
                    },

                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            }
        );
    </script>
</x-app-layout>
