<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Grade Details</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5" style="max-width: 950px;">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>
            <h1 class="fw-bold mb-1">
                Grade Details
            </h1>

            <p class="text-muted mb-0">
                Review student assessment information
            </p>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('grades.edit', $grade) }}"
                class="btn btn-primary"
            >
                Edit
            </a>

            <a
                href="{{ route('grades.index') }}"
                class="btn btn-outline-secondary"
            >
                Back
            </a>

        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 p-md-5">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <p class="text-muted mb-1">
                                Student
                            </p>

                            <h5 class="fw-bold">
                                {{ $grade
                                    ->enrollment
                                    ->student
                                    ->full_name }}
                            </h5>

                            <p class="mb-0">
                                {{ $grade
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
                                {{ $grade
                                    ->enrollment
                                    ->course
                                    ->course_code }}
                            </h5>

                            <p class="mb-0">
                                {{ $grade
                                    ->enrollment
                                    ->course
                                    ->course_name }}
                            </p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted mb-1">
                                Assessment
                            </p>

                            <h5 class="fw-bold">
                                {{ $grade->assessment_name }}
                            </h5>

                            <p class="mb-0">
                                {{ $grade->assessment_type }}
                            </p>
                        </div>

                        <div class="col-md-6">
                            <p class="text-muted mb-1">
                                Assessment Date
                            </p>

                            <h5 class="fw-bold mb-0">
                                {{ $grade->assessment_date
                                    ? $grade->assessment_date
                                        ->format('d F Y')
                                    : 'Not specified'
                                }}
                            </h5>
                        </div>

                        <div class="col-12">
                            <hr>

                            <p class="text-muted mb-2">
                                Notes
                            </p>

                            <p class="mb-0">
                                {{ $grade->notes
                                    ?: 'No notes provided.'
                                }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">

                    <p class="text-muted mb-2">
                        Final Result
                    </p>

                    <h1 class="display-4 fw-bold text-primary">
                        {{ number_format(
                            $grade->percentage(),
                            2
                        ) }}%
                    </h1>

                    <span
                        class="badge fs-5 text-bg-{{ $grade->gradeBadgeClass() }}"
                    >
                        {{ $grade->letterGrade() }}
                    </span>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">
                            Score
                        </span>

                        <strong>
                            {{ $grade->score }}
                            /
                            {{ $grade->maximum_score }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <span class="text-muted">
                            Weight
                        </span>

                        <strong>
                            {{ $grade->weight }}%
                        </strong>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>
