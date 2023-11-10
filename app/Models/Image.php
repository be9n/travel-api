<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['imageable_id', 'imageable_type', 'filename'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($model) {
            deleteExistedImage($model->filename);
        });
    }

    public function imageable()
    {
        return $this->morphTo();
    }
}
