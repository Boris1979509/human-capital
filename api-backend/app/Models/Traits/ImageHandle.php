<?php

namespace App\Models\Traits;

use App\Helpers\ImageHelper;
use App\Jobs\ImageResize;
use Illuminate\Database\Eloquent\Model;

trait ImageHandle
{
    public static function bootImageHandle()
    {
        self::saving(function (Model $model) {

            // Уменьшаем регистр (JPG) расширения
            if ($model->image) {
                $model->image = ImageHelper::extensionToLower($model->image);
            }
            if ($model->file) {
                $model->file = ImageHelper::extensionToLower($model->file);
            }
        });

        self::saved(function (Model $model) {

            // Сохраняем и делаем превьюшки для "Изображения"
            if ($model->isDirty('image')) {
                $newImage = $model->image;
                $oldImage = $model->getOriginal('image');
                if ($newImage != $oldImage) {
                    if ($newImage) {
                        dispatch((new ImageResize($newImage)));
                    }
                    if ($oldImage) {
                        ImageHelper::deleteImages($oldImage);
                    }
                }
            }

            // Сохраняем и делаем превьюшки для "Изображения"
            if ($model->isDirty('file')) {
                $newImage = $model->file;
                $oldImage = $model->getOriginal('file');
                if ($newImage != $oldImage) {
                    if ($newImage) {
                        dispatch((new ImageResize($newImage)));
                    }
                    if ($oldImage) {
                        ImageHelper::deleteImages($oldImage);
                    }
                }
            }

            if ($model->isDirty('files')) {
                $newImages = $model->images;
                $oldImages = json_decode($model->getOriginal('files'), true);

                if (!$oldImages || (count($newImages) >= count($oldImages))) {
                    $diff = $oldImages?array_diff($newImages, $oldImages):$newImages;

                    if ($diff) {
                        foreach ($diff as $line) {
                            dispatch((new ImageResize($newImage)));
                        }
                    }
                } elseif (count($oldImages) > count($newImages)) {
                    $diff = array_diff($oldImages, $newImages);

                    if ($diff) {
                        foreach ($diff as $line) {
                            ImageHelper::deleteImages($line);
                        }
                    }
                }
            }
        });
    }
}
