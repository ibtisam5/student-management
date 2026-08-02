<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Enrollment</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5" style="max-width: 950px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Add Enrollment</h1>

            <p class="text-muted mb-0">
                Register a student in a course
            </p>
        </div>

        <a href="{{ route('enrollments.index') }}"
           class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form
                method="POST"
                action="{{ route('enrollments.store') }}"
            >
                @csrf

                @include('enrollments._form')

                <div class="mt-4">
                    <button type="submit"
                            class="btn btn-primary">
                        Save Enrollment
                    </button>

                    <a href="{{ route('enrollments.index') }}"
                       class="btn btn-light">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>

</div>

</body>
</html>
