<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Image;

trait UploadTrait
{
    static $IMAGE_PATH = 'storage/images/';

    protected $allowedMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/excel',
        'application/vnd.ms-excel',
        'application/vnd.msexcel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/octet-stream',
        'video/mp4',
        'video/mov',
        'video/avi',
        'video/wmv',
        'video/flv',
        'video/mpeg',
        'video/mpg',
        'video/m4v',
        'video/webm',
        'video/mkv',
    ];

    public function uploadAllTypes($file, $directory, $width = null, $height = null)
    {
        if (!File::isDirectory(self::$IMAGE_PATH . $directory)) {
            File::makeDirectory(self::$IMAGE_PATH . $directory, 0777, true, true);
        }

        $fileMimeType = $file->getClientmimeType();
        $imageCheck   = explode('/', $fileMimeType);

        if ($imageCheck[0] == 'image') {
            $allowedImagesMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];

            if (!in_array($fileMimeType, $allowedImagesMimeTypes)) {
                return 'default.png';
            }

            return $this->uploadeImage($file, $directory, $width, $height);
        }

        if (!in_array($fileMimeType, $this->allowedMimeTypes)) {
            return 'default.png';
        }

        return $this->uploadFile($file, $directory);
    }

    public function uploadFile($file, $directory)
    {
        $filename = time() . rand(1000000, 9999999) . '.' . $file->getClientOriginalExtension();
        $path = 'images/' . $directory;
        $file->storeAs($path, $filename);
        
        return $filename;
    }

    public function uploadeImage($file, $directory, $width = null, $height = null)
    {
        $img        = Image::make($file)->orientate();
        $thumbsPath = self::$IMAGE_PATH . $directory;
        $name       = time() . '_' . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();

        if (null != $width && null != $height) {
            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $img->save($thumbsPath . '/' . $name);
        return (string) $name;
    }

    public function deleteFile($file_name, $directory = 'unknown'): void
    {
        if ($file_name && $file_name != 'default.png' && file_exists("storage/images/$directory/$file_name")) {
            unlink(self::$IMAGE_PATH . "$directory/$file_name");
        }
    }

    public function defaultImage($directory)
    {
        $defaultDirectory = "storage/images/$directory/" . $this->defaultImageName();
        
        return file_exists(public_path($defaultDirectory))
            ? asset($defaultDirectory)
            : asset("/defaults/" . "/" . $this->defaultImageName());
    }

    public static function getImage($name, $directory)
    {
        return asset("storage/images/$directory/" . $name);
    }

    public function defaultImageName()
    {
        return 'default.png';
    }
}
