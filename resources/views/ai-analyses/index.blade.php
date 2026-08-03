<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>AI Student Analyses</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            background: #f4f7fb;
        }

        .page-card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 8px 28px rgba(29, 48, 85, 0.08);
        }

        .stat-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(29, 48, 85, 0.07);
        }

        .analysis-text {
            max-width: 420px;
        }
    </style>
</head>

<body>

<div class="container-fluid px-4 px-lg-5 py-5">

    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">

        <div>
            <p class="text-primary fw-semibold mb-1">
                INTELLIGENT ACADEMIC ANALYTICS
            </p>

            <h1 class="fw-bold mb-1">
                AI Student Analyses
            </h1>

            <p class="text-muted mb-0">
                Generate and review intelligent student reports
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <a href="{{ route('dashboard') }}"
               class="btn btn-outline-secondary">
                Dashboard
            </a>

            <a href="{{ route('students.index') }}"
               class="btn btn-outline-secondary">
                Students
            </a>

            <a href="{{ route('ai-analyses.create') }}"
               class="btn btn-primary">
                + New Analysis
            </a>

        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Total Analyses
                    </p>

                    <h2 class="fw-bold mb-0">
                        {{ $totalAnalyses }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Analyzed Students
                    </p>

                    <h2 class="fw-bold text-primary mb-0">
                        {{ $analyzedStudents }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body p-4">
                    <p class="text-muted mb-2">
                        Latest Analysis
                    </p>

                    <h5 class="fw-bold mb-1">
                        {{ $latestAnalysis?->student?->full_name
                            ?? 'No analyses yet' }}
                    </h5>

                    <small class="text-muted">
                        {{ $latestAnalysis
                            ? $latestAnalysis->created_at->diffForHumans()
                            : 'Create the first analysis' }}
                    </small>
                </div>
            </div>
        </div>

    </div>

    <form
        method="GET"
        action="{{ route('ai-analyses.index') }}"
        class="card page-card mb-4"
    >
        <div class="card-body p-3">

            <div class="row g-2">

                <div class="col-lg-7">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search student number, name or major..."
                        value="{{ request('search') }}"
                    >
                </div>

                <div class="col-lg-3">
                    <select
                        name="analysis_type"
                        class="form-select"
                    >
                        <option value="">All Analysis Types</option>

                        @foreach([
                            'Academic Performance',
                            'Attendance Risk',
                            'Comprehensive Analysis'
                        ] as $type)
                            <option
                                value="{{ $type }}"
                                @selected(
                                    request('analysis_type') === $type
                                )
                            >
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-1">
                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        Search
                    </button>
                </div>

                <div class="col-lg-1">
                    <a
                        href="{{ route('ai-analyses.index') }}"
                        class="btn btn-outline-secondary w-100"
                    >
                        Clear
                    </a>
                </div>

            </div>

        </div>
    </form>

    <div class="card page-card">
        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Student</th>
                        <th>Analysis Type</th>
                        <th>Analysis</th>
                        <th>Provider</th>
                        <th>Created</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                    </thead>

                    <tbody>

                    @forelse($analyses as $analysis)

                        <tr>
                            <td class="ps-4">
                                {{ $analyses->firstItem()
                                    + $loop->index }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $analysis->student->full_name }}
                                </div>

                                <small class="text-muted">
                                    {{ $analysis->student->student_number }}
                                    · {{ $analysis->student->major }}
                                </small>
                            </td>

                            <td>
                                <span class="badge text-bg-primary">
                                    {{ $analysis->analysis_type }}
                                </span>
                            </td>

                            <td class="analysis-text">
                                {{ \Illuminate\Support\Str::limit(
                                    $analysis->analysis,
                                    100
                                ) }}
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $analysis->provider }}
                                </div>

                                <small class="text-muted">
                                    {{ $analysis->model }}
                                </small>
                            </td>

                            <td>
                                {{ $analysis->created_at
                                    ->format('d M Y') }}

                                <br>

                                <small class="text-muted">
                                    {{ $analysis->created_at
                                        ->format('h:i A') }}
                                </small>
                            </td>

                            <td class="text-end pe-4">

                                <a
                                    href="{{ route(
                                        'ai-analyses.show',
                                        $analysis
                                    ) }}"
                                    class="btn btn-outline-primary btn-sm"
                                >
                                    View
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route(
                                        'ai-analyses.destroy',
                                        $analysis
                                    ) }}"
                                    class="d-inline"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm(
                                            'Delete this analysis?'
                                        )"
                                    >
                                        Delete
                                    </button>
                                </form>

                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="7"
                                class="text-center py-5"
                            >
                                <h5 class="mb-2">
                                    No analyses found
                                </h5>

                                <p class="text-muted mb-3">
                                    Generate the first intelligent
                                    student report.
                                </p>

                                <a
                                    href="{{ route(
                                        'ai-analyses.create'
                                    ) }}"
                                    class="btn btn-primary"
                                >
                                    New Analysis
                                </a>
                            </td>
                        </tr>

                    @endforelse

                    </tbody>
                </table>

            </div>

        </div>
    </div>

    <div class="mt-4">
        {{ $analyses->links() }}
    </div>

</div>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>
