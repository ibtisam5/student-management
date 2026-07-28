<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Student</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5" style="max-width: 850px;">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Edit Student</h1>
            <p class="text-muted mb-0">Update student information</p>
        </div>

        <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
            Back
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please correct the following errors:</strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Student Number</label>
                        <input
                            type="text"
                            name="student_number"
                            value="{{ old('student_number', $student->student_number) }}"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input
                            type="text"
                            name="full_name"
                            value="{{ old('full_name', $student->full_name) }}"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $student->email) }}"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $student->phone) }}"
                            class="form-control"
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Major</label>
                        <input
                            type="text"
                            name="major"
                            value="{{ old('major', $student->major) }}"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Academic Year</label>
                        <input
                            type="number"
                            name="academic_year"
                            value="{{ old('academic_year', $student->academic_year) }}"
                            min="1"
                            max="10"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option
                                value="Active"
                                @selected(old('status', $student->status) === 'Active')
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                @selected(old('status', $student->status) === 'Inactive')
                            >
                                Inactive
                            </option>
                        </select>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Update Student
                    </button>

                    <a href="{{ route('students.index') }}" class="btn btn-light">
                        Cancel
                    </a>
                </div>
            </form>

        </div>
    </div>

</div>

</body>
</html>
