<?php

namespace App\Models;

// use Cviebrock\EloquentSluggable\Sluggable;

use App\Traits\MorphedImages;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Str;

class Travel extends Model
{
    use HasFactory, HasSlug, HasUuids, SearchableTrait, MorphedImages;

    protected $table = 'travels';

    protected $fillable = [
        'is_public',
        'slug',
        'name',
        'description',
        'number_of_days',
    ];

    // protected $searchable = [
    //     'name',
    //     'description',
    //     'tours.name',
    // ];

    protected $searchable = [
        'columns' => [
            'travels.name' => 10,
            'travels.description' => 3,
            'tours.name' => 8,
            'images.filename' => 5,
        ],
        'joins' => [
            'tours' => ['travels.id','tours.travel_id'],
            'images' => ['tours.id','images.imageable_id'],
        ],
    ];

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable')->where('relation', 'images');
    }

    public function cover()
    {
        return $this->morphOne(Image::class, 'imageable')->where('relation', 'cover');
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

    // public function getNumberOfNightsAttribute(){
    //     return $this->number_of_days - 1;
    // }

    public function scopePublic(Builder $query)
    {
        $query->where('is_public', true);
    }

    public function scopePrivate(Builder $builder)
    {
        $builder->where('is_public', false);
    }
}
