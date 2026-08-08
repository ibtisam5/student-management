<x-app-layout>

    <div class="py-8">
        <div class="container page-container">



<section class="students-hero mb-4">
    <div class="students-hero-content">
        <div>
            <p class="students-eyebrow">
                Student Management
            </p>

            <h1 class="students-title">
                Students
            </h1>

            <p class="students-subtitle">
                Manage student records, academic details and current status.
            </p>
        </div>

        <div class="students-hero-actions">
            <a
                href="{{ route('students.export.csv') }}"
                class="btn students-secondary-button"
            >
                <i class="bi bi-file-earmark-spreadsheet"></i>
                Export CSV
            </a>

            <a
                href="{{ route('students.create') }}"
                class="btn students-primary-button"
            >
                <i class="bi bi-person-plus-fill"></i>
                Add Student
            </a>
        </div>
    </div>
</section>

<section class="students-stats-grid mb-4">
    <article class="students-stat-card students-stat-blue">
        <div>
            <p class="students-stat-label">
                Total Students
            </p>

            <h2 class="students-stat-value">
                {{ number_format($totalStudents) }}
            </h2>

            <p class="students-stat-note">
                All registered students
            </p>
        </div>

        <div class="students-stat-icon">
            <i class="bi bi-people-fill"></i>
        </div>
    </article>

    <article class="students-stat-card students-stat-green">
        <div>
            <p class="students-stat-label">
                Active Students
            </p>

            <h2 class="students-stat-value">
                {{ number_format($activeStudents) }}
            </h2>

            <p class="students-stat-note">
                Currently active
            </p>
        </div>

        <div class="students-stat-icon">
            <i class="bi bi-person-check-fill"></i>
        </div>
    </article>

    <article class="students-stat-card students-stat-gray">
        <div>
            <p class="students-stat-label">
                Inactive Students
            </p>

            <h2 class="students-stat-value">
                {{ number_format($inactiveStudents) }}
            </h2>

            <p class="students-stat-note">
                Currently inactive
            </p>
        </div>

        <div class="students-stat-icon">
            <i class="bi bi-person-x-fill"></i>
        </div>
    </article>

    <article class="students-stat-card students-stat-purple">
        <div>
            <p class="students-stat-label">
                Total Majors
            </p>

            <h2 class="students-stat-value">
                {{ number_format($totalMajors) }}
            </h2>

            <p class="students-stat-note">
                Academic programs
            </p>
        </div>

        <div class="students-stat-icon">
            <i class="bi bi-journal-bookmark-fill"></i>
        </div>
    </article>
</section>
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

    <!-- جدول الطلاب --> <section class="students-directory-card">
    <div class="students-directory-header">
        <div>
            <p class="students-table-eyebrow">
                Student Directory
            </p>

            <h2 class="students-table-title">
                Registered Students
            </h2>

            <p class="students-table-description">
                Review and manage all student records.
            </p>
        </div>

        <div class="students-results-badge">
            {{ number_format($students->total()) }} Students
        </div>
    </div>

    @if($students->count())
        <div class="students-cards-list">
            @foreach($students as $student)
                @php
                    $studentInitials = collect(
                        preg_split('/\s+/', trim($student->full_name))
                    )
                        ->filter()
                        ->take(2)
                        ->map(
                            fn ($name) =>
                                strtoupper(mb_substr($name, 0, 1))
                        )
                        ->implode('');
                @endphp

                <article class="student-list-card">
                    <div class="student-card-number">
                        {{ $students->firstItem() + $loop->index }}
                    </div>

                    <div class="student-card-profile">
                        <div class="students-avatar">
                            {{ $studentInitials }}
                        </div>

                        <div class="student-card-identity">
                            <a
                                href="{{ route('students.show', $student) }}"
                                class="students-name"
                            >
                                {{ $student->full_name }}
                            </a>

                            <span class="students-email">
                                {{ $student->email }}
                            </span>
                        </div>
                    </div>

                    <div class="student-card-details">
                        <div class="student-detail-item">
                            <span class="student-detail-label">
                                Student Number
                            </span>

                            <span class="students-number-badge">
                                {{ $student->student_number }}
                            </span>
                        </div>

                        <div class="student-detail-item">
                            <span class="student-detail-label">
                                Major
                            </span>

                            <div class="students-major-cell">
                                <span class="students-major-icon">
                                    <i class="bi bi-book"></i>
                                </span>

                                <span>
                                    {{ $student->major }}
                                </span>
                            </div>
                        </div>

                        <div class="student-detail-item">
                            <span class="student-detail-label">
                                Academic Year
                            </span>

                            <span class="students-year-badge">
                                Year {{ $student->academic_year }}
                            </span>
                        </div>

                        <div class="student-detail-item">
                            <span class="student-detail-label">
                                Status
                            </span>

                            <span
                                class="students-status-badge
                                {{
                                    strtolower($student->status) === 'active'
                                        ? 'students-status-active'
                                        : 'students-status-inactive'
                                }}"
                            >
                                <span class="students-status-dot"></span>

                                {{ $student->status }}
                            </span>
                        </div>
                    </div>

                    <div class="students-actions">
                        <a
                            href="{{ route('students.show', $student) }}"
                            class="students-action-button students-view-button"
                            title="View student"
                        >
                            <i class="bi bi-eye"></i>
                        </a>

                        <a
                            href="{{ route('students.edit', $student) }}"
                            class="students-action-button students-edit-button"
                            title="Edit student"
                        >
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <form
                            method="POST"
                            action="{{ route('students.destroy', $student) }}"
                            onsubmit="return confirm('Delete this student?');"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="students-action-button students-delete-button"
                                title="Delete student"
                            >
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="students-pagination-footer">


            <div class="students-pagination">
                {{ $students->withQueryString()->links() }}
            </div>
        </div>
    @else
        <div class="students-empty-state">
            <div class="students-empty-icon">
                <i class="bi bi-people"></i>
            </div>

            <h3>No students found</h3>

            <p>
                Try changing the search filters or add a new student.
            </p>

            <a
                href="{{ route('students.create') }}"
                class="students-primary-button"
            >
                <i class="bi bi-person-plus-fill"></i>
                Add Student
            </a>
        </div>
    @endif
</section>
                </div>

                </div>



</x-app-layout>
