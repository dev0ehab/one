<?php 

namespace App\Services\Command\DashboardCrud\Blades;

use Illuminate\Support\Facades\File;

class ShowPageService
{
/**
 * Create an index page for a given folder.
 *
 * @param string $folderName    The name of the folder.
 * @param string $singleName    The name of the single item.
 * @param string $arSingleName  The Arabic name of the single item.
 * @return void
 */
public function createShowPage($folderName, $singleName)
{
    // Define the path of the create.blade.php file
    $showPath = 'resources/views/admin/' . $folderName . '/show.blade.php';

    // Copy the 'create.blade.php' file to the specified folder
    File::copy('app/Console/CommandCopys/blades/show.blade.php', base_path($showPath));

    // Read the contents of the copied file
    $copiedContent = file_get_contents($showPath);

    // Replace all occurrences of 'copys' with the folder name in the copied file
    $updatedContent = preg_replace("/copys/", $folderName, $copiedContent);

    // Replace all occurrences of 'copy' with the single item name in the copied file
    $updatedContent = preg_replace("/copy/", $singleName, $updatedContent);

    // Write the updated content back to the create.blade.php file
    file_put_contents($showPath, $updatedContent);
}
}