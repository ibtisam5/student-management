<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Academic Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f4f7fb;
            color: #172033;
        }

        .dashboard-container {
            max-width: 1800px;
            margin: auto;
        }

        .navigation-bar,
        .stat-card,
        .content-card {
            background: #ffffff;
            border: 0;
            border-radius: 18px;
            box-shadow: 0 8px 28px rgba(29, 48, 85, 0.07);
        }

        .navigation-bar {
            padding: 12px;
        }

        .navigation-bar .btn {
            border-radius: 11px;
        }

        .stat-card {
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(29, 48, 85, 0.12);
        }

        .stat-icon {
            width: 58px;
            height: 58px;
            border-radius: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 27px;
            flex-shrink: 0;
        }

        .chart-wrapper {
            position: relative;
            height: 320px;
        }

        .chart-wrapper.large {
            height: 360px;
        }

        .performance-score {
            min-width: 70px;
            text-align: center;
        }

        .risk-card {
            border: 0;
            border-left: 4px solid #dc3545;
            border-radius: 12px;
        }

        .table > :not(caption) > * > * {
            padding: 15px 14px;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .empty-state {
            padding: 35px 15px;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>

<body>

<div class="dashboard-container px-3 px-lg-5 py-4 py-lg-5">

    {{-- Header --}}
    <div
        class="d-flex flex-wrap justify-content-between
               align-items-center gap-3 mb-4"
    >
        <div>
            <p class="text-primary fw-semibold mb-1">
                SMART STUDENT MANAGEMENT SYSTEM
            </p>

            <h1 class="display-6 fw-bold mb-1">
                Academic Dashboard
            </h1>

            <p class="text-muted mb-0">
                Monitor student performance, attendance,
                grades and academic activity
            </p>
        </div>

        <div class="text-lg-end">
            <p class="text-muted mb-1">
                Dashboard updated
            </p>

            <h6 class="fw-bold mb-0">
                {{ now()->format('d F Y') }}
            </h6>
        </div>
    </div>

    {{-- Navigation --}}
    <div class="navigation-bar d-flex flex-wrap gap-2 mb-4">

        <a
            href="{{ route('dashboard') }}"
            class="btn btn-primary"
        >
            Dashboard
        </a>

        <a
            href="{{ route('students.index') }}"
            class="btn btn-outline-secondary"
        >
            Students
        </a>

        <a
            href="{{ route('courses.index') }}"
            class="btn btn-outline-secondary"
        >
            Courses
        </a>

        <a
            href="{{ route('enrollments.index') }}"
            class="btn btn-outline-secondary"
        >
            Enrollments
        </a>

        <a
            href="{{ route('attendances.index') }}"
            class="btn btn-outline-secondary"
        >
            Attendance
        </a>

        <a
            href="{{ route('grades.index') }}"
            class="btn btn-outline-secondary"
        >
            Grades
        </a>

        <a
            href="{{ route('ai-analyses.index') }}"
            class="btn btn-outline-primary"
        >
            AI Analysis
        </a>

    </div>

    {{-- Main Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between gap-3">

                        <div>
                            <p class="text-muted mb-2">
                                Total Students
                            </p>

                            <h2 class="fw-bold mb-1">
                                {{ $totalStudents }}
                            </h2>

                            <small class="text-success">
                                {{ $activeStudents }} active students
                            </small>
                        </div>

                        <div class="stat-icon bg-primary-subtle">
                            👨‍🎓
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between gap-3">

                        <div>
                            <p class="text-muted mb-2">
                                Courses
                            </p>

                            <h2 class="fw-bold mb-1">
                                {{ $totalCourses }}
                            </h2>

                            <small class="text-success">
                                {{ $activeCourses }} active courses
                            </small>
                        </div>

                        <div class="stat-icon bg-success-subtle">
                            📚
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between gap-3">

                        <div>
                            <p class="text-muted mb-2">
                                Enrollments
                            </p>

                            <h2 class="fw-bold mb-1">
                                {{ $totalEnrollments }}
                            </h2>

                            <small class="text-muted">
                                Course registrations
                            </small>
                        </div>

                        <div class="stat-icon bg-info-subtle">
                            📝
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between gap-3">

                        <div>
                            <p class="text-muted mb-2">
                                Attendance Records
                            </p>

                            <h2 class="fw-bold mb-1">
                                {{ $totalAttendanceRecords }}
                            </h2>

                            <small class="text-primary">
                                {{ $attendanceRate }}% attendance rate
                            </small>
                        </div>

                        <div class="stat-icon bg-warning-subtle">
                            ✅
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between gap-3">

                        <div>
                            <p class="text-muted mb-2">
                                Total Grades
                            </p>

                            <h2 class="fw-bold mb-1">
                                {{ $totalGrades }}
                            </h2>

                            <small class="text-muted">
                                Assessment records
                            </small>
                        </div>

                        <div class="stat-icon bg-danger-subtle">
                            📋
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between gap-3">

                        <div>
                            <p class="text-muted mb-2">
                                Average Grade
                            </p>

                            <h2 class="fw-bold text-primary mb-1">
                                {{ $averageGrade }}%
                            </h2>

                            <small class="text-muted">
                                Overall academic average
                            </small>
                        </div>

                        <div class="stat-icon bg-primary-subtle">
                            📊
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between gap-3">

                        <div>
                            <p class="text-muted mb-2">
                                Pass Rate
                            </p>

                            <h2 class="fw-bold text-success mb-1">
                                {{ $passRate }}%
                            </h2>

                            <small class="text-muted">
                                Grades of 60% or higher
                            </small>
                        </div>

                        <div class="stat-icon bg-success-subtle">
                            🏆
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between gap-3">

                        <div>
                            <p class="text-muted mb-2">
                                AI Analytics
                            </p>

                            <h2 class="fw-bold text-primary mb-1">
                                AI
                            </h2>

                            <a
                                href="{{ route('ai-analyses.create') }}"
                                class="small text-decoration-none"
                            >
                                Generate student analysis
                            </a>
                        </div>

                        <div class="stat-icon bg-info-subtle">
                            🤖
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Attendance and Grade Distribution --}}
    <div class="row g-4 mb-4">

        <div class="col-xl-6">
            <div class="card content-card h-100">
                <div class="card-body p-4">

                    <div class="mb-4">
                        <h5 class="section-title">
                            Attendance Distribution
                        </h5>

                        <p class="text-muted mb-0">
                            Distribution of attendance statuses
                        </p>
                    </div>

                    <div class="chart-wrapper">
                        <canvas id="attendanceChart"></canvas>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card content-card h-100">
                <div class="card-body p-4">

                    <div class="mb-4">
                        <h5 class="section-title">
                            Grade Distribution
                        </h5>

                        <p class="text-muted mb-0">
                            Assessment results grouped by percentage
                        </p>
                    </div>

                    <div class="chart-wrapper">
                        <canvas id="gradesChart"></canvas>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Students by Major and Course Performance --}}
    <div class="row g-4 mb-4">

        <div class="col-xl-5">
            <div class="card content-card h-100">
                <div class="card-body p-4">

                    <div class="mb-4">
                        <h5 class="section-title">
                            Students by Major
                        </h5>

                        <p class="text-muted mb-0">
                            Student distribution across programs
                        </p>
                    </div>

                    <div class="chart-wrapper large">
                        <canvas id="majorChart"></canvas>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card content-card h-100">
                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between
                               align-items-center mb-4"
                    >
                        <div>
                            <h5 class="section-title">
                                Course Performance
                            </h5>

                            <p class="text-muted mb-0">
                                Average grade for every course
                            </p>
                        </div>

                        <a
                            href="{{ route('grades.index') }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            View Grades
                        </a>
                    </div>

                    <div class="chart-wrapper large">
                        <canvas id="courseChart"></canvas>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- Top Students and At-Risk Students --}}
    <div class="row g-4 mb-4">

        <div class="col-xl-5">
            <div class="card content-card h-100">
                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between
                               align-items-center mb-4"
                    >
                        <div>
                            <h5 class="section-title">
                                Top Performers
                            </h5>

                            <p class="text-muted mb-0">
                                Students with the highest averages
                            </p>
                        </div>

                        <span class="badge text-bg-success">
                            Top {{ $topStudents->count() }}
                        </span>
                    </div>

                    @forelse($topStudents as $index => $student)

                        <div
                            class="d-flex align-items-center
                                   justify-content-between
                                   gap-3 py-3 border-bottom"
                        >
                            <div class="d-flex align-items-center gap-3">

                                <div
                                    class="rounded-circle bg-primary-subtle
                                           d-flex align-items-center
                                           justify-content-center fw-bold"
                                    style="width: 44px; height: 44px;"
                                >
                                    {{ $index + 1 }}
                                </div>

                                <div>
                                    <p class="fw-semibold mb-0">
                                        {{ $student['full_name'] }}
                                    </p>

                                    <small class="text-muted">
                                        {{ $student['student_number'] }}
                                        · {{ $student['major'] }}
                                    </small>
                                </div>

                            </div>

                            <span
                                class="badge text-bg-success
                                       performance-score fs-6"
                            >
                                {{ $student['average_grade'] }}%
                            </span>
                        </div>

                    @empty

                        <div class="empty-state">
                            No performance data available.
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card content-card h-100">
                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between
                               align-items-center mb-4"
                    >
                        <div>
                            <h5 class="section-title">
                                Students Requiring Attention
                            </h5>

                            <p class="text-muted mb-0">
                                Grade below 60% or attendance below 75%
                            </p>
                        </div>

                        <span class="badge text-bg-danger fs-6">
                            {{ $atRiskStudents->count() }} alerts
                        </span>
                    </div>

                    @forelse($atRiskStudents as $student)

                        <div class="card risk-card bg-light mb-3">
                            <div class="card-body">

                                <div
                                    class="d-flex flex-wrap
                                           justify-content-between
                                           align-items-center gap-3"
                                >
                                    <div>
                                        <h6 class="fw-bold mb-1">
                                            {{ $student['full_name'] }}
                                        </h6>

                                        <small class="text-muted">
                                            {{ $student['student_number'] }}
                                            · {{ $student['major'] }}
                                        </small>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2">

                                        <span
                                            class="badge
                                            {{ $student['average_grade'] < 60
                                                ? 'text-bg-danger'
                                                : 'text-bg-success' }}"
                                        >
                                            Grade:
                                            {{ $student['average_grade'] }}%
                                        </span>

                                        <span
                                            class="badge
                                            {{ $student['attendance_rate'] < 75
                                                ? 'text-bg-warning'
                                                : 'text-bg-success' }}"
                                        >
                                            Attendance:
                                            {{ $student['attendance_rate'] }}%
                                        </span>

                                        <a
                                            href="{{ route(
                                                'ai-analyses.create',
                                                ['student_id' => $student['id']]
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Analyze
                                        </a>

                                    </div>
                                </div>

                            </div>
                        </div>

                    @empty

                        <div class="empty-state">
                            <h6 class="fw-bold">
                                No academic alerts
                            </h6>

                            <p class="mb-0">
                                All evaluated students are performing well.
                            </p>
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

    </div>

    {{-- Recent Enrollments and Students --}}
    <div class="row g-4">

        <div class="col-xl-5">
            <div class="card content-card h-100">
                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between
                               align-items-center mb-4"
                    >
                        <div>
                            <h5 class="section-title">
                                Recent Enrollments
                            </h5>

                            <p class="text-muted mb-0">
                                Latest course registrations
                            </p>
                        </div>

                        <a
                            href="{{ route('enrollments.index') }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            View All
                        </a>
                    </div>

                    @forelse($recentEnrollments as $enrollment)

                        <div class="py-3 border-bottom">

                            <div
                                class="d-flex justify-content-between
                                       align-items-start gap-3"
                            >
                                <div>
                                    <p class="fw-semibold mb-1">
                                        {{ $enrollment->student->full_name }}
                                    </p>

                                    <small class="text-muted">
                                        {{ $enrollment->course->course_code }}
                                        —
                                        {{ $enrollment->course->course_name }}
                                    </small>
                                </div>

                                <span
                                    class="badge text-bg-primary
                                           align-self-start"
                                >
                                    {{ $enrollment->semester }}
                                </span>
                            </div>

                        </div>

                    @empty

                        <div class="empty-state">
                            No enrollment records found.
                        </div>

                    @endforelse

                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card content-card h-100">
                <div class="card-body p-4">

                    <div
                        class="d-flex justify-content-between
                               align-items-center mb-4"
                    >
                        <div>
                            <h5 class="section-title">
                                Recently Added Students
                            </h5>

                            <p class="text-muted mb-0">
                                Latest students registered in the system
                            </p>
                        </div>

                        <a
                            href="{{ route('students.index') }}"
                            class="btn btn-sm btn-outline-primary"
                        >
                            View Students
                        </a>
                    </div>

                    <div class="table-responsive">

                        <table
                            class="table table-hover align-middle mb-0"
                        >
                            <thead class="table-light">
                            <tr>
                                <th>Student Number</th>
                                <th>Full Name</th>
                                <th>Major</th>
                                <th>Academic Year</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>

                            @forelse($recentStudents as $student)

                                <tr>
                                    <td class="fw-semibold">
                                        {{ $student->student_number }}
                                    </td>

                                    <td>
                                        {{ $student->full_name }}
                                    </td>

                                    <td>
                                        {{ $student->major }}
                                    </td>

                                    <td>
                                        {{ $student->academic_year }}
                                    </td>

                                    <td>
                                        <span
                                            class="badge
                                            {{ $student->status === 'Active'
                                                ? 'text-bg-success'
                                                : 'text-bg-secondary' }}"
                                        >
                                            {{ $student->status }}
                                        </span>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="5"
                                        class="text-center py-4
                                               text-muted"
                                    >
                                        No students found.
                                    </td>
                                </tr>

                            @endforelse

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const attendanceLabels = @json(
        array_keys($attendanceDistribution)
    );

    const attendanceValues = @json(
        array_values($attendanceDistribution)
    );

    new Chart(
        document.getElementById('attendanceChart'),
        {
            type: 'doughnut',

            data: {
                labels: attendanceLabels,

                datasets: [{
                    data: attendanceValues,

                    backgroundColor: [
                        '#198754',
                        '#dc3545',
                        '#ffc107',
                        '#0dcaf0'
                    ],

                    borderWidth: 0
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',

                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        }
    );

    const gradeLabels = @json(
        array_keys($gradeDistribution)
    );

    const gradeValues = @json(
        array_values($gradeDistribution)
    );

    new Chart(
        document.getElementById('gradesChart'),
        {
            type: 'bar',

            data: {
                labels: gradeLabels,

                datasets: [{
                    label: 'Grade Records',
                    data: gradeValues,
                    backgroundColor: '#0d6efd',
                    borderRadius: 9,
                    borderSkipped: false
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                scales: {
                    y: {
                        beginAtZero: true,

                        ticks: {
                            precision: 0
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

    const majorLabels = @json(
        $studentsByMajor->pluck('major')
    );

    const majorValues = @json(
        $studentsByMajor->pluck('total')
    );

    new Chart(
        document.getElementById('majorChart'),
        {
            type: 'bar',

            data: {
                labels: majorLabels,

                datasets: [{
                    label: 'Students',
                    data: majorValues,
                    backgroundColor: '#198754',
                    borderRadius: 9,
                    borderSkipped: false
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
                            precision: 0
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

    const courseLabels = @json(
        $coursePerformance->pluck('course_code')
    );

    const courseValues = @json(
        $coursePerformance->pluck('average')
    );

    new Chart(
        document.getElementById('courseChart'),
        {
            type: 'line',

            data: {
                labels: courseLabels,

                datasets: [{
                    label: 'Average Grade',
                    data: courseValues,
                    borderColor: '#0d6efd',
                    backgroundColor:
                        'rgba(13, 110, 253, 0.12)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },

                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        }
    );
</script>

</body>
</html>
