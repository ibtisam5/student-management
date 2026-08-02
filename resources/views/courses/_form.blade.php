<div class="row g-3">

    <div class="col-md-6">
        <label class="form-label">Course Code</label>
        <input
            type="text"
            name="course_code"
            class="form-control @error('course_code') is-invalid @enderror"
            value="{{ old('course_code', $course->course_code ?? '') }}"
            required
        >

        @error('course_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Course Name</label>
        <input
            type="text"
            name="course_name"
            class="form-control @error('course_name') is-invalid @enderror"
            value="{{ old('course_name', $course->course_name ?? '') }}"
            required
        >

        @error('course_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Credit Hours</label>
        <input
            type="number"
            name="credit_hours"
            min="1"
            max="6"
            class="form-control @error('credit_hours') is-invalid @enderror"
            value="{{ old('credit_hours', $course->credit_hours ?? 3) }}"
            required
        >

        @error('credit_hours')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check mb-2">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                class="form-check-input"
                id="is_active"
                @checked(old('is_active', $course->is_active ?? true))
            >

            <label class="form-check-label" for="is_active">
                Active Course
            </label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea
            name="description"
            rows="4"
            class="form-control @error('description') is-invalid @enderror"
        >{{ old('description', $course->description ?? '') }}</textarea>

        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</div>
