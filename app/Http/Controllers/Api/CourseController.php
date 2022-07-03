<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    public CourseService $courseService;

    public function __construct()
    {
        $this->courseService = new CourseService();
    }


    public function index(Request $request)
    {
        try {
            $query = $this->courseService->getCourses($request);
            return CourseResource::collection($query);
        } catch (\Exception $e) {
            Log::error('Exception', [$e->getMessage()]);
            return ApiResponse::error('Something went wrong, Please try again !!');
        }
    }

    public function getCourseDetails($courseId)
    {
        if (blank($course = Course::find($courseId))) {
            return ApiResponse::error('Course not found');
        }

        try {
            return CourseResource::collection($course);
        } catch (\Exception $e) {
            Log::error('Exception', [$e->getMessage()]);
            return ApiResponse::error('Something went wrong, Please try again !!');
        }
    }

}
