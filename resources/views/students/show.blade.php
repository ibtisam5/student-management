<x-app-layout>

    <div class="py-8">
        <div class="container page-container">

            <div class="mb-4">
                <a href="{{ route('students.index') }}"
                   class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Students
                </a>
            </div>

            <section class="student-show-hero">

                <div class="student-show-avatar">
                    {{
                        collect(preg_split('/\s+/', trim($student->full_name)))
                            ->take(2)
                            ->map(fn($name) => strtoupper(mb_substr($name,0,1)))
                            ->implode('')
                    }}
                </div>

                <div class="student-show-info">

                    <p class="student-show-eyebrow">
                        STUDENT PROFILE
                    </p>

                    <h1 class="student-show-name">
                        {{ $student->full_name }}
                    </h1>

                    <p class="student-show-email">
                        {{ $student->email }}
                    </p>

                    <div class="student-show-meta">

                        <span class="student-show-chip">
                            <i class="bi bi-person-badge"></i>
                            {{ $student->student_number }}
                        </span>

                        <span class="student-show-chip">
                            <i class="bi bi-book"></i>
                            {{ $student->major }}
                        </span>

                        <span class="student-show-chip">
                            <i class="bi bi-mortarboard"></i>
                            Year {{ $student->academic_year }}
                        </span>

                    </div>

                </div>

                <div class="student-show-status">

                    <span class="students-status-badge {{ strtolower($student->status)=='active'
                        ? 'students-status-active'
                        : 'students-status-inactive' }}">

                        <span class="students-status-dot"></span>

                        {{ $student->status }}

                    </span>

                    <a href="{{ route('students.edit',$student) }}"
                       class="students-primary-button mt-3">

                        <i class="bi bi-pencil-square"></i>

                        Edit Student

                    </a>

                </div>

            </section>
<div class="student-stats-grid mt-4">

    <div class="student-stat-card student-stat-blue">
        <div>
            <p class="student-stat-label">Academic Year</p>
            <h2 class="student-stat-value">
                {{ $student->academic_year }}
            </h2>
            <p class="student-stat-note">
                Current study level
            </p>
        </div>

        <div class="student-stat-icon">
            <i class="bi bi-mortarboard"></i>
        </div>
    </div>

   <div class="student-stat-card student-stat-green">
    <div>
        <p class="student-stat-label">Status</p>

        <h2 class="student-stat-value">
            {{ $student->status }}
        </h2>

        <p class="student-stat-note">
            Current student status
        </p>
    </div>

    <div class="student-stat-icon">
        <i class="bi bi-patch-check"></i>
    </div>
</div>

    <div class="student-stat-card student-stat-purple">
    <div>
        <p class="student-stat-label">Major</p>

        <h2 class="student-stat-value">
            {{ $student->major }}
        </h2>

        <p class="student-stat-note">
            Program
        </p>
    </div>

    <div class="student-stat-icon">
        <i class="bi bi-book"></i>
    </div>
</div>

    <div class="student-stat-card student-stat-gray">
        <div>
            <p class="student-stat-label">Student ID</p>
            <h2 class="student-stat-value">
                {{ substr($student->student_number,-4) }}
            </h2>
            <p class="student-stat-note">
                Last four digits
            </p>
        </div>

        <div class="student-stat-icon">
            <i class="bi bi-person-badge"></i>
        </div>
    </div>

</div>
        </div>
    </div>

</x-app-layout>
