<div class="row g-3">

    <div class="col-12">
        <label for="enrollment_id" class="form-label">
            Student and Course
        </label>

        <select
            name="enrollment_id"
            id="enrollment_id"
            class="form-select @error('enrollment_id') is-invalid @enderror"
            required
        >
            <option value="">Select Enrollment</option>

            @foreach($enrollments as $enrollment)
                <option
                    value="{{ $enrollment->id }}"
                    @selected(
                        old(
                            'enrollment_id',
                            $attendance->enrollment_id ?? ''
                        ) == $enrollment->id
                    )
                >
                    {{ $enrollment->student->student_number }}
                    — {{ $enrollment->student->full_name }}
                    | {{ $enrollment->course->course_code }}
                    — {{ $enrollment->course->course_name }}
                </option>
            @endforeach
        </select>

        @error('enrollment_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="attendance_date" class="form-label">
            Attendance Date
        </label>

        <input
            type="date"
            name="attendance_date"
            id="attendance_date"
            max="{{ now()->format('Y-m-d') }}"
            class="form-control @error('attendance_date') is-invalid @enderror"
            value="{{ old(
                'attendance_date',
                isset($attendance)
                    ? $attendance->attendance_date->format('Y-m-d')
                    : now()->format('Y-m-d')
            ) }}"
            required
        >

        @error('attendance_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">
            Status
        </label>

        <select
            name="status"
            id="status"
            class="form-select @error('status') is-invalid @enderror"
            required
        >
            @foreach([
                'Present',
                'Absent',
                'Late',
                'Excused'
            ] as $status)
                <option
                    value="{{ $status }}"
                    @selected(
                        old(
                            'status',
                            $attendance->status ?? 'Present'
                        ) === $status
                    )
                >
                    {{ $status }}
                </option>
            @endforeach
        </select>

        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12">
        <label for="notes" class="form-label">
            Notes
        </label>

        <textarea
            name="notes"
            id="notes"
            rows="4"
            maxlength="1000"
            class="form-control @error('notes') is-invalid @enderror"
            placeholder="Optional attendance notes..."
        >{{ old('notes', $attendance->notes ?? '') }}</textarea>

        @error('notes')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>
