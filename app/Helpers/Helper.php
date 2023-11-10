<?php

use App\Models\Image;

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

function getImagesNames($images)
{
    return collect($images)->map(fn ($image) => $image->only('id', 'filename'))->toArray();
}

function deleteExistedImage($oldImage)
{
    $destination = 'uploads/images/' . $oldImage;

    if (File::exists($destination)) {
        File::delete($destination);
    }
}
