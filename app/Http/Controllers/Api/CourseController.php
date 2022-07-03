<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Services\CourseService;
use Illuminate\Http\Request;

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
        } catch (Exception $e) {
            Log::error($e);
            return ApiResponse::error('Something went wrong, Please try again !!');
        }
    }
}
