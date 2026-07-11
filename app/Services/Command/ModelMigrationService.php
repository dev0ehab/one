<?php
namespace App\Services\Command;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;


class ModelMigrationService {


    
/**
 * Create a new model and copy it to the specified folder.
 *
 * @param string $model The name of the model to create.
 * @param string $folderName The name of the folder to copy the model to.
 * @return void
 */
function createModel($model, $folderName)
{
    // Create the model using Artisan command
    Artisan::call('make:model', ['name' => $model, '-m' => true]);

    // Set the path of the model file
    $modelPath = 'app/Models/' . $model . '.php';

    // Copy the model file to the specified folder
    File::copy('app/Console/CommandCopys/model.php', base_path($modelPath));

    // Replace the "Copy" placeholder in the copied model file with the actual model name
    file_put_contents($modelPath, preg_replace("/Copy/", $model, file_get_contents($modelPath)));

    // Replace the "copys" placeholder in the copied model file with the actual folder name
    file_put_contents($modelPath, preg_replace("/copys/", $folderName, file_get_contents($modelPath)));
}
}