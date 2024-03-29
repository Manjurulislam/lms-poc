<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Manjurulislam\Coupon\Facades\Coupon;


class CourseService
{

    public function courseList(Request $request)
    {
        $keyword = $request->get('keyword');
        $row     = $request->get('rows', 20);
        $query   = Course::with('category');

        if (!blank($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%')
                ->orWhereHas('category', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
        }
        return $query->latest()->paginate($row);
    }


    public function courses(Request $request): array
    {
        $keyword = $request->get('keyword');
        $query   = Course::with('category');

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
        $query   = CourseCategory::query();

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


    public function validateCoupon(Request $request, Course $course): array
    {
        if (blank($couponCode = $request->get('coupon_code'))) {
            return [false, [], 'Coupon code is required'];
        }

        if (blank($coupon = Coupon::getCouponByCode($couponCode))) {
            return [false, [], 'Invalid Coupon Code'];
        }

        if ($coupon->expire_date < Carbon::now()) {
            return [false, [], 'Coupon is already expired.'];
        }

        $validProduct = Coupon::applyCouponToProduct($coupon, $course->id);

        if (!$validProduct) {
            return [false, [], 'Coupon is not valid for this product'];
        } else {
            return [true, $validProduct, 'Coupon is already expired.'];
        }
    }

}
