<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Api\Settings\PageResource;
use App\Http\Resources\Api\Settings\SettingResource;
use App\Models\Social;
use App\Models\SiteSetting;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Settings\SocialResource;
use App\Http\Resources\Api\Settings\ImageResource;
use App\Models\Image;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    use ResponseTrait;

    public function main()
    {
        $data = [
            'sliders'      => ImageResource::collection(Image::latest()->get()),
            // "our_partners" => PartnersResource::collection(Organization::latest()->active()->get()),
            // "who_is_us"    => PageResource::make(Page::whereSlug('about')->first()),
            // "other_data"   => [
            //     "our_vision"   => PageResource::make(Page::whereSlug('our_vision')->first()),
            //     "our_goal"     => PageResource::make(Page::whereSlug('our_goal')->first()),
            //     "our_meassage" => PageResource::make(Page::whereSlug('our_meassage')->first()),
            // ],
            // "statistics" => [
            //     "customers_count" => settings()["customers_count"],
            //     "partners_count"  => settings()["partners_count"],
            //     "services_count"  => settings()["services_count"],
            //     "years_count"     => settings()["years_count"],
            // ],

        ];

        return $this->successData($data);
    }

    public function contact()
    {
        $lang = lang();
        $data = [
            "header" => [
                "title" => settings()["contact_us_page_{$lang}_title"],
                "image" =>  url('/storage/images/settings/' . settings()["contact_us_page_image"]),
            ],
            'phone' => SiteSetting::where(['key' => 'phone'])->first()->value,
            'email' => SiteSetting::where(['key' => 'email'])->first()->value
        ];

        return $this->successData($data);
    }

    public function whoIsUs()
    {

        $lang = lang();
        $data = [
            "header" => [
                "title" => settings()["who_is_us_page_{$lang}_title"],
                "image" =>  url('/storage/images/settings/' . settings()["who_is_us_page_image"]),
            ],

            "who_is_us" => PageResource::make(Page::whereSlug('about')->first()),

            "other_data" => [
                "our_vision"   => PageResource::make(Page::whereSlug('our_vision')->first()),
                "our_goal"     => PageResource::make(Page::whereSlug('our_goal')->first()),
                "our_meassage" => PageResource::make(Page::whereSlug('our_meassage')->first()),
            ],

        ];
        return $this->successData($data);
    }

    public function socials()
    {
        $socials = SocialResource::collection(Social::latest()->get());
        return $this->successData($socials);
    }


    public function settings()
    {
        $socials = SettingResource::make(Cache::get('settings'));
        return $this->successData($socials);
    }

    public function isProduction()
    {
        $isProduction = (bool) (int) (settings()['is_production'] ?? app()->environment('production'));

        return $this->successData([
            'is_production' => $isProduction,
        ]);
    }

}
