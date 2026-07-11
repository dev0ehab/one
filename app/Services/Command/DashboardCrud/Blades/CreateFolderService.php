<?php 

namespace App\Services\Command\DashboardCrud\Blades; 

use Illuminate\Support\Facades\File;

class CreateFolderService
{
    public function createFolder($folderName)
    {
        File::makeDirectory('resources/views/admin/' . $folderName);
    }
}