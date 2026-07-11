<?php

namespace App\Services\Entity;


use App\Traits\BaseTrait;
use App\Traits\ReportTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class BaseService
{
    use BaseTrait;

    protected $model;

    protected $query;

    protected $filter;

    protected const INDEX_VIEW_NAME = '.index';

    protected const TABLE_VIEW_NAME = '.table';

    protected const CREATE_TABLE_NAME = '.create';

    protected const EDIT_VIEW_NAME = '.edit';

    protected const SHOW_VIEW_NAME = '.show';

    public function __construct(Model $model)
    {
        $this->model = $model;
    }


    /*
    |--------------------------------------------------------------------------
    | WEB Functions
    |--------------------------------------------------------------------------
    |
    */


    /**
     * Show the index page of the model.
     *
     * @return Application|Factory|View|JsonResponse
     */
    public function index()
    {
        if (request()->ajax()) {
            // get the rows from the database
            ${$this->model->getFolderName()} = !empty($this->query) ? $this->query : $this->model::search(request()->searchArray)->paginate(30);
            // determine the table view path
            $tableViewPath = view()->exists('admin.' . $this->model->getFolderName() . static::TABLE_VIEW_NAME)
                ? 'admin.' . $this->model->getFolderName() . static::TABLE_VIEW_NAME
                : 'admin.' . $this->model->camelModelName() . static::TABLE_VIEW_NAME;

            // render the table view
            $html = view($tableViewPath, get_defined_vars())->render();

            // return the rendered view as a json response
            return response()->json(['html' => $html]);
        }

        // determine the index view path
        $indexViewPath = view()->exists('admin.' . $this->model->getFolderName() . static::INDEX_VIEW_NAME)
            ? 'admin.' . $this->model->getFolderName() . static::INDEX_VIEW_NAME
            : 'admin.' . $this->model->camelModelName() . static::INDEX_VIEW_NAME;

        // return the index view
        return view($indexViewPath);
    }


    /**
     * Show the create page of the model.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $viewPath = 'admin.' . $this->model->getFolderName() . '.create';

        // check if the view exists
        if (!view()->exists($viewPath)) {
            $viewPath = 'admin.' . $this->model->camelModelName() . '.create';
        }

        // return the create view
        return view($viewPath);
    }

    /**
     * Store a newly created model in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store($request)
    {
        // Check if the request is validated or not
        // and get the data
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();
        // create a new instance of the model
        // and save it to the database
        $model = $this->model::create($data);

        // check if the request is api
        // and return a json response
        if ($this->isApiRequest()) {
            return $this->apiResourceResponse($model->fresh());
        }

        // add a log entry
        // using the ReportTrait
        // the log entry will be in the format
        // "add_{lower_model_name}"
        // for example, if the model is named "User"
        // the log entry will be "add_user"
        ReportTrait::addToLog('add_' . $this->model->lowerModelName());

        // with a success status
        return ['status' => 'success', 'url' => route('admin.' . $this->model->getFolderName() . '.index')];
    }

    /**
     * Show the edit page of the model.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        // get the item from the database
        // and store it in a variable with the name
        // of the model in lower case
        // for example, if the model is named "User"
        // the variable will be named "$user"
        ${$this->model->lowerModelName()} = $this->model::findOrFail($id);

        // return the edit view
        // with the item as a parameter
        // the view will be located in the folder
        // "admin/{lower_model_name}/edit.blade.php"
        $viewPath = view()->exists('admin.' . $this->model->getFolderName() . '.edit')
            ? 'admin.' . $this->model->getFolderName() . '.edit'
            : 'admin.' . $this->model->camelModelName() . '.edit';

        return view($viewPath, [$this->model->lowerModelName() => ${$this->model->lowerModelName()}]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array
     */
    public function update($request, $id)
    {
        // Check if the request is validated or not
        // and get the data
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();
        // Find the item in the database
        // and update it with the validated
        // data from the request
        $this->model::findOrFail($id)->update($data);


        // check if the request is api
        // and return a json response
        if ($this->isApiRequest()) {
            return $this->apiResourceResponse($this->model::findOrFail($id));
        }

        // Add a log entry
        // using the ReportTrait
        // the log entry will be in the format
        // "edit_{lower_model_name}"
        // for example, if the model is named "User"
        // the log entry will be "edit_user"
        ReportTrait::addToLog('edit_' . $this->model->lowerModelName());

        // Return a success response
        return ['status' => 'success', 'url' => route('admin.' . $this->model->getFolderName() . '.index')];
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int  $id
     * @return Application|Factory|View|JsonResponse
     */
    public function show($id)
    {
        // Find the item in the database
        // and store it in a variable with the name
        // of the model in lower case
        // for example, if the model is named "User"
        // the variable will be named "$user"
        ${$this->model->lowerModelName()} = $this->model::findOrFail($id);

        // Return the show view
        // with the item as a parameter
        // the view will be located in the folder
        // "admin/{lower_model_name}/show.blade.php"
        $viewPath = view()->exists('admin.' . $this->model->getFolderName() . '.show')
            ? 'admin.' . $this->model->getFolderName() . '.show'
            : 'admin.' . $this->model->camelModelName() . '.show';

        return view($viewPath, [$this->model->lowerModelName() => ${$this->model->lowerModelName()}]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        // Find the item in the database
        // and delete it
        $this->model::findOrFail($id)->delete();

        // Add a log entry
        // using the ReportTrait
        // the log entry will be in the format
        // "delete_{lower_model_name}"
        // for example, if the model is named "User"
        // the log entry will be "delete_user"
        if (!$this->isApiRequest()) {
            ReportTrait::addToLog('delete_' . $this->model->lowerModelName());
        }

        // Return a success response
        return ['status' => 'success'];
    }

    /**
     * Destroy multiple items from storage.
     *
     * @return array
     */
    public function destroyAll($request)
    {
        // Decode the json data from the request
        $ids = array_map(fn($id) => $id->id, json_decode(request()->data));

        // Destroy all the items with the given ids
        // and store the result in a variable
        $result = $this->model::whereIn('id', $ids)->each(fn($instance) => $instance->delete());

        // If the result is true
        // add a log entry
        // and return a success response
        if ($result) {
            ReportTrait::addToLog('delete_mutli_' . $this->model->getFolderName());
            return ['status' => 'success'];
        } else {
            // If the result is false
            // return a fail response
            return ['status' => 'fail'];
        }
    }




    /*
    |--------------------------------------------------------------------------
    | API Functions
    |--------------------------------------------------------------------------
    |
    */

    /**
     * Get the index page of the model.
     */
    public function indexApi()
    {
        // Return all the items from the database
        $query = $this->model::search(request()->searchArray)->latest();

        if (request()->has('is_paginated') && !request()->is_paginated) {
            $data = $query->when(request()->limit, fn($query) => $query->limit(request()->limit))->get();
        } else {
            $data = $query->paginate(request('perPage', 15));
        }

        // Return a json response
        return $this->apiCollectionResponse($data);
    }


    /**
     * Show the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function showApi($id)
    {
        // Find the item in the database
        // and store it in a variable with the name
        // of the model in lower case
        // for example, if the model is named "User"
        // the variable will be named "$user"
        $data = $this->model::findOrFail($id);

        // Return JSON response
        // with the data
        return $this->apiResourceResponse($data);
    }
}
