<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Enrollments</title>

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
            <h1 class="fw-bold mb-1">Enrollments</h1>

            <p class="text-muted mb-0">
                Manage student course registrations
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('students.index') }}"
               class="btn btn-outline-secondary">
                Students
            </a>

            <a href="{{ route('courses.index') }}"
               class="btn btn-outline-secondary">
                Courses
            </a>

            <a href="{{ route('enrollments.create') }}"
               class="btn btn-primary">
                + Add Enrollment
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

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Total Enrollments
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $totalEnrollments }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Enrolled Students
                    </p>

                    <h2 class="fw-bold text-primary mb-0">
                        {{ $totalStudents }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Registered Courses
                    </p>

                    <h2 class="fw-bold text-success mb-0">
                        {{ $totalCourses }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    <form
        method="GET"
        action="{{ route('enrollments.index') }}"
        class="card border-0 shadow-sm mb-4"
    >
        <div class="card-body">
            <div class="row g-2">

                <div class="col-lg-5">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search student or course..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-lg-2">
                    <select
                        name="semester"
                        class="form-select"
                    >
                        <option value="">All Semesters</option>

                        @foreach(['Fall', 'Spring', 'Summer'] as $semester)
                            <option
                                value="{{ $semester }}"
                                @selected(
                                    request('semester') === $semester
                                )
                            >
                                {{ $semester }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2">
                    <input
                        type="number"
                        name="academic_year"
                        class="form-control"
                        placeholder="Year"
                        value="{{ request('academic_year') }}"
                    >
                </div>

                <div class="col-lg-2">
                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        Search
                    </button>
                </div>

                <div class="col-lg-1">
                    <a
                        href="{{ route('enrollments.index') }}"
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
                        <th>Student Number</th>
                        <th>Course</th>
                        <th>Semester</th>
                        <th>Academic Year</th>
                        <th>Enrollment Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($enrollments as $enrollment)
                        <tr>
                            <td class="ps-4">
                                {{ $enrollments->firstItem() + $loop->index }}
                            </td>

                            <td class="fw-semibold">
                                {{ $enrollment->student->full_name }}
                            </td>

                            <td>
                                {{ $enrollment->student->student_number }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $enrollment->course->course_code }}
                                </div>

                                <small class="text-muted">
                                    {{ $enrollment->course->course_name }}
                                </small>
                            </td>

                            <td>
                                <span class="badge text-bg-primary">
                                    {{ $enrollment->semester }}
                                </span>
                            </td>

                            <td>
                                {{ $enrollment->academic_year }}
                            </td>

                            <td>
                                {{ $enrollment->enrolled_at
                                    ? $enrollment->enrolled_at->format('d M Y')
                                    : '—'
                                }}
                            </td>

                            <td class="text-end pe-4">
                                <a
                                    href="{{ route(
                                        'enrollments.show',
                                        $enrollment
                                    ) }}"
                                    class="btn btn-outline-dark btn-sm"
                                >
                                    View
                                </a>

                                <a
                                    href="{{ route(
                                        'enrollments.edit',
                                        $enrollment
                                    ) }}"
                                    class="btn btn-outline-primary btn-sm"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'enrollments.destroy',
                                        $enrollment
                                    ) }}"
                                    class="d-inline"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm(
                                            'Delete this enrollment?'
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
                                colspan="8"
                                class="text-center py-5"
                            >
                                <h5 class="mb-2">
                                    No enrollments found
                                </h5>

                                <p class="text-muted mb-3">
                                    No records match the current filters.
                                </p>

                                <a
                                    href="{{ route(
                                        'enrollments.create'
                                    ) }}"
                                    class="btn btn-primary"
                                >
                                    Add Enrollment
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
        {{ $enrollments->links() }}
    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>
