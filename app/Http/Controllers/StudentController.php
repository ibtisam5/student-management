<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    $search = trim((string) $request->query('search'));
    $status = $request->query('status', 'all');

    $allowedSorts = [
        'student_number',
        'full_name',
        'email',
        'major',
        'academic_year',
        'status',
    ];

    $sort = $request->query('sort', 'id');
    $direction = $request->query('direction', 'desc');

    if (! in_array($sort, $allowedSorts, true)) {
        $sort = 'id';
    }

    if (! in_array($direction, ['asc', 'desc'], true)) {
        $direction = 'desc';
    }
$totalStudents = Student::count();

$activeStudents = Student::where('status', 'Active')->count();

$inactiveStudents = Student::where('status', 'Inactive')->count();

$totalMajors = Student::query()
    ->distinct()
    ->count('major');
    $students = Student::query()
        ->when($search !== '', function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('student_number', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('major', 'like', "%{$search}%");
            });
        })
        ->when(
            in_array($status, ['Active', 'Inactive'], true),
            function ($query) use ($status) {
                $query->where('status', $status);
            }
        )
        ->orderBy($sort, $direction)
        ->paginate(5)
        ->withQueryString();

   return view('students.index', compact(
    'students',
    'search',
    'status',
    'sort',
    'direction',
    'totalStudents',
    'activeStudents',
    'inactiveStudents',
    'totalMajors'
));
}
public function exportCsv()
{
    $fileName = 'students-' . now()->format('Y-m-d-His') . '.csv';

    return response()->streamDownload(function () {
        $file = fopen('php://output', 'w');

        // لإظهار الإنجليزية والعربية بصورة صحيحة في Excel
        fwrite($file, "\xEF\xBB\xBF");

        fputcsv($file, [
            'Student Number',
            'Full Name',
            'Email',
            'Phone',
            'Major',
            'Academic Year',
            'Status',
        ]);

        Student::query()
            ->orderBy('full_name')
            ->chunk(500, function ($students) use ($file) {
                foreach ($students as $student) {
                    fputcsv($file, [
                        $student->student_number,
                        $student->full_name,
                        $student->email,
                        $student->phone,
                        $student->major,
                        $student->academic_year,
                        $student->status,
                    ]);
                }
            });

        fclose($file);
    }, $fileName, [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('students.create');
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validated = $request->validate([
        'student_number' => 'required|string|max:50|unique:students,student_number',
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:students,email',
        'phone' => 'nullable|string|max:20',
        'major' => 'required|string|max:255',
        'academic_year' => 'required|integer|min:1|max:10',
        'status' => 'required|in:Active,Inactive',
    ]);

    Student::create($validated);

    return redirect()
        ->route('students.index')
        ->with('success', 'Student added successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
{
    return view('students.show', compact('student'));
}

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(Student $student)
{
    return view('students.edit', compact('student'));
}

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Student $student)
{
    $validated = $request->validate([
        'student_number' => 'required|string|max:50|unique:students,student_number,' . $student->id,
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:students,email,' . $student->id,
        'phone' => 'nullable|string|max:20',
        'major' => 'required|string|max:255',
        'academic_year' => 'required|integer|min:1|max:10',
        'status' => 'required|in:Active,Inactive',
    ]);

    $student->update($validated);

    return redirect()
        ->route('students.index')
        ->with('success', 'Student updated successfully.');
}
    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Student $student)
{
    $student->delete();

    return redirect()
        ->route('students.index')
        ->with('success', 'Student deleted successfully.');
}
}
