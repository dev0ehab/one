<?php

namespace App\Services\Command\DashboardCrud;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ControllerService 
{
    
/**
 * Creates a controller for a given model.
 *
 * @param string $model The name of the model.
 * @param string $folderName The name of the folder.
 * @param string $singleName The singular name of the model.
 * @param string $arSingleName The singular name of the model in Arabic.
 * @param string $arPluralName The plural name of the model in Arabic.
 * @return void
*/
function createController($model, $folderName, $singleName, $arSingleName, $arPluralName)
{
    // Generate the controller file using Artisan command
    Artisan::call('make:controller', ['name' => 'Admin/' . $model . 'Controller']);

    $contollerPath = 'app/Http/Controllers/Admin/' . $model . 'Controller.php' ;
    // Copy the template controller file to the desired location
    File::copy('app/Console/CommandCopys/Controller.php', base_path($contollerPath));

    // Replace placeholder text in the controller file with actual values
    $controllerContent = file_get_contents($contollerPath);

    $controllerContent = preg_replace("/copys/", $folderName, $controllerContent);

    $controllerContent = preg_replace("/copy/", $singleName, $controllerContent);

    $controllerContent = preg_replace("/Copy/", $model, $controllerContent);

    $controllerContent = preg_replace("/arsinglesame/", $arSingleName, $controllerContent);

    $controllerContent = preg_replace("/arpluraleName/", $arPluralName, $controllerContent);

    // Save the modified controller file
    file_put_contents($contollerPath, $controllerContent);
}
}