<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseService
{

    public function courseList(Request $request)
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



    public function courses(Request $request): array
    {
        $keyword = $request->get('keyword');
        $query = Course::query()->with('category');

        if (!blank($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%')
                ->orWhereHas('category', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
        }

        return $query->take(10)->get()->map(function ($item) {
            return [
                'value' => data_get($item, 'id'),
                'label' => data_get($item, 'title'),
            ];
        })->toArray();

    }


    public function getCourseCategory(Request $request): array
    {
        $keyword = $request->get('keyword');
        $query = CourseCategory::query();

        if (!blank($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        return $query->take(10)->get()->map(function ($item) {
            return [
                'value' => data_get($item, 'id'),
                'label' => data_get($item, 'name'),
            ];
        })->toArray();
    }


}
