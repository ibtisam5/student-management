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
                            $grade->enrollment_id ?? ''
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
        <label for="assessment_name" class="form-label">
            Assessment Name
        </label>

        <input
            type="text"
            name="assessment_name"
            id="assessment_name"
            maxlength="255"
            class="form-control @error('assessment_name') is-invalid @enderror"
            placeholder="Example: Quiz 1"
            value="{{ old(
                'assessment_name',
                $grade->assessment_name ?? ''
            ) }}"
            required
        >

        @error('assessment_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="assessment_type" class="form-label">
            Assessment Type
        </label>

        <select
            name="assessment_type"
            id="assessment_type"
            class="form-select @error('assessment_type') is-invalid @enderror"
            required
        >
            @foreach([
                'Quiz',
                'Assignment',
                'Midterm',
                'Final',
                'Project',
                'Other'
            ] as $type)
                <option
                    value="{{ $type }}"
                    @selected(
                        old(
                            'assessment_type',
                            $grade->assessment_type ?? 'Quiz'
                        ) === $type
                    )
                >
                    {{ $type }}
                </option>
            @endforeach
        </select>

        @error('assessment_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="score" class="form-label">
            Score
        </label>

        <input
            type="number"
            name="score"
            id="score"
            min="0"
            step="0.01"
            class="form-control @error('score') is-invalid @enderror"
            value="{{ old('score', $grade->score ?? '') }}"
            required
        >

        @error('score')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="maximum_score" class="form-label">
            Maximum Score
        </label>

        <input
            type="number"
            name="maximum_score"
            id="maximum_score"
            min="0.01"
            max="1000"
            step="0.01"
            class="form-control @error('maximum_score') is-invalid @enderror"
            value="{{ old(
                'maximum_score',
                $grade->maximum_score ?? 100
            ) }}"
            required
        >

        @error('maximum_score')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="weight" class="form-label">
            Weight (%)
        </label>

        <input
            type="number"
            name="weight"
            id="weight"
            min="0"
            max="100"
            step="0.01"
            class="form-control @error('weight') is-invalid @enderror"
            value="{{ old('weight', $grade->weight ?? 0) }}"
            required
        >

        @error('weight')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="assessment_date" class="form-label">
            Assessment Date
        </label>

        <input
            type="date"
            name="assessment_date"
            id="assessment_date"
            max="{{ now()->format('Y-m-d') }}"
            class="form-control @error('assessment_date') is-invalid @enderror"
            value="{{ old(
                'assessment_date',
                isset($grade) && $grade->assessment_date
                    ? $grade->assessment_date->format('Y-m-d')
                    : now()->format('Y-m-d')
            ) }}"
        >

        @error('assessment_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">
            Calculated Percentage
        </label>

        <div class="form-control bg-light" id="percentagePreview">
            Enter the score and maximum score
        </div>
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
            placeholder="Optional notes..."
        >{{ old('notes', $grade->notes ?? '') }}</textarea>

        @error('notes')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const scoreInput = document.getElementById('score');
        const maximumInput = document.getElementById('maximum_score');
        const preview = document.getElementById('percentagePreview');

        function updatePercentage() {
            const score = Number.parseFloat(scoreInput.value);
            const maximum = Number.parseFloat(maximumInput.value);

            if (
                Number.isNaN(score) ||
                Number.isNaN(maximum) ||
                maximum <= 0
            ) {
                preview.textContent =
                    'Enter the score and maximum score';

                return;
            }

            const percentage = (score / maximum) * 100;

            preview.textContent =
                `${percentage.toFixed(2)}%`;
        }

        scoreInput.addEventListener('input', updatePercentage);
        maximumInput.addEventListener('input', updatePercentage);

        updatePercentage();
    });
</script>
