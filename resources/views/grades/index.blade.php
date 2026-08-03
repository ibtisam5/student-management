<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Grades</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container-fluid px-4 px-lg-5 py-5">

    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">

        <div>
            <h1 class="fw-bold mb-1">Grades</h1>

            <p class="text-muted mb-0">
                Manage student assessment results
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <a href="{{ route('students.index') }}"
               class="btn btn-outline-secondary">
                Students
            </a>

            <a href="{{ route('courses.index') }}"
               class="btn btn-outline-secondary">
                Courses
            </a>

            <a href="{{ route('enrollments.index') }}"
               class="btn btn-outline-secondary">
                Enrollments
            </a>

            <a href="{{ route('attendances.index') }}"
               class="btn btn-outline-secondary">
                Attendance
            </a>

            <a href="{{ route('grades.create') }}"
               class="btn btn-primary">
                + Add Grade
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

        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Total Grades
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $totalGrades }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Average
                    </p>

                    <h2 class="fw-bold text-primary mb-0">
                        {{ $averagePercentage }}%
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Passed
                    </p>

                    <h2 class="fw-bold text-success mb-0">
                        {{ $passedCount }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Failed
                    </p>

                    <h2 class="fw-bold text-danger mb-0">
                        {{ $failedCount }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-xl col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Highest Grade
                    </p>

                    <h2 class="fw-bold text-warning mb-0">
                        {{ $highestPercentage }}%
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <form
        method="GET"
        action="{{ route('grades.index') }}"
        class="card border-0 shadow-sm mb-4"
    >
        <div class="card-body">

            <div class="row g-2">

                <div class="col-xl-4 col-lg-6">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search student, course or assessment..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-xl-2 col-lg-3">
                    <select
                        name="course_id"
                        class="form-select"
                    >
                        <option value="">All Courses</option>

                        @foreach($courses as $course)
                            <option
                                value="{{ $course->id }}"
                                @selected(
                                    request('course_id') == $course->id
                                )
                            >
                                {{ $course->course_code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-lg-3">
                    <select
                        name="assessment_type"
                        class="form-select"
                    >
                        <option value="">All Assessment Types</option>

                        @foreach([
                            'Quiz',
                            'Assignment',
                            'Midterm',
                            'Final',
                            'Project',
                            'Other'
                        ] as $type)
                            <option
                                value="{{ $type }}"
                                @selected(
                                    request('assessment_type') === $type
                                )
                            >
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-xl-2 col-lg-4">
                    <input
                        type="number"
                        name="minimum_percentage"
                        min="0"
                        max="100"
                        step="1"
                        class="form-control"
                        placeholder="Minimum %"
                        value="{{ request('minimum_percentage') }}"
                    >
                </div>

                <div class="col-xl-1 col-lg-4">
                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        Search
                    </button>
                </div>

                <div class="col-xl-1 col-lg-4">
                    <a
                        href="{{ route('grades.index') }}"
                        class="btn btn-outline-secondary w-100"
                    >
                        Clear
                    </a>
                </div>

            </div>

        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Assessment</th>
                        <th>Score</th>
                        <th>Percentage</th>
                        <th>Grade</th>
                        <th>Date</th>
                        <th class="text-end pe-4">
                            Actions
                        </th>
                    </tr>
                    </thead>

                    <tbody>

                    @forelse($grades as $grade)

                        <tr>
                            <td class="ps-4">
                                {{ $grades->firstItem()
                                    + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $grade
                                        ->enrollment
                                        ->student
                                        ->full_name }}
                                </div>

                                <small class="text-muted">
                                    {{ $grade
                                        ->enrollment
                                        ->student
                                        ->student_number }}
                                </small>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $grade
                                        ->enrollment
                                        ->course
                                        ->course_code }}
                                </div>

                                <small class="text-muted">
                                    {{ $grade
                                        ->enrollment
                                        ->course
                                        ->course_name }}
                                </small>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $grade->assessment_name }}
                                </div>

                                <small class="text-muted">
                                    {{ $grade->assessment_type }}
                                    · {{ $grade->weight }}%
                                </small>
                            </td>

                            <td>
                                {{ $grade->score }}
                                /
                                {{ $grade->maximum_score }}
                            </td>

                            <td class="fw-semibold">
                                {{ number_format(
                                    $grade->percentage(),
                                    2
                                ) }}%
                            </td>

                            <td>
                                <span
                                    class="badge text-bg-{{ $grade->gradeBadgeClass() }}"
                                >
                                    {{ $grade->letterGrade() }}
                                </span>
                            </td>

                            <td>
                                {{ $grade->assessment_date
                                    ? $grade->assessment_date
                                        ->format('d M Y')
                                    : '—'
                                }}
                            </td>

                            <td class="text-end pe-4">

                                <a
                                    href="{{ route(
                                        'grades.show',
                                        $grade
                                    ) }}"
                                    class="btn btn-outline-dark btn-sm"
                                >
                                    View
                                </a>

                                <a
                                    href="{{ route(
                                        'grades.edit',
                                        $grade
                                    ) }}"
                                    class="btn btn-outline-primary btn-sm"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'grades.destroy',
                                        $grade
                                    ) }}"
                                    class="d-inline"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm(
                                            'Delete this grade record?'
                                        )"
                                    >
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="9"
                                class="text-center py-5"
                            >
                                <h5 class="mb-2">
                                    No grades found
                                </h5>

                                <p class="text-muted mb-3">
                                    No records match the current filters.
                                </p>

                                <a
                                    href="{{ route('grades.create') }}"
                                    class="btn btn-primary"
                                >
                                    Add Grade
                                </a>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    <div class="mt-4">
        {{ $grades->links() }}
    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>
