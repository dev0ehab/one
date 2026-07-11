<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\Settings\AppIntroResource;
use App\Http\Resources\Api\Settings\PageResource;
use App\Models\AppIntro;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Page;

class StaticController extends Controller
{
    use ResponseTrait;

    public function aboutUs()
    {
        $static = Page::where("slug","about")->first();
        return $this->successData(PageResource::make($static));
    }

    public function terms()
    {
        $term = Page::where("slug","terms")->first();
        return $this->successData(PageResource::make($term));
    }

    public function privacy()
    {
        $privacy = Page::where("slug","privacy")->first();
        return $this->successData(PageResource::make($privacy));
    }

    public function clientAppIntro()
    {
        $appIntro = AppIntro::where("type","client")->get();
        return $this->successData(AppIntroResource::collection($appIntro));
    }

    public function providerAppIntro()
    {
        $appIntro = AppIntro::where('type', 'provider')->get();

        return $this->successData(AppIntroResource::collection($appIntro));
    }

}
