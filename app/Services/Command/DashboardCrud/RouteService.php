<?php 

namespace App\Services\Command\DashboardCrud;

use Illuminate\Support\Facades\Artisan;


class RouteService
{

    /**
     * Create routes for a given folder and model.
     *
     * @param string $folderName The name of the folder.
     * @param string $model      The name of the model.
    */
    function createRoute($folderName, $model)
    {
        // Get the contents of the web.php file
        $webContents = file_get_contents('routes/web.php');
        $Controller = $model.'Controller';
        // Define the new routes
        $newRoutes = "/*------------ start Of $folderName ----------*/
        Route::get('$folderName', [
            'uses'      => '$Controller@index',
            'as'        => '$folderName.index',
            'title'     => '$folderName.index',
            'icon'      => '<i class=\"feather icon-image\"></i>',
            'type'      => 'parent',
            'sub_route' => false,
            'child'     => ['$folderName.create', '$folderName.store', '$folderName.edit', '$folderName.update', '$folderName.show', '$folderName.delete', '$folderName.deleteAll']
        ]);

        # $folderName store
        Route::get('$folderName/create', [
            'uses'  => '$Controller@create',
            'as'    => '$folderName.create',
            'title' => '$folderName.create_page'
        ]);

        # $folderName store
        Route::post('$folderName/store', [
            'uses'  => '$Controller@store',
            'as'    => '$folderName.store',
            'title' => '$folderName.create'
        ]);

        # $folderName update
        Route::get('$folderName/{id}/edit', [
            'uses'  => '$Controller@edit',
            'as'    => '$folderName.edit',
            'title' => '$folderName.edit_page'
        ]);

        # $folderName update
        Route::put('$folderName/{id}', [
            'uses'  => '$Controller@update',
            'as'    => '$folderName.update',
            'title' => '$folderName.edit'
        ]);

        # $folderName show
        Route::get('$folderName/{id}/Show', [
            'uses'  => '$Controller@show',
            'as'    => '$folderName.show',
            'title' => '$folderName.show',
        ]);

        # $folderName delete
        Route::delete('$folderName/{id}', [
            'uses'  => '$Controller@destroy',
            'as'    => '$folderName.delete',
            'title' => '$folderName.delete',
        ]);

        # delete all $folderName
        Route::post('delete-all-$folderName', [
            'uses'  => '$Controller@destroyAll',
            'as'    => '$folderName.deleteAll',
            'title' => '$folderName.delete_all',
        ]);

    /*------------ end Of $folderName ----------*/

    #new_routes_here";

        // Replace the placeholder in the web.php file with the new routes
        $newWebContents = preg_replace("/#new_routes_here/", $newRoutes, $webContents);

        // Write the updated contents back to the web.php file
        file_put_contents('routes/web.php', $newWebContents);

        // Clear the route cache
        Artisan::call('route:clear');
    }
}
