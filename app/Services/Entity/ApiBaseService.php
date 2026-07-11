<?php

namespace App\Services\Entity;


use App\Traits\ReportTrait;
use Illuminate\Database\Eloquent\Model;

class ApiBaseService
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Show the index page of the model.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get all the items from the database
        $data = $this->model::search(request()->searchArray)->paginate(request()->per_page);

        // Return a json response
        return response()->json(['success' => true, 'data' => $data]);
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
        $payload = method_exists($request, 'validated') ? $request->validated() : $request->all();
        // create a new instance of the model
        // and save it to the database
        $data = $this->model::create($payload);

        // add a log entry
        // using the ReportTrait
        // the log entry will be in the format
        // "add_{lower_model_name}"
        // for example, if the model is named "User"
        // the log entry will be "add_user"
        ReportTrait::addToLog('add_' . $this->model->lowerModelName());

        // return a success response
        return ['status' => 'success', 'data' => $data];
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
        $payload = method_exists($request, 'validated') ? $request->validated() : $request->all();
        // Find the item in the database
        // and update it with the validated
        // data from the request
        $data = tap($this->model::findOrFail($id))->update($payload);

        // Add a log entry
        // using the ReportTrait
        // the log entry will be in the format
        // "edit_{lower_model_name}"
        // for example, if the model is named "User"
        // the log entry will be "edit_user"
        ReportTrait::addToLog('edit_' . $this->model->lowerModelName());

        // Return a success response
        return ['status' => 'success', 'data' => $data];
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Find the item in the database
        // and store it in a variable with the name
        // of the model in lower case
        // for example, if the model is named "User"
        // the variable will be named "$user"
        $data = $this->model::findOrFail($id);

        // Return a json response
        return response()->json(['success' => true, 'data' => $data]);
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
        ReportTrait::addToLog('delete_' . $this->model->lowerModelName());

        // Return a success response
        return ['status' => 'success'];
    }
}
