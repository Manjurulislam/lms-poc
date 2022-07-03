<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        JsonResource::withoutWrapping();

        return [
            'id'       => $this->id,
            'title'    => $this->title,
            'slug'     => $this->slug,
            'price'    => $this->price,
            'category' => $this->category->only('id', 'name')
        ];

    }
}
