<?php

namespace App\Http\Requests\Admin\introSites;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // Landing Section
            'landing_background_image' => 'nullable|image',
            'landing_image_1' => 'nullable|image',
            'landing_image_2' => 'nullable|image',
            'landing_title.*'       => 'required',
            'landing_description.*' => 'required',
            'landing_sub_title.*' => 'required',

            // Services Section
            'services' => 'required|array',
            'services.*.image' => 'nullable|image',
            'services.*.title.*' => 'required',
            'services.*.description.*' => 'required',

            // Sections Section
            'sections_title.*' => 'required',
            'sections_description.*' => 'required',
            'sections_main_image' => 'nullable|image',
            'sections_clients_image' => 'nullable|image',
            'sections_partners_image' => 'nullable|image',
            'sections_stations_image' => 'nullable|image',
            'sections_companies_image' => 'nullable|image',

            // Stats Section
            'stats' => 'required|array',
            'stats.*.image' => 'nullable|image',
            'stats.*.title.*' => 'required',
            'stats.*.value' => 'required',

            // Video Section
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,flv,mpeg,mpg,m4v,webm,mkv',

            // Newsletter Section
            'subscribe_title.*' => 'required',
            'subscribe_description.*' => 'required',

            // Contact Us Section
            'contact_title.*' => 'required',
            'contact_description.*' => 'required',

            // App Section
            'app_image' => 'nullable|image',
            'app_title.*' => 'required',
            'android_app_link' => 'required|url',
            'ios_app_link' => 'required|url',
        ];
    }



    public function messages()
    {
        return [
            // Landing Section Messages
            'landing_background_image.image' => __('validation.intro_sites.landing_background_image.image'),
            'landing_image_1.image' => __('validation.intro_sites.landing_image_1.image'),
            'landing_image_2.image' => __('validation.intro_sites.landing_image_2.image'),
            'landing_title.*.required' => __('validation.intro_sites.landing_title.required'),
            'landing_description.*.required' => __('validation.intro_sites.landing_description.required'),
            'landing_sub_title.*.required' => __('validation.intro_sites.landing_sub_title.required'),

            // Services Section Messages
            'services.required' => __('validation.intro_sites.services.required'),
            'services.array' => __('validation.intro_sites.services.array'),
            'services.*.image.image' => __('validation.intro_sites.services_image.image'),
            'services.*.title.*.required' => __('validation.intro_sites.services_title.required'),
            'services.*.description.*.required' => __('validation.intro_sites.services_description.required'),

            // Sections Section Messages
            'sections_title.*.required' => __('validation.intro_sites.sections_title.required'),
            'sections_description.*.required' => __('validation.intro_sites.sections_description.required'),
            'sections_main_image.image' => __('validation.intro_sites.sections_main_image.image'),
            'sections_clients_image.image' => __('validation.intro_sites.sections_clients_image.image'),
            'sections_partners_image.image' => __('validation.intro_sites.sections_partners_image.image'),
            'sections_stations_image.image' => __('validation.intro_sites.sections_stations_image.image'),
            'sections_companies_image.image' => __('validation.intro_sites.sections_companies_image.image'),

            // Stats Section Messages
            'stats.required' => __('validation.intro_sites.stats.required'),
            'stats.array' => __('validation.intro_sites.stats.array'),
            'stats.*.image.image' => __('validation.intro_sites.stats_image.image'),
            'stats.*.title.*.required' => __('validation.intro_sites.stats_title.required'),
            'stats.*.value.required' => __('validation.intro_sites.stats_value.required'),

            // Video Section Messages
            'video.image' => __('validation.intro_sites.video.image'),

            // Newsletter Section Messages
            'subscribe_title.*.required' => __('validation.intro_sites.subscribe_title.required'),
            'subscribe_description.*.required' => __('validation.intro_sites.subscribe_description.required'),

            // Contact Us Section Messages
            'contact_title.*.required' => __('validation.intro_sites.contact_title.required'),
            'contact_description.*.required' => __('validation.intro_sites.contact_description.required'),

            // App Section Messages
            'app_image.image' => __('validation.intro_sites.app_image.image'),
            'app_title.*.required' => __('validation.intro_sites.app_title.required'),
            'android_app_link.required' => __('validation.intro_sites.android_app_link.required'),
            'android_app_link.url' => __('validation.intro_sites.android_app_link.url'),
            'ios_app_link.required' => __('validation.intro_sites.ios_app_link.required'),
            'ios_app_link.url' => __('validation.intro_sites.ios_app_link.url'),
        ];
    }
}
