<?php

namespace App\Traits;

trait ImageUploadTrait
{
    public $imagesUploadPath = 'uploads/images/';

    public function saveImage($file)
    {
        $file_extension = $file->getClientOriginalExtension();

        $file_name = 'img_' . random_int(1, 9999) . time() . '.' . $file_extension;

        $file->move($this->imagesUploadPath, $file_name);

        return $file_name;
    }

    public function saveMultipleImages($images, $modelPath, $foreign_key, $obj_id)
    {
        foreach ($images as $image) {
            $imageName = $this->saveImage($image);

            $modelPath::create([
                'name' => $imageName,
                $foreign_key => $obj_id,
            ]);
        }
    }

    public function deleteExistedImage($oldImage)
    {
        $destination = $this->imagesUploadPath . $oldImage;

        if (File::exists($destination)) {
            File::delete($destination);
        }
    }
}
