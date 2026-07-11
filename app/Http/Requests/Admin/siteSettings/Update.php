<?php

namespace App\Http\Requests\Admin\siteSettings;

use App\Http\Requests\BaseRequest;

class Update extends BaseRequest
{
    protected const SOMETIMES_REQUIRED_RULE = 'sometimes|required';

    protected const IMAGE_RULE = 'image';

    public function rules(): array
    {
        $rules = [];


        if ($this->type == 'intro_app_setting') {
            $rules = [
                'intro_name_ar' => static::SOMETIMES_REQUIRED_RULE,
                'intro_name_en' => static::SOMETIMES_REQUIRED_RULE,
                'intro_email'   => static::SOMETIMES_REQUIRED_RULE,
                'intro_phone'   => static::SOMETIMES_REQUIRED_RULE,
                'intro_address' => static::SOMETIMES_REQUIRED_RULE,
                'color'         => static::SOMETIMES_REQUIRED_RULE,
                'buttons_color' => static::SOMETIMES_REQUIRED_RULE,
                'hover_color'   => static::SOMETIMES_REQUIRED_RULE,
                'intro_logo'    => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
                'intro_loader'  => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
            ];
        } elseif ($this->type == 'Frequent_texts') {
            $rules = [
                'services_text_ar' => static::SOMETIMES_REQUIRED_RULE,
                'services_text_en' => static::SOMETIMES_REQUIRED_RULE,
                'how_work_text_ar' => static::SOMETIMES_REQUIRED_RULE,
                'how_work_text_en' => static::SOMETIMES_REQUIRED_RULE,
                'fqs_text_ar'      => static::SOMETIMES_REQUIRED_RULE,
                'fqs_text_en'      => static::SOMETIMES_REQUIRED_RULE,
            ];
        } elseif ($this->type == 'intro_about_app') {
            $rules = [
                'about_image_1'  => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
                'about_image_2'  => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
                'intro_about_ar' => static::SOMETIMES_REQUIRED_RULE,
                'intro_about_en' => static::SOMETIMES_REQUIRED_RULE,
            ];
        } elseif ($this->type == 'app_setting') {
            $rules = [
                'name_ar'             => static::SOMETIMES_REQUIRED_RULE,
                'name_en'             => static::SOMETIMES_REQUIRED_RULE,
                'description_ar'      => static::SOMETIMES_REQUIRED_RULE,
                'description_en'      => static::SOMETIMES_REQUIRED_RULE,
                'email'               => static::SOMETIMES_REQUIRED_RULE,
                'phone'               => static::SOMETIMES_REQUIRED_RULE . '|numeric|digits_between:9,11',
                'whatsapp'            => static::SOMETIMES_REQUIRED_RULE . '|numeric',
                'logo'                => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
                'landing_image_site'  => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
                'login_image_company' => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
                'fav_icon'            => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
                'login_background'    => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
                'default_user'        => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
                'default_data_image'  => static::SOMETIMES_REQUIRED_RULE . '|' . static::IMAGE_RULE,
                'address'             => static::SOMETIMES_REQUIRED_RULE . '|' . 'string',
                'map_desc'            => static::SOMETIMES_REQUIRED_RULE . '|' . 'string',
                'lat'                 => static::SOMETIMES_REQUIRED_RULE . '|' . 'numeric',
                'lng'                 => static::SOMETIMES_REQUIRED_RULE . '|' . 'numeric',

            ];
        } elseif ($this->type == 'email_data') {
            $rules = [
                'smtp_user_name'   => static::SOMETIMES_REQUIRED_RULE,
                'smtp_password'    => static::SOMETIMES_REQUIRED_RULE,
                'smtp_mail_from'   => static::SOMETIMES_REQUIRED_RULE,
                'smtp_sender_name' => static::SOMETIMES_REQUIRED_RULE,
                'smtp_host'        => static::SOMETIMES_REQUIRED_RULE,
                'smtp_encryption'  => static::SOMETIMES_REQUIRED_RULE,
                'smtp_port'        => static::SOMETIMES_REQUIRED_RULE,
            ];
        } elseif ($this->type == 'notification_data') {
            $rules = [
                'firebase_key'       => static::SOMETIMES_REQUIRED_RULE,
                'firebase_sender_id' => static::SOMETIMES_REQUIRED_RULE,
            ];
        } elseif ($this->type == 'api_data') {
            $rules = [
                'google_analytics' => static::SOMETIMES_REQUIRED_RULE,
                'google_places'    => static::SOMETIMES_REQUIRED_RULE,
            ];
        } elseif ($this->type == 'pages_data') {
            $rules = [
                "who_is_us_page_ar_title"  => 'sometimes|string|language_match:ar',
                // "who_is_us_page_en_title"  => 'sometimes|string|language_match:en',
                "services_page_ar_title"   => 'sometimes|string|language_match:ar',
                "services_page_en_title"   => 'sometimes|string|language_match:en',
                "customers_page_ar_title"  => 'sometimes|string|language_match:ar',
                "customers_page_en_title"  => 'sometimes|string|language_match:en',
                "partners_page_ar_title"   => 'sometimes|string|language_match:ar',
                // "partners_page_en_title"   => 'sometimes|string|language_match:en',
                "blogs_page_ar_title"      => 'sometimes|string|language_match:ar',
                "blogs_page_en_title"      => 'sometimes|string|language_match:en',
                "contact_us_page_ar_title" => 'sometimes|string|language_match:ar',
                "contact_us_page_en_title" => 'sometimes|string|language_match:en',
                "financials_page_ar_title" => 'sometimes|string|language_match:ar',
                "financials_page_en_title" => 'sometimes|string|language_match:en',
                "galleries_page_ar_title"  => 'sometimes|string|language_match:ar',
                "galleries_page_en_title"  => 'sometimes|string|language_match:en',
                "jobs_page_ar_title"       => 'sometimes|string|language_match:ar',
                "jobs_page_en_title"       => 'sometimes|string|language_match:en',
                "who_is_us_page_image"     => 'sometimes|image|mimes:jpg,jpeg,png',
                "services_page_image"      => 'sometimes|image|mimes:jpg,jpeg,png',
                "customers_page_image"     => 'sometimes|image|mimes:jpg,jpeg,png',
                "partners_page_image"      => 'sometimes|image|mimes:jpg,jpeg,png',
                "blogs_page_image"         => 'sometimes|image|mimes:jpg,jpeg,png',
                "contact_us_page_image"    => 'sometimes|image|mimes:jpg,jpeg,png',
                "financials_page_image"    => 'sometimes|image|mimes:jpg,jpeg,png',
                "galleries_page_image"     => 'sometimes|image|mimes:jpg,jpeg,png',
                "jobs_page_image"          => 'sometimes|image|mimes:jpg,jpeg,png',
            ];
        } elseif ($this->type == 'order_wallet_data') {
            $rules = [
                'tax' => 'required|numeric|min:0|max:100',
                'commission' => 'required|numeric|min:0|max:100',
                'order_duration_expiration' => 'required|numeric|min:0',
                'rating_points' => 'required|numeric|min:0',
                'points_per_riyal' => 'required|numeric|min:0',
                'points_validity_months' => 'required|numeric|min:0',
            ];
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        //
    }

    public function attributes()
    {
        return [
            'intro_name_ar' => __('admin.name_of_induction_in_arabic'),
            'intro_name_en' => __('admin.name_of_the_induction_of_english'),

            'services_text_ar' => __('admin.address_of_our_services_section_in_arabic'),
            'services_text_en' => __('admin.address_of_our_services_section_in_english'),
            'how_work_text_ar' => __('admin.the_title_of_how_the_site_works_in_arabic'),
            'how_work_text_en' => __('admin.the_title_of_how_the_site_works_in_english'),
            'fqs_text_ar'      => __('admin.the_address_of_the_questions_section_in_arabic'),
            'fqs_text_en'      => __('admin.the_address_of_the_questions_section_english'),
            'partners_text_ar' => __('admin.the_title_of_our_partition_in_arabic'),
            'partners_text_en' => __('admin.the_title_of_our_partition_in_english'),
            'contact_text_ar'  => __('admin.address_in_arabic_communication'),
            'contact_text_en'  => __('admin.address_in_english_communication'),

            'intro_about_ar' => __('admin.about_the_arabic_application'),
            'intro_about_en' => __('admin.about_the_english_application'),

            // 'whatsapp' => __('admin.whats_app_number'),
            'delivery' => __('admin.delivery'),
            'vat_tax'  => __('admin.vat_tax'),

            'smtp_user_name'   => __('admin.user_name'),
            'smtp_password'    => __('admin.password'),
            'smtp_mail_from'   => __('admin.the_email_sender'),
            'smtp_sender_name' => __('admin.the_sender_name'),
            'smtp_host'        => __('admin.the_host_name'),
            'smtp_encryption'  => __('admin.encryption_type'),
            'smtp_port'        => __('admin.smtp_port'),

            'firebase_key'       => __('admin.server_key'),
            'firebase_sender_id' => __('admin.sender_identification'),

            'google_analytics' => __('admin.google_analytics'),
            'google_places'    => __('admin.google_places'),

            'fav_icon'         => __('admin.fav_icon_image'),
            'login_background' => __('admin.background_image'),
            'default_user'     => __('admin.default_user_image'),
            'intro_logo'       => __('admin.logo_image'),
            'intro_loader'     => __('admin.intro_loader_image'),
        ];
    }
}
