<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
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
            $query = $this->courseService->courseList($request);
            return CourseResource::collection($query);
        } catch (\Exception $e) {
            Log::error('Exception', [$e->getMessage()]);
            return ApiResponse::error('Something went wrong, Please try again !!');
        }
    }

    public function details($courseId)
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

    public function applyCoupon($courseId, Request $request)
    {
        if (blank($course = Course::find($courseId))) {
            return ApiResponse::error('Course not found');
        }

        [$validCoupon, $responseData, $errorMessage] = $this->courseService->validateCoupon($request, $course);

        if ($validCoupon) {
            return ApiResponse::success($responseData, 'Coupon applied');
        } else {
            return ApiResponse::error($errorMessage);
        }

    }

    public function getCourses(Request $request): JsonResponse
    {
        try {
            return ApiResponse::success($this->courseService->courses($request));
        } catch (\Exception $e) {
            Log::error('Exception', [$e->getMessage()]);
            return ApiResponse::error('Something went wrong, Please try again !!');
        }
    }

    public function getCategories(Request $request): JsonResponse
    {
        try {
            return ApiResponse::success($this->courseService->getCourseCategory($request));
        } catch (\Exception $e) {
            Log::error('Exception', [$e->getMessage()]);
            return ApiResponse::error('Something went wrong, Please try again !!');
        }
    }

}
