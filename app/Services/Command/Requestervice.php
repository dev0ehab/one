<?php
namespace App\Services\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
class Requestervice
{
    /**
     * Creates a request for the given folder name.
     *
     * @param string $folderName The name of the folder.
     */
    public function createRequest($model)
    {
        // Generate 'Store' request
        Artisan::call('make:request', ['name' => 'Admin/' . $model . '/Store']);

        // Generate 'Update' request
        Artisan::call('make:request', ['name' => 'Admin/' . $model . '/Update']);

        // Copy 'store_copy.php' to 'Store.php' and replace 'Copy' with folderName
        File::copy(
            'app/Console/CommandCopys/store_copy.php',
            base_path('app/Http/Requests/Admin/' . $model . '/Store.php')
        );
        file_put_contents(
            'app/Http/Requests/Admin/' . $model . '/Store.php',
            preg_replace("/Copy/", $model, file_get_contents('app/Http/Requests/Admin/' . $model . '/Store.php'))
        );

        // Copy 'update_copy.php' to 'Update.php' and replace 'Copy' with folderName
        File::copy(
            'app/Console/CommandCopys/update_copy.php',
            base_path('app/Http/Requests/Admin/' . $model . '/Update.php')
        );
        file_put_contents(
            'app/Http/Requests/Admin/' . $model . '/Update.php',
            preg_replace("/Copy/", $model, file_get_contents('app/Http/Requests/Admin/' . $model . '/Update.php'))
        );
    }
}