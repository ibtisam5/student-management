<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Enrollment Details</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5" style="max-width: 1000px;">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>
            <h1 class="fw-bold mb-1">
                Enrollment Details
            </h1>

            <p class="text-muted mb-0">
                Student, course, attendance and grades
            </p>
        </div>

        <div class="d-flex gap-2">
            <a
                href="{{ route(
                    'enrollments.edit',
                    $enrollment
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

            <a
                href="{{ route('enrollments.index') }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        Student Information
                    </h5>

                    <p class="text-muted mb-1">
                        Full Name
                    </p>

                    <p class="fw-semibold">
                        {{ $enrollment->student->full_name }}
                    </p>

                    <p class="text-muted mb-1">
                        Student Number
                    </p>

                    <p class="fw-semibold">
                        {{ $enrollment->student->student_number }}
                    </p>

                    <p class="text-muted mb-1">
                        Major
                    </p>

                    <p class="fw-semibold mb-0">
                        {{ $enrollment->student->major }}
                    </p>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-4">
                        Course Information
                    </h5>

                    <p class="text-muted mb-1">
                        Course
                    </p>

                    <p class="fw-semibold">
                        {{ $enrollment->course->course_code }}
                        — {{ $enrollment->course->course_name }}
                    </p>

                    <p class="text-muted mb-1">
                        Semester
                    </p>

                    <p class="fw-semibold">
                        {{ $enrollment->semester }}
                    </p>

                    <p class="text-muted mb-1">
                        Academic Year
                    </p>

                    <p class="fw-semibold mb-0">
                        {{ $enrollment->academic_year }}
                    </p>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Attendance Records
                    </p>

                    <h2 class="fw-bold mb-2">
                        {{ $enrollment->attendances->count() }}
                    </h2>

                    <p class="mb-0">
                        Attendance:
                        <strong>
                            {{ $enrollment->attendancePercentage() }}%
                        </strong>
                    </p>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <p class="text-muted mb-2">
                        Grade Records
                    </p>

                    <h2 class="fw-bold mb-2">
                        {{ $enrollment->grades->count() }}
                    </h2>

                    <p class="mb-0">
                        Grade:
                        <strong>
                            {{ $enrollment->gradePercentage() }}%
                        </strong>
                    </p>

                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>
