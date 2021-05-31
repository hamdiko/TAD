<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Filters\CourseFilters;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SessionRepository;
use App\Http\Requests\Course\CourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;

class CourseController extends Controller
{

    /**
     * list all available courses.
     *
     * @return \Illuminate\Http\Response
     */
    public function listAll()
    {
        $courses = Course::Select('id', 'name_en', 'name_ar','type')
            ->latest()
            ->get();

        return response()->json(compact('courses'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CourseFilters $filters)
    {
        $courses = Course::Select('id', 'name_en', 'name_ar', 'description', 'created_at as submission_date', 'created_at as submission_time', 'status','type')
            ->latest()
            ->filter($filters)
	        ->take(request('limit', 10))
            ->where('user_id', auth()->id())
            ->get();

        return response()->json(compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CourseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseRequest $request, SessionRepository $sessionRepository)
    {
        $this->authorize('create', Course::class);

        $data = $request->only('name_en', 'name_ar', 'description', 'cost', 'minimum_seats', 'maximum_seats', 'type', 'category_id', 'branch_id');

        $course = auth()->user()->courses()->create($data);

        $course->uploadImages();

        $sessionRepository->saveCourseSessions($course, $request->input('sessions', []));

        $course->load('sessions', 'images');

        return response()->json(compact('course'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        $this->authorize('view', $course);

        $course->load('images');

        $sessions = $course->sessions()->paginate(request('per_page', 20));

        return response()->json(compact('course', 'sessions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);

        $data = $request->only('name_en', 'name_ar', 'description', 'cost', 'type', 'category_id', 'branch_id');

        $course->update($data);
	    $course->uploadImages();
        return response()->json([
            'message' => __('courses.course_updated'),
            'course' => $course
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        $course->delete();

        return response()->json([
            'message' => __('courses.course_deleted')
        ]);
    }
}
