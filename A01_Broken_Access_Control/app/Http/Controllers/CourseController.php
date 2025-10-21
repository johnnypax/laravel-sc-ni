<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    public function edit(Course $course)
    {
//        $course = Course::findOrFail($id);
        $this->authorize('view', $course);
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

//        $course = Course::findOrFail($id);
        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Corso aggiornato!');
    }
}
