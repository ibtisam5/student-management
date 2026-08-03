<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Generate Student Analysis</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f4f7fb;
        }

        .analysis-card {
            border: 0;
            border-radius: 20px;
            box-shadow: 0 12px 36px rgba(29, 48, 85, 0.09);
        }

        .ai-icon {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e8f1ff;
            font-size: 34px;
        }
    </style>
</head>

<body>

<div class="container py-5" style="max-width: 950px;">

    <div class="d-flex justify-content-between
                align-items-center mb-4">

        <div>
            <h1 class="fw-bold mb-1">
                Generate Student Analysis
            </h1>

            <p class="text-muted mb-0">
                Analyze academic performance and attendance
            </p>
        </div>

        <a
            href="{{ route('ai-analyses.index') }}"
            class="btn btn-outline-secondary"
        >
            Back
        </a>
    </div>

    <div class="card analysis-card">
        <div class="card-body p-4 p-md-5">

            <div class="d-flex align-items-center gap-3 mb-4">

                <div class="ai-icon">
                    🤖
                </div>

                <div>
                    <h4 class="fw-bold mb-1">
                        Intelligent Analysis Engine
                    </h4>

                    <p class="text-muted mb-0">
                        The system will analyze grades,
                        attendance and academic risk.
                    </p>
                </div>

            </div>

            <form
                method="POST"
                action="{{ route('ai-analyses.store') }}"
            >
                @csrf

                <div class="mb-4">
                    <label
                        for="student_id"
                        class="form-label fw-semibold"
                    >
                        Student
                    </label>

                    <select
                        name="student_id"
                        id="student_id"
                        class="form-select form-select-lg
                               @error('student_id') is-invalid @enderror"
                        required
                    >
                        <option value="">
                            Select Student
                        </option>

                        @foreach($students as $student)
                            <option
                                value="{{ $student->id }}"
                                @selected(
                                    old(
                                        'student_id',
                                        $selectedStudentId
                                    ) == $student->id
                                )
                            >
                                {{ $student->student_number }}
                                — {{ $student->full_name }}
                                — {{ $student->major }}
                            </option>
                        @endforeach
                    </select>

                    @error('student_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label
                        for="analysis_type"
                        class="form-label fw-semibold"
                    >
                        Analysis Type
                    </label>

                    <select
                        name="analysis_type"
                        id="analysis_type"
                        class="form-select form-select-lg
                               @error('analysis_type') is-invalid @enderror"
                        required
                    >
                        @foreach([
                            'Comprehensive Analysis',
                            'Academic Performance',
                            'Attendance Risk'
                        ] as $type)
                            <option
                                value="{{ $type }}"
                                @selected(
                                    old(
                                        'analysis_type',
                                        'Comprehensive Analysis'
                                    ) === $type
                                )
                            >
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>

                    @error('analysis_type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="alert alert-primary border-0">
                    <strong>Analysis includes:</strong>

                    <ul class="mb-0 mt-2">
                        <li>Average academic grade</li>
                        <li>Attendance rate</li>
                        <li>Academic risk level</li>
                        <li>Performance observations</li>
                        <li>Personalized recommendations</li>
                    </ul>
                </div>

                <div class="d-flex gap-2 mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary btn-lg"
                    >
                        Generate Analysis
                    </button>

                    <a
                        href="{{ route('ai-analyses.index') }}"
                        class="btn btn-light btn-lg"
                    >
                        Cancel
                    </a>

                </div>
            </form>

        </div>
    </div>

</div>

</body>
</html>
