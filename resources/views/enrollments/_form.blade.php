<div class="row g-3">

    <div class="col-md-6">
        <label for="student_id" class="form-label">
            Student
        </label>

        <select
            name="student_id"
            id="student_id"
            class="form-select @error('student_id') is-invalid @enderror"
            required
        >
            <option value="">Select Student</option>

            @foreach($students as $student)
                <option
                    value="{{ $student->id }}"
                    @selected(
                        old(
                            'student_id',
                            $enrollment->student_id ?? ''
                        ) == $student->id
                    )
                >
                    {{ $student->student_number }}
                    — {{ $student->full_name }}
                </option>
            @endforeach
        </select>

        @error('student_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="course_id" class="form-label">
            Course
        </label>

        <select
            name="course_id"
            id="course_id"
            class="form-select @error('course_id') is-invalid @enderror"
            required
        >
            <option value="">Select Course</option>

            @foreach($courses as $course)
                <option
                    value="{{ $course->id }}"
                    @selected(
                        old(
                            'course_id',
                            $enrollment->course_id ?? ''
                        ) == $course->id
                    )
                >
                    {{ $course->course_code }}
                    — {{ $course->course_name }}
                </option>
            @endforeach
        </select>

        @error('course_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="semester" class="form-label">
            Semester
        </label>

        <select
            name="semester"
            id="semester"
            class="form-select @error('semester') is-invalid @enderror"
            required
        >
            @foreach(['Fall', 'Spring', 'Summer'] as $semester)
                <option
                    value="{{ $semester }}"
                    @selected(
                        old(
                            'semester',
                            $enrollment->semester ?? 'Fall'
                        ) === $semester
                    )
                >
                    {{ $semester }}
                </option>
            @endforeach
        </select>

        @error('semester')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="academic_year" class="form-label">
            Academic Year
        </label>

        <input
            type="number"
            name="academic_year"
            id="academic_year"
            min="2020"
            max="2100"
            class="form-control @error('academic_year') is-invalid @enderror"
            value="{{ old(
                'academic_year',
                $enrollment->academic_year ?? 2026
            ) }}"
            required
        >

        @error('academic_year')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="enrolled_at" class="form-label">
            Enrollment Date
        </label>

        <input
            type="date"
            name="enrolled_at"
            id="enrolled_at"
            class="form-control @error('enrolled_at') is-invalid @enderror"
            value="{{ old(
                'enrolled_at',
                isset($enrollment) && $enrollment->enrolled_at
                    ? $enrollment->enrolled_at->format('Y-m-d')
                    : now()->format('Y-m-d')
            ) }}"
        >

        @error('enrolled_at')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>
