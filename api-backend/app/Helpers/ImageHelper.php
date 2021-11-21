<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Config as Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;

class ImageHelper
{
    const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];
    const PREVIEW_SIZES = [
        'small' => 320,
        'medium' => 768,
        'large' => 920,
        'extra' => 1420
    ];
    const PREVIEW_QUALITY = 50;

    /**
     * @param string $url
     * @param bool $removeDomain
     * @return array
     */
    public static function clearPathFromUrl(string $url, bool $removeDomain = true): array
    {
        // Убираем домен
        if ($removeDomain) {
            $url = preg_replace('/(.*\.[a-z]{2,3}\/)/', '', $url);
        }
        // Убираем всякую шляпу после ?
        $url = preg_replace('/\?.+/', '', $url);

        $path = [];
        $url = (explode('/', $url));
        $path['file'] = array_pop($url);
        $path['path'] = implode("/", $url);

        return $path;
    }

    /**
     * @param string $file
     * @return bool
     */
    public static function createPreview(string $file): bool
    {
        // Проверяем расширение файла
        $exp = explode('.', $file);
        if (!in_array(end($exp), self::ALLOWED_IMAGE_EXTENSIONS)) {
            return true;
        }

        // Делим путь на массив ($path['path'] = путь к файлу, $path['file'] = сам файл
        $path = self::clearPathFromUrl($file);

        // Генерим ПОЛНЫЙ путь к картинке
        $path['path'] = Config::get('app.path.storage') . DIRECTORY_SEPARATOR . $path['path'];

        // Путь к оригинальной картинке
        $imageOriginal = $path['path'] . DIRECTORY_SEPARATOR . $path['file'];

        if (!file_exists($imageOriginal)) {
            dump('[ImageHelper::createPreview] File "' . $imageOriginal . '" not found!');
            return false;
        }

        foreach (self::PREVIEW_SIZES as $size => $width) {

            // Путь к будущей превьюшке
            $thumbnailPath = $path['path'] . DIRECTORY_SEPARATOR . $size . '.' . $path['file'];

            // Ресайзим по ширине (пропорционально)
            Image::make($imageOriginal)
                ->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($thumbnailPath, self::PREVIEW_QUALITY);
        }

        return true;
    }

    public static function extensionToLower(string $file): string
    {
        // Проверяем регистр расширения файла (JPG)
        $fileName = explode('.', $file);
        $ext = array_pop($fileName);
        if (ctype_upper($ext)) {
            array_push($fileName, strtolower($ext));
            $newFile = implode('.', $fileName);
            rename(public_path(Config::get('app.path.images') . DIRECTORY_SEPARATOR . $file), public_path(Config::get('app.path.images') . DIRECTORY_SEPARATOR . $newFile));
        }

        return $newFile ?? $file;
    }

    /**
     * @param string $url
     * @return bool
     */
    public static function deleteImages(string $url): bool
    {
        $path = self::clearPathFromUrl($url);

        // Генерим ПОЛНЫЙ путь к картинке
        $path['path'] = Config::get('app.path.storage') . DIRECTORY_SEPARATOR . $path['path'];

        foreach (self::PREVIEW_SIZES as $size => $width) {

            // Путь к превьюшке
            $thumbnailPath = $path['path'] . DIRECTORY_SEPARATOR . $size . '.' . $path['file'];

            // Удаляем превьюшку
            if (is_file($thumbnailPath)) {
                unlink($thumbnailPath);
            }
        }

        // Удаляем оригинал
        $imageOriginal = $path['path'] . DIRECTORY_SEPARATOR . $path['file'];

        if (is_file($imageOriginal)) {
            unlink($imageOriginal);
        }

        return true;
    }

    /**
     * @param string|null $image
     * @param string $size
     * @return string
     */
    public static function getMainImage(string $image = null, string $size = 'medium'): string
    {
        if ($image) {
            if (strpos($image, 'plug-')) {
                return ($image[0]!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$image;
            }
            $path = ImageHelper::clearPathFromUrl($image);
            $image = DIRECTORY_SEPARATOR . Config::get('app.path.images') . DIRECTORY_SEPARATOR . $path['path'] . DIRECTORY_SEPARATOR . $size . '.' . $path['file'];
        } else {
            $image = Config::get('app.path.image_default');
        }

        return $image ?: '';
    }

    /**
     * @param $images
     * @param string $imagesPath
     * @return array
     */
    public static function getImagesAttribute($images, string $imagesPath)
    {
        $newImages = [];
        if (is_array($images)) {
            foreach ($images as $image) {
                if (!strpos($image, 'plug-')) {
                    $image = str_replace('"', '', $image);
                    $image = str_replace('\\/', "/", $image);

                    // Удаляем старые пути, которые сохранились вместе с http:// и storage/
                    $image = preg_replace("/.*" . Config::get('app.path.images') . "\//", '', $image);
                } else {
                    $image = ($image[0]!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$image;
                }
                $newImages[] = $image;
            }
        }
        return $newImages;
    }


    /**
     * @param string $image
     * @param string $size
     * @return string
     */
    public static function rightPath(string $image = null, string $size = 'extra'): string
    {
        if (!$image) {
            return Config::get('app.path.image_default');
        }

        if (strpos($image, 'plug-')) {
            return ($image[0]!=DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').$image;
        }

        $path = self::clearPathFromUrl($image);

        $path = DIRECTORY_SEPARATOR . Config::get('app.path.images') . DIRECTORY_SEPARATOR . $path['path'] . DIRECTORY_SEPARATOR . $size . '.' . $path['file'];

        return $path;
    }

    /**
     * @return string
     */
    public static function previewRotateButtons()
    {
        return  '<button type="button" class="kv-rotate-btn btn btn-sm btn-default btn-outline-secondary" title="Rotate" data-file="{caption}" data-rotate="90"><i class="fa fa-rotate-left"></i></button> ' .
                '<button type="button" class="kv-rotate-btn btn btn-sm btn-default btn-outline-secondary" title="Rotate" data-file="{caption}" data-rotate="-90"><i class="fa fa-rotate-right"></i></button>';
    }

    public static function fullPathWithPrefix(string $image, string $size = 'extra') : string
    {
        if (request('resolution') && array_key_exists(request('resolution'), self::PREVIEW_SIZES)) {
            $size = request('resolution');
        }
        $pieces = explode(DIRECTORY_SEPARATOR, $image);

        // Добавляем приставку для превью
        array_push($pieces, $size . '.' . array_pop($pieces));

        // Генерим полный путь для АПИ
        $image = url(Storage::url(implode(DIRECTORY_SEPARATOR, $pieces)));

        return $image;
    }
}
