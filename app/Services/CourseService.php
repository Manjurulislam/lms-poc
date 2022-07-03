<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseService
{
    public function getCourses(Request $request)
    {
        $keyword = $request->get('keyword');
        $query   = Course::query()->with('category');

        if (!blank($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%')
                ->orWhereHas('category', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
        }
        return $query->latest()->paginate($request->get('rows', 20));
    }
}
