<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Attendance Details</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5" style="max-width: 900px;">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>
            <h1 class="fw-bold mb-1">
                Attendance Details
            </h1>

            <p class="text-muted mb-0">
                Review attendance information
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'attendances.edit',
                    $attendance
                ) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

            <a
                href="{{ route('attendances.index') }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">

            <div class="row g-4">

                <div class="col-md-6">
                    <p class="text-muted mb-1">
                        Student
                    </p>

                    <h5 class="fw-bold">
                        {{ $attendance
                            ->enrollment
                            ->student
                            ->full_name }}
                    </h5>

                    <p class="mb-0">
                        {{ $attendance
                            ->enrollment
                            ->student
                            ->student_number }}
                    </p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted mb-1">
                        Course
                    </p>

                    <h5 class="fw-bold">
                        {{ $attendance
                            ->enrollment
                            ->course
                            ->course_code }}
                    </h5>

                    <p class="mb-0">
                        {{ $attendance
                            ->enrollment
                            ->course
                            ->course_name }}
                    </p>
                </div>

                <div class="col-md-6">
                    <p class="text-muted mb-1">
                        Date
                    </p>

                    <h5 class="fw-bold mb-0">
                        {{ $attendance
                            ->attendance_date
                            ->format('d F Y') }}
                    </h5>
                </div>

                <div class="col-md-6">
                    <p class="text-muted mb-1">
                        Status
                    </p>

                    <h5 class="fw-bold mb-0">
                        {{ $attendance->status }}
                    </h5>
                </div>

                <div class="col-12">
                    <hr>

                    <p class="text-muted mb-2">
                        Notes
                    </p>

                    <p class="mb-0">
                        {{ $attendance->notes ?: 'No notes provided.' }}
                    </p>
                </div>

            </div>

        </div>
    </div>

</div>

</body>
</html>
