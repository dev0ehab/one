<?php

namespace App\Services\Entity;

use App\Models\City;

class CityService extends BaseService
{
    /**
     * Initializes the CityService class.
     *
     * @param City $city The City model instance.
     */
    public function __construct(City $city)
    {
        parent::__construct($city);
    }

    /**
     * Override the index method to load relationships
     */
    public function index()
    {
        if (request()->ajax()) {
            // get the rows from the database with relationships
            ${$this->model->getFolderName()} = $this->model::with(['country'])
                ->withCount(['clients'])
                ->search(request()->searchArray)
                ->latest()
                ->paginate(30);

            // determine the table view path
            $tableViewPath = view()->exists('admin.' . $this->model->getFolderName() . '.table')
                ? 'admin.' . $this->model->getFolderName() . '.table'
                : 'admin.' . $this->model->camelModelName() . '.table';

            // render the table view
            $html = view($tableViewPath, get_defined_vars())->render();

            // return the rendered view as a json response
            return response()->json(['html' => $html]);
        }

        // determine the index view path
        $indexViewPath = view()->exists('admin.' . $this->model->getFolderName() . '.index')
            ? 'admin.' . $this->model->getFolderName() . '.index'
            : 'admin.' . $this->model->camelModelName() . '.index';

        // return the index view
        return view($indexViewPath);
    }

    /**
     * Override the destroy method to prevent deletion if city has clients
     */
    public function destroy($id)
    {
        $city = $this->model::withCount(['clients'])->findOrFail($id);
        
        if ($city->clients_count > 0) {
            return ['status' => 'error', 'message' => __('admin.cannot_delete_city_in_use')];
        }

        return parent::destroy($id);
    }
}
