<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use App\Models\IntroReview;

class IntroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $intro_text = [
        //     [
        //         'key' => 'landing_title_ar',
        //         'value' => 'مرحبًا بك في موقعنا',
        //     ],
        //     [
        //         'key' => 'landing_title_en',
        //         'value' => 'Welcome to our website',
        //     ],
        //     [
        //         'key' => 'landing_description_ar',
        //         'value' => 'هذا هو الوصف العربي لموقعنا.',
        //     ],
        //     [
        //         'key' => 'landing_description_en',
        //         'value' => 'This is the English description of our website.',
        //     ],
        //     [
        //         'key' => 'landing_sub_title_ar',
        //         'value' => 'اكتشف المزيد عن خدماتنا',
        //     ],
        //     [
        //         'key' => 'landing_sub_title_en',
        //         'value' => 'Discover more about our services',
        //     ],
        //     [
        //         'key' => 'services_title_ar',
        //         'value' => 'خدماتنا',
        //     ],
        //     [
        //         'key' => 'services_title_en',
        //         'value' => 'Our Services',
        //     ],
        //     [
        //         'key' => 'services_description_ar',
        //         'value' => 'نحن نقدم مجموعة متنوعة من الخدمات لتلبية احتياجاتك.',
        //     ],
        //     [
        //         'key' => 'services_description_en',
        //         'value' => 'We offer a variety of services to meet your needs.',
        //     ],
        //     [
        //         'key' => 'sections_title_ar',
        //         'value' => 'أقسام الموقع',
        //     ],
        //     [
        //         'key' => 'sections_title_en',
        //         'value' => 'Website Sections',
        //     ],
        //     [
        //         'key' => 'sections_description_ar',
        //         'value' => 'استكشف أقسام موقعنا المختلفة.',
        //     ],
        //     [
        //         'key' => 'sections_description_en',
        //         'value' => 'Explore our different website sections.',
        //     ],
        //     [
        //         'key' => 'clients_title_ar',
        //         'value' => 'عملاؤنا',
        //     ],
        //     ['key' => 'clients_title_en',
        //         'value' => 'Our Clients',
        //     ],
        //     [
        //         'key' => 'organizations_title_ar',
        //         'value' => 'شركاؤنا',
        //     ],
        //     ['key' => 'organizations_title_en',
        //         'value' => 'Our organizations',
        //     ],
        //     [
        //         'key' => 'reviews_title_ar',
        //         'value' => 'آراء العملاء',
        //     ],
        //     ['key' => 'reviews_title_en',
        //         'value' => 'Customer Reviews',
        //     ],
        //     [
        //         'key' => 'app_title_ar',
        //         'value' => 'تطبيقنا',
        //     ],
        //     ['key' => 'app_title_en',
        //         'value' => 'Our App',
        //     ],
        //     [
        //         'key' => 'subscribe_title_ar',
        //         'value' => 'اشترك في نشرتنا الإخبارية',
        //     ],
        //     ['key' => 'subscribe_title_en',
        //         'value' => 'Subscribe to our Newsletter',
        //     ],
        //     [
        //         'key' => 'subscribe_description_ar',
        //         'value' => 'ابق على اطلاع بآخر الأخبار والعروض.',
        //     ],
        //     ['key' => 'subscribe_description_en',
        //         'value' => 'Stay updated with the latest news and offers.',
        //     ],
        //     [
        //         'key' => 'contact_title_ar',
        //         'value' => 'اتصل بنا',
        //     ],
        //     ['key' => 'contact_title_en',
        //         'value' => 'Contact Us',
        //     ],
        //     [
        //         'key' => 'android_app_link',
        //         'value' => 'https://play.google.com/store/apps/details?id=com.example.app',
        //     ],
        //     ['key' => 'ios_app_link',
        //         'value' => 'https://apps.apple.com/app/id1234567890',
        //     ],
        // ];
        // $intro_image = [
        //     // [
        //     //     'key' => 'landing_background_image',
        //     //     'image' => 'default.png',
        //     // ],
        //     // [
        //     //     'key' => 'landing_image_1',
        //     //     'image' => 'default.png',
        //     // ],
        //     // [
        //     //     'key' => 'landing_image_2',
        //     //     'image' => 'default.png',
        //     // ],
        //     // [
        //     //     'key' => 'sections_main_image',
        //     //     'image' => 'default.png',
        //     // ],
        //     // [
        //     //     'key' => 'sections_clients_image',
        //     //     'image' => 'default.png',
        //     // ],
        //     // [
        //     //     'key' => 'sections_organizations_image',
        //     //     'image' => 'default.png',
        //     // ],
        //     // [
        //     //     'key' => 'sections_providers_image',
        //     //     'image' => 'default.png',
        //     // ],
        //     // [
        //     //     'key' => 'sections_merchants_image',
        //     //     'image' => 'default.png',
        //     // ],
        //     // [
        //     //     'key' => 'app_image',
        //     //     'image' => 'default.png',
        //     // ],
        //     // [
        //     //     'key' => 'video',
        //     //     'image' => 'default.mp4',
        //     // ],
        //     [
        //         'key' => 'app_data_1',
        //         'image' => 'default.png',
        //     ],
        //     [
        //         'key' => 'app_data_2',
        //         'image' => 'default.png',
        //     ],
        //     [
        //         'key' => 'app_data_3',
        //         'image' => 'default.png',
        //     ],
        //     [
        //         'key' => 'app_data_4',
        //         'image' => 'default.png',
        //     ],
        //     [
        //         'key' => 'app_data_5',
        //         'image' => 'default.png',
        //     ],
        //     [
        //         'key' => 'subscribe_image',
        //         'image' => 'default.png',
        //     ],
        // ];
        // $intro_service = [
        //     [
        //         'title' => json_encode([
        //             'ar' => 'اقرب المحطات',
        //             'en' => 'Nearest providers',
        //         ]),
        //         'description' => json_encode([
        //             'ar' => 'اكتشف أقرب محطات الوقود إليك بسهولة.',
        //             'en' => 'Easily discover the nearest fuel providers to you.',
        //         ]),
        //         'image' => 'default.png',
        //     ],
        //     [
        //         'title' => json_encode([
        //             'ar' => 'خدمات سهلة',
        //             'en' => 'Easy Services',
        //         ]),
        //         'description' => json_encode([
        //             'ar' => 'احصل على خدماتنا بسهولة ويسر.',
        //             'en' => 'Get our services easily and conveniently.',
        //         ]),
        //         'image' => 'default.png',
        //     ],
        //     [
        //         'title' => json_encode([
        //             'ar' => 'الدعم المستمر',
        //             'en' => 'Continuous Support',
        //         ]),
        //         'description' => json_encode([
        //             'ar' => 'نحن هنا لدعمك في كل خطوة.',
        //             'en' => 'We are here to support you every step of the way.',
        //         ]),
        //         'image' => 'default.png',
        //     ],
        //     [
        //         'title' => json_encode([
        //             'ar' => 'أمان أموالك',
        //             'en' => 'Your money is safe with us',
        //         ]),
        //         'description' => json_encode([
        //             'ar' => 'نحن نضمن أمان أموالك في كل معاملة.',
        //             'en' => 'We ensure the safety of your money in every transaction.',
        //         ]),
        //         'image' => 'default.png',
        //     ],
        // ];
        // $intro_review = [
        //     [
        //         'image' => 'default.png',
        //         'comment' => json_encode([
        //             'ar' => 'هذا هو التعليق الأول من العميل.',
        //             'en' => 'This is the first customer review.',
        //         ], JSON_UNESCAPED_UNICODE),
        //     ],
        //     [
        //         'image' => 'default.png',
        //         'comment' => json_encode([
        //             'ar' => 'هذا هو التعليق الثاني من العميل.',
        //             'en' => 'This is the second customer review.',
        //         ], JSON_UNESCAPED_UNICODE),
        //     ],
        //     [
        //         'image' => 'default.png',
        //         'comment' => json_encode([
        //             'ar' => 'هذا هو التعليق الثالث من العميل.',
        //             'en' => 'This is the third customer review.',
        //         ], JSON_UNESCAPED_UNICODE),
        //     ],
        //     [
        //         'image' => 'default.png',
        //         'comment' => json_encode([
        //             'ar' => 'هذا هو التعليق الرابع من العميل.',
        //             'en' => 'This is the fourth customer review.',
        //         ], JSON_UNESCAPED_UNICODE),
        //     ],
        //     [
        //         'image' => 'default.png',
        //         'comment' => json_encode([
        //             'ar' => 'هذا هو التعليق الخامس من العميل.',
        //             'en' => 'This is the fifth customer review.',
        //         ], JSON_UNESCAPED_UNICODE),
        //     ],
        //     [
        //         'image' => 'default.png',
        //         'comment' => json_encode([
        //             'ar' => 'هذا هو التعليق السادس من العميل.',
        //             'en' => 'This is the sixth customer review.',
        //         ], JSON_UNESCAPED_UNICODE),
        //     ],
        //     [
        //         'image' => 'default.png',
        //         'comment' => json_encode([
        //             'ar' => 'هذا هو التعليق السابع من العميل.',
        //             'en' => 'This is the seventh customer review.',
        //         ], JSON_UNESCAPED_UNICODE),
        //     ],
        //     [
        //         'image' => 'default.png',
        //         'comment' => json_encode([
        //             'ar' => 'هذا هو التعليق الثامن من العميل.',
        //             'en' => 'This is the eighth customer review.',
        //         ], JSON_UNESCAPED_UNICODE),
        //     ]
        // ];
        // $intro_stats = [
        //     [
        //         'key' => 'clients_rates',
        //         'title' => json_encode([
        //             'ar' =>  'تقيمات العملاء',
        //             'en' => 'Clients Rates',
        //         ]),
        //         'image' => 'default.png',
        //         'value' => '100 K',
        //     ],
        //     [
        //         'key' => 'number_of_clients',
        //         'title' => json_encode([
        //             'ar' =>  'عدد العملاء',
        //             'en' => 'Number of Clients',
        //         ]),
        //         'image' => 'default.png',
        //         'value' => '50 K',
        //     ],
        //     [
        //         'key' => 'number_of_organizations',
        //         'title' => json_encode([
        //             'ar' =>  'عدد المحطات',
        //             'en' => 'Number of providers',
        //         ]),
        //         'image' => 'default.png',
        //         'value' => '200 K',
        //     ],
        //     [
        //         'key' => 'number_of_merchants',
        //         'title' => json_encode([
        //             'ar' =>  'عدد الشركات',
        //             'en' => 'Number of merchants',
        //         ]),
        //         'image' => 'default.png',
        //         'value' => '150 K',
        //     ],
        //     [
        //         'key' => 'number_of_downloads',
        //         'title' => json_encode([
        //             'ar' =>  'عدد التنزيلات',
        //             'en' => 'Number of Downloads',
        //         ]),
        //         'image' => 'default.png',
        //         'value' => '300 K',
        //     ],
        // ];

        // DB::table('intro_services')->insert($intro_service);
        // DB::table('intro_texts')->insert($intro_text);
        // DB::table('intro_images')->insert($intro_image);
        // DB::table('intro_reviews')->insert($intro_review);
        // DB::table('intro_stats')->insert($intro_stats);

        // loop over intro reviews and convert the comment to json
        // $intro_reviews = IntroReview::all();
        // foreach ($intro_reviews as $intro_review) {
        //     $intro_review->comment = ['ar' => $intro_review->comment, 'en' => 'This is the review comment in english'];
        //     $intro_review->save();
        // }

    }
}
