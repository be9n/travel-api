<?php

namespace App\Traits;

use App\Models\Image;

trait MorphedImages
{
    public function saveMorphedImages($images, string $relation = 'images')
    {
        collect($images)->map(function ($image) use ($relation) {
            $this->storeImage($image, $relation);
        });

        return $this;
    }

    public function storeImage($image, string $relation)
    {
        $imageName = saveImage($image);

        Image::create([
            'filename' => $imageName,
            'imageable_id' => $this->id,
            'imageable_type' => get_class($this),
            'relation' => $relation,
        ]);

        return $this;
    }
}
