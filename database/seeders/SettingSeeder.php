<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use App\Services\SettingService;
use App\Traits\ImageFakerTrait;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SettingSeeder extends Seeder
{
    use ImageFakerTrait;
    protected const PHONE_NUMBER = '+966555184424';

    protected const DUMMY_AR_TEXT = 'من خلال بناء منتج بديهي يحاكي ويسهل تنفيذ الخدمة العامة ، كان الجواب البسيط هو تزويد المستخدمين بثلاثة أشياء';

    protected const DUMMY_EN_TEXT = 'By building an intuitive product that simulates and facilitates the implementation of public service, the simple answer has been to provide users with three things';

    protected $pages = [
        "who_is_us",
        "services",
        "customers",
        "organizations",
        "blogs",
        "contact_us",
        "financials",
        "galleries",
        "jobs",
    ];

    public function run()
    {       
    
        $this->createImageWithName("settings" , "default");
        $this->createImageWithName("settings" , "fav_icon");
        $this->createImageWithName("settings" , "intro_logo");
        $this->createImageWithName("settings" , "logo");
        $this->createImageWithName("users" , "default");
        $this->createImageWithName("settings" , "login_background");

        Cache::forget('settings');

        $pages = [];
        foreach ($this->pages as $page) {
            $pages[] =   ['key' => "{$page}_page_en_title", 'value' => "{$page} page en title"];
            $pages[] =   ['key' => "{$page}_page_ar_title", 'value' => " عنوان الصفحة"];
            $pages[] =   ['key' => "{$page}_page_image", 'value' => "default.png"];
        }

        SiteSetting::insert([
            ...$pages,


            ['key' => 'customers_count', 'value' => '1'],
            ['key' => 'organizations_count', 'value' => '2'],
            ['key' => 'services_count', 'value' => '3'],
            ['key' => 'years_count', 'value' => '4'],
            
            ['key' => 'is_production', 'value' => 0],

            ['key' => 'name_ar', 'value' => 'اسم المشروع'],
            ['key' => 'name_en', 'value' => 'codebase'],

            ['key' => 'description_ar', 'value' => 'وصف المشروع'],
            ['key' => 'description_en', 'value' => 'codebase description'],

            ['key' => 'lat', 'value' => '12.23'],
            ['key' => 'lng', 'value' => '23.12'],
            ['key' => 'map_desc', 'value' => 'شارع  - الرياض - السعودية'],
            ['key' => 'address', 'value' => 'شارع  - الرياض - السعودية'],
            ['key' => 'landing_image', 'value' => 'landing_image.png'],


            ['key' => 'email', 'value' => 'codebase@gmail.com'],
            ['key' => 'phone', 'value' => self::PHONE_NUMBER],
            ['key' => 'whatsapp', 'value' => self::PHONE_NUMBER],
            ['key' => 'terms_ar', 'value' => 'الشروط والاحكام'],
            ['key' => 'terms_en', 'value' => 'terms'],
            ['key' => 'about_ar', 'value' => 'من نحن'],
            ['key' => 'about_en', 'value' => 'about'],
            ['key' => 'privacy_ar', 'value' => 'سياسة الخصوصية باللغه العربية'],
            ['key' => 'privacy_en', 'value' => 'Privacy in english'],
            ['key' => 'logo', 'value' => 'logo.png'],
            ['key' => 'fav_icon', 'value' => 'fav_icon.png'],
            ['key' => 'login_background', 'value' => 'login_background.png'],
            ['key' => 'no_data_icon', 'value' => 'fav.png'],
            ['key' => 'default_user', 'value' => 'default.png'],
            ['key' => 'default_data_image', 'value' => "default.png"],
            ['key' => 'intro_email', 'value' => 'email@gmail.com'],
            ['key' => 'intro_phone', 'value' => self::PHONE_NUMBER],
            ['key' => 'intro_address', 'value' => 'الرياض - السعودية'],
            ['key' => 'intro_logo', 'value' => 'intro_logo.png'],
            ['key' => 'intro_loader', 'value' => 'intro_loader.png'],
            ['key' => 'about_image_2', 'value' => 'about_image_2.png'],
            ['key' => 'about_image_1', 'value' => 'about_image_1.png'],
            ['key' => 'intro_name_ar', 'value' => ' codebase'],
            ['key' => 'intro_name_en', 'value' => 'codebase'],
            ['key' => 'intro_meta_description', 'value' => 'موقع تعريفي خاص codebase '],
            ['key' => 'intro_meta_keywords', 'value' => 'موقع تعريفي خاص codebase '],
            ['key' => 'intro_about_ar', 'value' => 'هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك أن تولد مثل هذا النص أو العديد من النصوص الأخرى هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساح'],
            ['key' => 'intro_about_en', 'value' => 'This text is an example of text that can be replaced in the same space. This text was generated from the Arabic text generator, where you can generate such text or many other texts. This text is an example of text that can be replaced in the same space. This text is an example of text It can be replaced in the same space. This text was generated from the Arabic text generator, where you can generate such text or many other texts. This text is an example of a text that can be replaced in the same space.'],
            ['key' => 'services_text_ar', 'value' => self::DUMMY_AR_TEXT],
            ['key' => 'services_text_en', 'value' => self::DUMMY_EN_TEXT],
            ['key' => 'how_work_text_ar', 'value' => self::DUMMY_AR_TEXT],
            ['key' => 'how_work_text_en', 'value' => self::DUMMY_EN_TEXT],
            ['key' => 'fqs_text_ar', 'value' => self::DUMMY_AR_TEXT],
            ['key' => 'fqs_text_en', 'value' => self::DUMMY_EN_TEXT],
            ['key' => 'organizations_text_ar', 'value' => self::DUMMY_AR_TEXT],
            ['key' => 'organizations_text_en', 'value' => self::DUMMY_EN_TEXT],
            ['key' => 'contact_text_ar', 'value' => self::DUMMY_AR_TEXT],
            ['key' => 'contact_text_en', 'value' => self::DUMMY_EN_TEXT],
            ['key' => 'color', 'value' => '#10163a'],
            ['key' => 'buttons_color', 'value' => '#7367F0'],
            ['key' => 'hover_color', 'value' => '#262c49'],

            ['key' => 'google_places', 'value' => config("services.GOOGLE_MAP.secret")],


            // ['key' => 'smtp_user_name', 'value' => config("mail.username")],
            // ['key' => 'smtp_password', 'value' => config("mail.password")],
            // ['key' => 'smtp_mail_from', 'value' => config("mail.from.address")],
            // ['key' => 'smtp_sender_name', 'value' => config("mail.from.name")],
            // ['key' => 'smtp_port', 'value' => config("mail.port")],
            // ['key' => 'smtp_host', 'value' => config("mail.host")],
            // ['key' => 'smtp_encryption', 'value' => config("mail.encryption")],

            ['key' => 'firebase_key', 'value' => "firebase_key"],
            ['key' => 'firebase_sender_id', 'value' => "firebase_sender_id"],

            ['key' => 'google_analytics', 'value' => "google_analytics"],
            ['key' => 'live_chat', 'value' => "live_chat"],
            ['key' => 'tax', 'value' => 14],
            ['key' => 'order_duration_expiration', 'value' => 60],
            ['key' => 'commission', 'value' => 10],
            ['key' => 'debt_limit', 'value' => "0"],
            ['key' => 'order_to_points', 'value' => "0"],
            ['key' => 'points_to_balance', 'value' => "0"],
            ['key' => 'auto_approve_duration', 'value' => "60"],
            ['key' => 'points_per_riyal', 'value' => "1"],

            ['key' => 'booking_service_monthly_fee', 'value' => '0'],
            ['key' => 'booking_service_duration_days', 'value' => '30'],


        ]);

        Cache::rememberForever('settings', function () {
            return SettingService::appInformations(SiteSetting::pluck('value', 'key'));
        });
    }
}
