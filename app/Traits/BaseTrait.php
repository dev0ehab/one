<?php

namespace App\Traits;

use Str;

trait BaseTrait
{
    /**
     * Paginate
     */
    public function paginate($request)
    {
        $this->model->search($request->searchArray)->paginate($request->per_page);
    }


    /**
     * Get the model validation request path.
     */
    public function getRequestPath()
    {
        // Get the function name/request file name
        $prefix = '';

        foreach (explode('/', request("api_prefix") ?? request()->route()->getPrefix()) as $value) {
            $prefix .= '\\' . ucfirst($value);
        }

        $smallModelName = $this->model->camelModelName();
        $capitalModelName = ucfirst($smallModelName);
        $functionName = ucfirst(request()->route()->getActionMethod());

        // Define possible request file paths
        $paths = [
            "App\Http\Requests{$prefix}\\{$smallModelName}\\{$functionName}",
            "App\Http\Requests{$prefix}\\{$capitalModelName}\\{$functionName}",
        ];

        // Check if the request file exists and return the path
        foreach ($paths as $path) {
            if (class_exists($path)) {
                return $path;
            }
        }

        // Default to Illuminate\Http\Request if no class exists
        return "Illuminate\Http\Request";
    }


    /**
     * Get the model collection.
     */
    public function apiCollectionResponse($data)
    {
        return $this->getApiResponse($data, 'Collection');
    }


    /**
     * Get the model resource.
     */
    public function apiResourceResponse($data)
    {
        return $this->getApiResponse($data, 'Resource');
    }


    /**
     * Get the API response for the given type.
     */
    private function getApiResponse($data, $type)
    {

        $prefix = request("api_prefix") ?? request()->route()->getPrefix();
        $prefix = str_replace('api/', '', $prefix);
        $prefix = $prefix != '' ? ucfirst($prefix) . '\\' : '';


        $baseNamespace = "App\Http\Resources\Api\\" . $prefix . ucfirst($this->model->camelModelName()) . "\\" . ucfirst($this->model->pluralModelName()) . $type;

        // dd($baseNamespace);
        // Check if the class exists in the capitalized namespace
        if (class_exists($baseNamespace)) {
            return $baseNamespace::make($data);
        }
        // Check if the class exists in the lowercase namespace
        $lowercaseNamespace = "App\Http\Resources\Api\\" . $prefix . $this->model->camelModelName() . "\\" . ucfirst($this->model->pluralModelName()) . $type;
        if (class_exists($lowercaseNamespace)) {
            return $lowercaseNamespace::make($data);
        }

        // Return the data if no class exists
        return $data;
    }


    /**
     * Check if the request is from the API or not.
     */
    public function isApiRequest()
    {
        return request()->is('api/*');
    }
}
