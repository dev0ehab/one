<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait ModelTrait
{
    /**
     * Return the name of the model.
     *
     * @return string
     */
    public function modelName(): string
    {
        return class_basename($this);
    }

    /**
     * Return the name of the model in lower case.
     *
     * @return string
     */
    public function lowerModelName(): string
    {
        return strtolower($this->modelName());
    }

    /**
     * Return the plural name of the model.
     *
     * @return string
     */
    public function pluralModelName(): string
    {
        //  جمع اسم ال model
        return Str::plural($this->modelName());
    }

    /**
     * Return the name of the folder in the views directory.
     *
     * @return string
     */
    public function getFolderName(): string
    {
        // return the name of the folder in the views directory
        // it is the plural name of the model in lower case
        return strtolower($this->pluralModelName());
    }


    /**
     * Return the name of the model in camel case.
     * 
     * @return string
     */
    public function camelModelName(): string
    {
        return Str::camel($this->pluralModelName());
    }
}
