<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5" style="max-width: 900px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Edit Course</h1>
            <p class="text-muted mb-0">
                Update course information
            </p>
        </div>

        <a href="{{ route('courses.index') }}"
           class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <form method="POST"
                  action="{{ route('courses.update', $course) }}">

                @csrf
                @method('PUT')

                @include('courses._form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Update Course
                    </button>

                    <a href="{{ route('courses.index') }}"
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
