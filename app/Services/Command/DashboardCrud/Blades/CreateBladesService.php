<?php 

namespace App\Services\Command\DashboardCrud\Blades;

use Illuminate\Support\Facades\File;

class CreateBladesService
{
    function CreateBlades($folderName, $singleName)  {
        (new CreateFolderService())->createFolder($folderName);
        (new IndexPageService())->createIndexPage($folderName);
        (new TablePageService())->createTablePage($folderName ,$singleName);
        (new CreatePageService())->createCreatePage($folderName, $singleName);
        (new EditPageService())->createEditPage($folderName, $singleName);
        (new ShowPageService())->createShowPage($folderName, $singleName);
    }
}