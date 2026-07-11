<?php

namespace App\Services\Entity;

use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use App\Traits\ReportTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProviderCategoryService extends BaseService
{
    /**
     * Initializes the ProviderCategoryService class.
     *
     * @param Category $category The Category model instance.
     */
    public function __construct(Category $category)
    {
        parent::__construct($category);
    }

    /**
     * Override the index method to filter provider categories (parent_id = null)
     */
    public function index()
    {
        if (request()->ajax()) {
            // get the rows from the database - only provider categories with parent_id = null
            $provider_categories = $this->model::where('type', CategoryTypeEnum::PROVIDER)
                ->whereNull('parent_id')
                ->search(request()->searchArray)
                ->latest()
                ->paginate(30);

            // determine the table view path
            $tableViewPath = view()->exists('admin.provider-categories.table')
                ? 'admin.provider-categories.table'
                : 'admin.providerCategories.table';

            // render the table view
            $html = view($tableViewPath, get_defined_vars())->render();

            // return the rendered view as a json response
            return response()->json(['html' => $html]);
        }

        // determine the index view path
        $indexViewPath = view()->exists('admin.provider-categories.index')
            ? 'admin.provider-categories.index'
            : 'admin.providerCategories.index';

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
        $viewPath = 'admin.provider-categories.create';

        // check if the view exists
        if (!view()->exists($viewPath)) {
            $viewPath = 'admin.providerCategories.create';
        }

        // return the create view
        return view($viewPath);
    }

    /**
     * Show the edit page of the model.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        // Find the item in the database - ensure it's a provider category
        $providerCategory = $this->model::where('type', CategoryTypeEnum::PROVIDER)
            ->whereNull('parent_id')
            ->findOrFail($id);

        // Determine the edit view path
        $viewPath = view()->exists('admin.provider-categories.edit')
            ? 'admin.provider-categories.edit'
            : 'admin.providerCategories.edit';

        // Return the edit view with the provider category
        return view($viewPath, compact('providerCategory'));
    }

    /**
     * Show the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        // Find the item in the database - ensure it's a provider category
        $providerCategory = $this->model::where('type', CategoryTypeEnum::PROVIDER)
            ->whereNull('parent_id')
            ->findOrFail($id);

        // Determine the show view path
        $viewPath = view()->exists('admin.provider-categories.show')
            ? 'admin.provider-categories.show'
            : 'admin.providerCategories.show';

        // Return the show view with the provider category
        return view($viewPath, compact('providerCategory'));
    }

    /**
     * Store a newly created model in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function store($request)
    {
        // Check if the request is validated or not and get the data
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();
        
        // Set type to PROVIDER and parent_id to null
        $data['type'] = CategoryTypeEnum::PROVIDER;
        $data['parent_id'] = null;
        
        // create a new instance of the model and save it to the database
        $model = $this->model::create($data);

        // check if the request is api and return a json response
        if ($this->isApiRequest()) {
            return $this->apiResourceResponse($model->fresh());
        }

        // add a log entry using the ReportTrait
        ReportTrait::addToLog('add_provider_category');

        // with a success status
        return ['status' => 'success', 'url' => route('admin.provider-categories.index')];
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
        // Find the item and ensure it's a provider category
        $category = $this->model::where('type', CategoryTypeEnum::PROVIDER)
            ->whereNull('parent_id')
            ->findOrFail($id);

        // Check if the request is validated or not and get the data
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();
        
        // Ensure type and parent_id remain correct
        $data['type'] = CategoryTypeEnum::PROVIDER;
        $data['parent_id'] = null;
        
        // Update the category
        $category->update($data);

        // check if the request is api and return a json response
        if ($this->isApiRequest()) {
            return $this->apiResourceResponse($category->fresh());
        }

        // Add a log entry using the ReportTrait
        ReportTrait::addToLog('edit_provider_category');

        // Return a success response
        return ['status' => 'success', 'url' => route('admin.provider-categories.index')];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($id)
    {
        // Find the item and ensure it's a provider category
        $category = $this->model::where('type', CategoryTypeEnum::PROVIDER)
            ->whereNull('parent_id')
            ->findOrFail($id);

        // Delete the category
        $category->delete();

        // Add a log entry using the ReportTrait
        if (!$this->isApiRequest()) {
            ReportTrait::addToLog('delete_provider_category');
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
        $result = $this->model::whereIn('id', $ids)->each(fn($instance) => $instance->delete());

        // If the result is true, add a log entry and return a success response
        if ($result) {
            ReportTrait::addToLog('delete_mutli_provider_categories');
            return ['status' => 'success'];
        } else {
            // If the result is false, return a fail response
            return ['status' => 'fail'];
        }
    }
}

