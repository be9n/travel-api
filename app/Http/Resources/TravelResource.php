<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'number_of_days' => $this->number_of_days,
            'number_of_nights' => $this->number_of_nights,
            'images' => getImagesInfo($this->images),
            'cover' => $this->when($this->cover, fn() => getImageInfo($this->cover)),

            'tours' => $this->tours->map(function ($tour) {
                return [
                    'id' => $tour->id,
                    'name' => $tour->name,
                    'starting_date' => $tour->starting_date,
                    'ending_date' => $tour->ending_date,
                    'price' => $tour->price,
                ];
            }),
        ];
    }
}
