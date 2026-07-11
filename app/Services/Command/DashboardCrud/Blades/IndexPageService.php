<?php 

namespace App\Services\Command\DashboardCrud\Blades;

use Illuminate\Support\Facades\File;

class IndexPageService
{
/**
 * Create an index page for a given folder.
 *
 * @param string $folderName The name of the folder.
 * @return void
 */
public function createIndexPage($folderName)
{
    // Set the path to the index.blade.php file
    $indexPath = 'resources/views/admin/' . $folderName . '/index.blade.php';

    // Copy the index.blade.php template file to the destination folder
    File::copy('app/Console/CommandCopys/Blades/index.blade.php', base_path($indexPath));

    // Retrieve the contents of the index.blade.php file
    $contents = file_get_contents($indexPath);

    // Replace all occurrences of "copys" with the folder name
    $contents = preg_replace("/copys/", $folderName, $contents);

    // Write the modified contents back to the index.blade.php file
    file_put_contents($indexPath, $contents);
}
}