<?php

namespace App\Services\Entity;

use App\Models\Country;

class CountryService extends BaseService
{
    /**
     * Initializes the CountryService class.
     *
     * @param Country $country The Country model instance.
     */
    public function __construct(Country $country)
    {
        parent::__construct($country);
    }

    /**
     * Override the index method to load relationships
     */
    public function index()
    {
        if (request()->ajax()) {
            // get the rows from the database with relationships
            ${$this->model->getFolderName()} = $this->model::withCount(['cities'])
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
}
