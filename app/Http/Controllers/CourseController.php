<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Course::query();

    if ($request->filled('search')) {
        $search = $request->string('search')->trim()->toString();

        $query->where(function ($courseQuery) use ($search) {
            $courseQuery
                ->where('course_name', 'like', "%{$search}%")
                ->orWhere('course_code', 'like', "%{$search}%");
        });
    }

    $courses = $query
        ->orderBy('course_code')
        ->paginate(10)
        ->withQueryString();

    return view('courses.index', compact('courses'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code'  => 'required|unique:courses,course_code',
            'course_name'  => 'required|max:255',
            'description'  => 'nullable',
            'credit_hours' => 'required|integer|min:1|max:6',
            'is_active'    => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Course::create($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return view('courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_code' => 'required|unique:courses,course_code,' . $course->id,
            'course_name' => 'required|max:255',
            'description' => 'nullable',
            'credit_hours' => 'required|integer|min:1|max:6',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $course->update($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
