<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Students List</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        body {
            background: #f6f8fb;
        }

        .page-container {
            max-width: 1450px;
        }

        .stat-card {
            border: 0;
            border-radius: 14px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.10) !important;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 1.6rem;
        }

        .main-card {
            border: 0;
            border-radius: 14px;
        }

        .table th {
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .sort-link {
            color: #212529;
            text-decoration: none;
        }

        .sort-link:hover {
            color: #0d6efd;
        }

        .actions-cell {
            white-space: nowrap;
        }

        @media (max-width: 767.98px) {
            .page-heading {
                flex-direction: column;
                align-items: stretch !important;
                gap: 16px;
            }

            .header-actions {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
            }

            .filter-actions {
                display: grid;
                grid-template-columns: 1fr;
                gap: 8px;
            }
        }
    </style>
</head>

<body>

<div class="container page-container py-5">

    <!-- عنوان الصفحة والأزرار -->
    <div class="page-heading d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Students</h1>

            <p class="text-muted mb-0">
                Student Management System
            </p>
        </div>

        <div class="header-actions d-flex gap-2">
            <a
                href="{{ route('students.export.csv') }}"
                class="btn btn-outline-success"
            >
                <i class="bi bi-file-earmark-spreadsheet me-1"></i>
                Export CSV
            </a>

            <a
                href="{{ route('students.create') }}"
                class="btn btn-primary"
            >
                <i class="bi bi-person-plus-fill me-1"></i>
                Add Student
            </a>
        </div>
    </div>

    <!-- رسالة النجاح -->
    @if (session('success'))
        <div
            class="alert alert-success alert-dismissible fade show shadow-sm"
            role="alert"
        >
            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>
    @endif

    <!-- بطاقات الإحصائيات -->
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Total Students</p>

                        <h2 class="fw-bold mb-0">
                            {{ $totalStudents }}
                        </h2>
                    </div>

                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Active Students</p>

                        <h2 class="fw-bold text-success mb-0">
                            {{ $activeStudents }}
                        </h2>
                    </div>

                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Inactive Students</p>

                        <h2 class="fw-bold text-secondary mb-0">
                            {{ $inactiveStudents }}
                        </h2>
                    </div>

                    <div class="stat-icon bg-secondary-subtle text-secondary">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-2">Total Majors</p>

                        <h2 class="fw-bold text-primary mb-0">
                            {{ $totalMajors }}
                        </h2>
                    </div>

                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-journal-bookmark-fill"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- البحث والفلترة -->
    <form
        action="{{ route('students.index') }}"
        method="GET"
        class="mb-4"
    >
        <input
            type="hidden"
            name="sort"
            value="{{ $sort }}"
        >

        <input
            type="hidden"
            name="direction"
            value="{{ $direction }}"
        >

        <div class="row g-2">

            <div class="col-12 col-lg-7">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Search by student number, name, email, or major"
                >
            </div>

            <div class="col-12 col-md-5 col-lg-2">
                <select
                    name="status"
                    class="form-select"
                >
                    <option
                        value="all"
                        {{ request('status', 'all') === 'all' ? 'selected' : '' }}
                    >
                        All Statuses
                    </option>

                    <option
                        value="Active"
                        {{ request('status') === 'Active' ? 'selected' : '' }}
                    >
                        Active
                    </option>

                    <option
                        value="Inactive"
                        {{ request('status') === 'Inactive' ? 'selected' : '' }}
                    >
                        Inactive
                    </option>
                </select>
            </div>

            <div class="col-12 col-md-7 col-lg-3">
                <div class="filter-actions d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary flex-grow-1"
                    >
                        <i class="bi bi-search me-1"></i>
                        Search
                    </button>

                    @if (
                        request('search') ||
                        request('status', 'all') !== 'all'
                    )
                        <a
                            href="{{ route('students.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bi bi-x-circle me-1"></i>
                            Clear
                        </a>
                    @endif

                </div>
            </div>

        </div>
    </form>

    <!-- جدول الطلاب -->
    <div class="card main-card shadow-sm">
        <div class="card-body p-3 p-md-4">

            @if ($students->count() > 0)

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                        <tr>
                            <th>#</th>

                            <th>
                                <a
                                    href="{{ route('students.index', array_merge(request()->query(), [
                                        'sort' => 'student_number',
                                        'direction' => $sort === 'student_number' && $direction === 'asc'
                                            ? 'desc'
                                            : 'asc',
                                        'page' => 1,
                                    ])) }}"
                                    class="sort-link"
                                >
                                    Student Number

                                    @if ($sort === 'student_number')
                                        {{ $direction === 'asc' ? '▲' : '▼' }}
                                    @endif
                                </a>
                            </th>

                            <th>
                                <a
                                    href="{{ route('students.index', array_merge(request()->query(), [
                                        'sort' => 'full_name',
                                        'direction' => $sort === 'full_name' && $direction === 'asc'
                                            ? 'desc'
                                            : 'asc',
                                        'page' => 1,
                                    ])) }}"
                                    class="sort-link"
                                >
                                    Full Name

                                    @if ($sort === 'full_name')
                                        {{ $direction === 'asc' ? '▲' : '▼' }}
                                    @endif
                                </a>
                            </th>

                            <th>
                                <a
                                    href="{{ route('students.index', array_merge(request()->query(), [
                                        'sort' => 'email',
                                        'direction' => $sort === 'email' && $direction === 'asc'
                                            ? 'desc'
                                            : 'asc',
                                        'page' => 1,
                                    ])) }}"
                                    class="sort-link"
                                >
                                    Email

                                    @if ($sort === 'email')
                                        {{ $direction === 'asc' ? '▲' : '▼' }}
                                    @endif
                                </a>
                            </th>

                            <th>
                                <a
                                    href="{{ route('students.index', array_merge(request()->query(), [
                                        'sort' => 'major',
                                        'direction' => $sort === 'major' && $direction === 'asc'
                                            ? 'desc'
                                            : 'asc',
                                        'page' => 1,
                                    ])) }}"
                                    class="sort-link"
                                >
                                    Major

                                    @if ($sort === 'major')
                                        {{ $direction === 'asc' ? '▲' : '▼' }}
                                    @endif
                                </a>
                            </th>

                            <th>
                                <a
                                    href="{{ route('students.index', array_merge(request()->query(), [
                                        'sort' => 'academic_year',
                                        'direction' => $sort === 'academic_year' && $direction === 'asc'
                                            ? 'desc'
                                            : 'asc',
                                        'page' => 1,
                                    ])) }}"
                                    class="sort-link"
                                >
                                    Academic Year

                                    @if ($sort === 'academic_year')
                                        {{ $direction === 'asc' ? '▲' : '▼' }}
                                    @endif
                                </a>
                            </th>

                            <th>Status</th>

                            <th class="text-end">
                                Actions
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>
                                    {{ $students->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ $student->student_number }}
                                </td>

                                <td>
                                    {{ $student->full_name }}
                                </td>

                                <td>
                                    {{ $student->email }}
                                </td>

                                <td>
                                    {{ $student->major }}
                                </td>

                                <td>
                                    {{ $student->academic_year }}
                                </td>

                                <td>
                                    @if ($student->status === 'Active')
                                        <span class="badge text-bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge text-bg-secondary">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end actions-cell">

                                    <a
                                        href="{{ route('students.edit', $student) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        <i class="bi bi-pencil-square me-1"></i>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $student->id }}"
                                    >
                                        <i class="bi bi-trash3 me-1"></i>
                                        Delete
                                    </button>

                                </td>
                            </tr>

                            <!-- نافذة تأكيد الحذف -->
                            <div
                                class="modal fade"
                                id="deleteModal{{ $student->id }}"
                                tabindex="-1"
                                aria-labelledby="deleteModalLabel{{ $student->id }}"
                                aria-hidden="true"
                            >
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5
                                                class="modal-title"
                                                id="deleteModalLabel{{ $student->id }}"
                                            >
                                                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                                Delete Student
                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                                aria-label="Close"
                                            ></button>
                                        </div>

                                        <div class="modal-body">
                                            <p>
                                                Are you sure you want to delete
                                                <strong>
                                                    {{ $student->full_name }}
                                                </strong>?
                                            </p>

                                            <p class="text-danger mb-0">
                                                This action cannot be undone.
                                            </p>
                                        </div>

                                        <div class="modal-footer">
                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Cancel
                                            </button>

                                            <form
                                                action="{{ route('students.destroy', $student) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >
                                                    <i class="bi bi-trash3 me-1"></i>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </tbody>

                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $students->links() }}
                </div>

            @else

                <div class="text-center py-5">
                    <i class="bi bi-person-x fs-1 text-muted"></i>

                    <h4 class="mt-3">
                        No students found
                    </h4>

                    <p class="text-muted">
                        No students match the current search or filters.
                    </p>

                    <a
                        href="{{ route('students.create') }}"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-person-plus-fill me-1"></i>
                        Add Student
                    </a>

                    @if (
                        request('search') ||
                        request('status', 'all') !== 'all'
                    )
                        <a
                            href="{{ route('students.index') }}"
                            class="btn btn-outline-secondary"
                        >
                            Clear Filters
                        </a>
                    @endif
                </div>

            @endif

        </div>
    </div>

</div>

<!-- Bootstrap JavaScript -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>
