<?php

namespace App\Models;

// use Cviebrock\EloquentSluggable\Sluggable;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Travel extends Model
{
    use HasFactory, HasSlug, HasUuids;

    protected $table = 'travels';

    protected $fillable = [
        'is_public',
        'slug',
        'name',
        'description',
        'number_of_days',
    ];

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function numberOfNights(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['number_of_days'] - 1
        );
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

    // public function getNumberOfNightsAttribute(){
    //     return $this->number_of_days - 1;
    // }
}
