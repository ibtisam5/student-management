<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">Courses</h2>
            <p class="text-muted mb-0">
                Manage university courses
            </p>
        </div>

        <a href="{{ route('courses.create') }}"
           class="btn btn-primary">
            + Add Course
        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    <form method="GET"
      action="{{ route('courses.index') }}"
      class="row g-2 mb-4">

    <div class="col-md">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search by course code or name..."
            value="{{ request('search') }}"
        >
    </div>

    <div class="col-md-auto">
        <button type="submit" class="btn btn-primary w-100">
            Search
        </button>
    </div>

    @if(request()->filled('search'))
        <div class="col-md-auto">
            <a href="{{ route('courses.index') }}"
               class="btn btn-outline-secondary w-100">
                Clear
            </a>
        </div>
    @endif

</form>

    <div class="card shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead class="table-dark">

                <tr>

                    <th>Course Code</th>

                    <th>Name</th>

                    <th>Credit Hours</th>

                    <th>Status</th>

                    <th width="180">
                        Actions
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($courses as $course)

                    <tr>

                        <td>{{ $course->course_code }}</td>

                        <td>{{ $course->course_name }}</td>

                        <td>{{ $course->credit_hours }}</td>

                        <td>

                            @if($course->is_active)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </td>

                        <td>

                            <a href="{{ route('courses.edit',$course) }}"
                               class="btn btn-warning btn-sm">

                                Edit

                            </a>

                            <form
                                action="{{ route('courses.destroy',$course) }}"
                                method="POST"
                                class="d-inline">

                                @csrf

                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this course?')">

                                    Delete

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="text-center py-4">

                            No courses found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="mt-3">

        {{ $courses->links() }}

    </div>

</div>

</body>

</html>
