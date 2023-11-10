<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'travel_id',
        'name',
        'starting_date',
        'ending_date',
        'price',
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function saveMorphedImages($images)
    {
        collect($images)->map(function ($image) {
            $imageName = saveImage($image);

            Image::create([
                'filename' => $imageName,
                'imageable_id' => $this->id,
                'imageable_type' => get_class($this),
            ]);
        });

        return $this;
    }

    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
