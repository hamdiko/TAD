<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use App\Filters\CourseFilters;

class CourseSubjectController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CourseFilters $filters)
    {

        $data = $this->buildQuery(Subject::query(), $filters)
            ->union($this->buildQuery(Course::query(), $filters))
            ->latest()
            ->paginate(request('per_page', 20));

        return response()->json($data);
    }


    private function buildQuery($query, $filters)
    {
        return $query->select('id', 'name_en', 'name_ar', 'description', 'created_at', 'status', 'type', 'object_type', 'user_id')
            ->where('user_id', auth()->id())
            ->filter($filters);
    }
}
