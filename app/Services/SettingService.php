<?php

namespace App\Services;

class SettingService
{

    public static function appInformations($app_info)
    {
        $pages = [
            "who_is_us",
            "services",
            "customers",
            "partners",
            "blogs",
            "contact_us",
            "financials",
            "galleries",
            "jobs",
        ];

        foreach ($pages as $page) {
            $pages["{$page}_page_en_title"] = $app_info["{$page}_page_en_title"] ?? null;
            $pages["{$page}_page_ar_title"] = $app_info["{$page}_page_ar_title"] ?? null;
            $image = $app_info["{$page}_page_image"] ?? null;
            $pages["{$page}_page_image"] = $image ? '/storage/images/settings/' . $image : null;
        }

        $data = [
            ...$pages,
            'commission'             => $app_info['commission'] ?? 0,
            'name_ar'                => $app_info['name_ar'] ?? '',
            'name_en'                => $app_info['name_en'] ?? '',
            'description_ar'         => $app_info['description_ar'] ?? '',
            'description_en'         => $app_info['description_en'] ?? '',
            'lat'                    => $app_info['lat'] ?? null,
            'lng'                    => $app_info['lng'] ?? null,
            'address'                => $app_info['address'] ?? '',
            'map_desc'               => $app_info['map_desc'] ?? '',
            'email'                  => $app_info['email'] ?? '',
            'phone'                  => $app_info['phone'] ?? '',
            'whatsapp'               => $app_info['whatsapp'] ?? '',
            'logo'                   => !empty($app_info['logo']) ? '/storage/images/settings/' . $app_info['logo'] : null,
            'fav_icon'               => !empty($app_info['fav_icon']) ? '/storage/images/settings/' . $app_info['fav_icon'] : null,
            'no_data_icon'           => $app_info['no_data_icon'] ?? null,
            'default_user'           => !empty($app_info['default_user']) ? '/storage/images/users/' . $app_info['default_user'] : null,
            'default_data_image'     => !empty($app_info['default_data_image']) ? '/defaults/' . $app_info['default_data_image'] : null,
            'login_background'       => !empty($app_info['login_background']) ? '/storage/images/settings/' . $app_info['login_background'] : null,
            'intro_logo'             => !empty($app_info['intro_logo']) ? '/storage/images/settings/' . $app_info['intro_logo'] : null,
            'intro_loader'           => !empty($app_info['intro_loader']) ? '/storage/images/settings/' . $app_info['intro_loader'] : null,
            'intro_name'             => $app_info['intro_name_' . lang()] ?? '',
            'intro_name_ar'          => $app_info['intro_name_ar'] ?? '',
            'intro_name_en'          => $app_info['intro_name_en'] ?? '',
            'intro_about'            => $app_info['intro_about_' . lang()] ?? '',
            'intro_about_ar'         => $app_info['intro_about_ar'] ?? '',
            'intro_about_en'         => $app_info['intro_about_en'] ?? '',
            'about_image_2'          => !empty($app_info['about_image_2']) ? '/storage/images/settings/' . $app_info['about_image_2'] : null,
            'about_image_1'          => !empty($app_info['about_image_1']) ? '/storage/images/settings/' . $app_info['about_image_1'] : null,
            'services_text_ar'       => $app_info['services_text_ar'] ?? '',
            'services_text_en'       => $app_info['services_text_en'] ?? '',
            'services_text'          => $app_info['services_text_' . lang()] ?? '',
            'how_work_text_ar'       => $app_info['how_work_text_ar'] ?? '',
            'how_work_text_en'       => $app_info['how_work_text_en'] ?? '',
            'how_work_text'          => $app_info['how_work_text_' . lang()] ?? '',
            'fqs_text_ar'            => $app_info['fqs_text_ar'] ?? '',
            'fqs_text_en'            => $app_info['fqs_text_en'] ?? '',
            'fqs_text'               => $app_info['fqs_text_' . lang()] ?? '',
            'partners_text_ar'       => $app_info['partners_text_ar'] ?? $app_info['organizations_text_ar'] ?? '',
            'partners_text_en'       => $app_info['partners_text_en'] ?? $app_info['organizations_text_en'] ?? '',
            'partners_text'          => $app_info['partners_text_' . lang()] ?? $app_info['organizations_text_' . lang()] ?? '',
            'contact_text_ar'        => $app_info['contact_text_ar'] ?? '',
            'contact_text_en'        => $app_info['contact_text_en'] ?? '',
            'contact_text'           => $app_info['contact_text_' . lang()] ?? '',
            'intro_email'            => $app_info['intro_email'] ?? '',
            'intro_phone'            => $app_info['intro_phone'] ?? '',
            'intro_address'          => $app_info['intro_address'] ?? '',
            'color'                  => $app_info['color'] ?? '',
            'buttons_color'          => $app_info['buttons_color'] ?? '',
            'hover_color'            => $app_info['hover_color'] ?? '',
            'intro_meta_description' => $app_info['intro_meta_description'] ?? '',
            'intro_meta_keywords'    => $app_info['intro_meta_keywords'] ?? '',
            // 'smtp_user_name'         => $app_info['smtp_user_name'],
            // 'smtp_password'          => $app_info['smtp_password'],
            // 'smtp_mail_from'         => $app_info['smtp_mail_from'],
            // 'smtp_sender_name'       => $app_info['smtp_sender_name'],
            // 'smtp_port'              => $app_info['smtp_port'],
            // 'smtp_host'              => $app_info['smtp_host'],
            // 'smtp_encryption'        => $app_info['smtp_encryption'],
            'firebase_key'           => $app_info['firebase_key'] ?? '',
            'firebase_sender_id'     => $app_info['firebase_sender_id'] ?? '',
            'google_places'          => $app_info['google_places'] ?? '',
            'google_analytics'       => $app_info['google_analytics'] ?? '',
            'live_chat'              => $app_info['live_chat'] ?? '',
            'tax'                    => $app_info['tax'] ?? 0,
            'order_duration_expiration' => $app_info['order_duration_expiration'] ?? 0,
            'debt_limit'             => $app_info['debt_limit'] ?? 0,
            'points_to_balance'      => $app_info['points_to_balance'] ?? 0,
            'order_to_points'        => $app_info['order_to_points'] ?? 0,
            'auto_approve_duration'  => $app_info['auto_approve_duration'] ?? 0,
            'rating_points'          => $app_info['rating_points'] ?? 0,
            'points_per_riyal'       => $app_info['points_per_riyal'] ?? 0,
            'points_validity_months' => $app_info['points_validity_months'] ?? 0,
        ];
        return $data;
    }
}
