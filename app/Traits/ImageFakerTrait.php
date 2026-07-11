<?php

namespace App\Traits;

use Faker\Factory as Faker;
use File;


trait ImageFakerTrait
{
    public function createImage($model, $url = "images/fakers", $count = 1, $media = "images")
    {
        $faker = Faker::create();

        for ($i = 0; $i < $count; $i++) {

            $files = File::files(public_path($url));
            $index = $faker->numberBetween(0, count($files) - 1);

            if (isset($files[$index])) {
                $path = asset($url . "/" . $files[$index]->getFilename());

                $model->addMediaFromUrl($path)->preservingOriginal()->toMediaCollection($media);
            }
        }
    }


    public function createImageWithStore($class, $url = "images/fakers", $count = 1, $width = null, $height = null)
    {
        $faker = Faker::create();
        $directory = $class::IMAGEPATH; // Ensure this is correctly defined in your model
        $IMAGE_PATH = storage_path('app/public/images/' . $directory);

        // Ensure directory exists
        if (!File::exists($IMAGE_PATH)) {
            File::makeDirectory($IMAGE_PATH, 0755, true, true);
        }

        $files = File::files(public_path($url)); // Get local file paths

        if (empty($files)) {
            return null; // No images available
        }

        $imageNames = [];

        for ($i = 0; $i < $count; $i++) {
            $index = $faker->numberBetween(0, count($files) - 1);
            $file = $files[$index];

            $img = \Image::make($file->getPathname())->orientate(); // Correct file path usage

            // Get file extension correctly
            $extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
            $name = ($i + 1) . '.' . $extension;

            if ($width && $height) {
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $img->save($IMAGE_PATH . '/' . $name); // Save correctly
            $imageNames[] = 'storage/images/' . $directory . '/' . $name; // Return correct storage URL
        }

        return count($imageNames) === 1 ? $imageNames[0] : $imageNames; // Return single or multiple
    }


    public function createImageWithName($directory = "" , $name = "")
    {
        $faker = Faker::create();
        $IMAGE_PATH = storage_path('app/public/images/' . $directory);

        // Ensure the target directory exists
        if (!File::exists($IMAGE_PATH)) {
            File::makeDirectory($IMAGE_PATH, 0755, true, true);
        }

        $files = File::files(public_path("images/fakers")); // Get image files from the public directory

        if (empty($files)) {
            return null; // No images found
        }

        // Pick a random file
        $file = $files[$faker->numberBetween(0, count($files) - 1)];
        $img = \Image::make($file->getPathname())->orientate();

        // Get the original file extension
        $extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);

        // Append extension to the provided name
        $finalName = $name . '.' . $extension;

        // Save the image to storage
        $img->save($IMAGE_PATH . '/' . $finalName);

        // Return the relative storage path
        return 'storage/images/' . $directory . '/' . $finalName;
    }
}
