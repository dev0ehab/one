<?php 

namespace App\Services\Command\DashboardCrud;

class TranslationService
{
    /**
     * Creates translations for a given folder name, singular name, and plural name.
     *
     * @param string $folderName The name of the folder.
     * @param string $arSingleName The singular name in Arabic.
     * @param string $arPluralName The plural name in Arabic.
     * @return void
     */
    function createTranslation($folderName, $arSingleName, $arPluralName ,$singleName)
    {
        // Read the contents of the routes.php file
        $contents = file_get_contents('resources/lang/ar/routes.php');

        // Define the new translations
        $translations = "
        '$folderName' => [
            'index' => '$arPluralName',
            'create_page' => 'صفحة اضافة $arSingleName',
            'create' => 'اضافة $arSingleName',
            'edit_page' => 'صفحة تعديل $arSingleName',
            'edit' => 'صفحة تعديل $arSingleName',
            'show' => 'صفحة عرض $arSingleName',
            'delete' => 'حذف $arSingleName',
            'delete_all' => 'حذف العديد  من $arPluralName',
        ],
        #new_comand_translations_here
        ";

        // Replace the placeholder text with the new translations
        $newContents = preg_replace(
            "/#new_comand_translations_here/",
            $translations,
            $contents
        );

        // Write the updated contents back to the routes.php file
        file_put_contents('resources/lang/ar/routes.php', $newContents);

        // Read the contents of the routes.php file
        $enContents = file_get_contents('resources/lang/en/routes.php');

        // Define the new translations
        $translationsEn = "
        '$folderName' => [
            'index' => '$folderName',
            'create_page' => '$singleName Create Page',
            'create' => 'Create $singleName',
            'edit_page' => '$singleName Edit Page',
            'edit' => 'Edit $singleName',
            'show' => '$singleName Show',
            'delete' => 'Delete $singleName',
            'delete_all' => 'Delete All $folderName',
        ],
        #new_comand_translations_here
        ";

        // Replace the placeholder text with the new translations
        $newEnContents = preg_replace(
            "/#new_comand_translations_here/",
            $translationsEn,
            $enContents
        );

        // Write the updated contents back to the routes.php file
        file_put_contents('resources/lang/en/routes.php', $newEnContents);
    }
}