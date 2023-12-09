<?php

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;

function saveImage($file)
{
    $file_extension = $file->getClientOriginalExtension();
    $file_name = 'img_' . time() . random_int(1, 9999) . '.' . $file_extension;
    $file->move('uploads/images/', $file_name);

    return $file_name;
}

function saveMultipleImages($files, $item, $modelName)
{
    $data = [];
    foreach ($files as $key => $file) {
        if ($item)
            deleteExistedImage($item->$key, $modelName);

        $file_name = saveImage($file);
        $data[$key] = $file_name;
    }

    return $data;
}

function getImageUrl($image)
{
    return asset('uploads/images/' . $image);
}

function getImagesInfo($images)
{
    if ($images instanceof Collection) {
        return collect($images)->map(function ($image) {
            return [
                'id' => $image->id,
                'filename' => $image->filename,
                'url' => getImageUrl($image->filename),
            ];
        })->toArray();
    }
}

function getImageInfo($image)
{
    return [
        'id' => $image->id,
        'filename' => $image->filename,
        'url' => getImageUrl($image->filename),
    ];
}

function deleteExistedImage($oldImage)
{
    $destination = 'uploads/images/' . $oldImage;

    if (File::exists($destination)) {
        File::delete($destination);
    }
}
