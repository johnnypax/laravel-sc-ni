<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return Course::with('user')->paginate(5);
    }

    public function store(Request $r)
    {
        $r->validate(['title'=>'required','description'=>'required']);
        return Course::create([
            'title'=>$r->title,
            'description'=>$r->description,
            'user_id'=>auth()->id()
        ]);
    }

    public function update(Request $r, Course $course)
    {
        $this->authorize('update', $course);
        $course->update($r->only('title','description'));
        return $course;
    }

    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();
        return response()->noContent();
    }
}
