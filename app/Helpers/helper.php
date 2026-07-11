<?php

use App\Models\Seo;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

function seo($key)
{
    return Seo::where('key', $key)->first();
}

function appInformations()
{
    return SiteSetting::pluck('value', 'key');
}

function convert2english($string)
{
    $newNumbers = range(0, 9);
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
    $string = str_replace($arabic, $newNumbers, $string);
    return $string;
}

function fixPhone($string = null)
{
    if (!$string) {
        return null;
    }

    $result = convert2english($string);
    $result = ltrim($result, '00');
    $result = ltrim($result, '0');
    $result = ltrim($result, '+');
    return $result;
}

function getPhone($code, $phone)
{
    return fixPhone($code) . fixPhone($phone);
}

function getYoutubeVideoId($youtubeUrl)
{
    preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $youtubeUrl, $videoId);
    return isset($videoId[1]) ? $videoId[1] : '';
}

function lang()
{
    return App()->getLocale();
}

function generateRandomCode()
{
    return random_int(100000, 999999);
}

if (!function_exists('languages')) {
    function languages($withFaker = false)
    {
        if ($withFaker) {
            return [
                [
                    'code' => 'ar',
                    'faker' => 'ar_SA',
                ],
                [
                    'code' => 'en',
                    'faker' => 'en_US',
                ]
            ];
        }

        return ['ar', 'en'];
    }
}

if (!function_exists('defaultLang')) {
    function defaultLang()
    {
        return 'ar';
    }
}

if (!function_exists('currentLang')) {
    function currentLang()
    {
        // get lang of user's current device
        $user = auth()->user();

        if ($user?->currentDevice) {
            return $user->currentDevice->preferred_locale;
        }

        return defaultLang();
    }
}

if (!function_exists('isEmail')) {
    function isEmail($input)
    {
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('getFlag')) {
    function getFlags()
    {
        $flags = [];
        foreach (File::files(public_path('admin/assets/flags/png')) as $path) {
            $file = pathinfo($path);
            $flags[] = $file['filename'] . '.' . $file['extension'];
        }
        return $flags;
    }
}

if (!function_exists('settings')) {
    function settings()
    {
        return SiteSetting::pluck('value', 'key');
    }
}

if (!function_exists('googleMapKey')) {
    function googleMapKey()
    {
        return SiteSetting::where('key', 'google_places')->value('value');
    }
}

if (!function_exists('format_date')) {
    function format_date(Carbon $date, string $format = 'd F Y'): string
    {
        $formatted = $date->locale($lang = lang())->translatedFormat($format);

        if($lang  != 'ar') {
            return $formatted;
        }
        
        $westernToArabic = ['0'=>'٠','1'=>'١','2'=>'٢','3'=>'٣','4'=>'٤','5'=>'٥','6'=>'٦','7'=>'٧','8'=>'٨','9'=>'٩'];

        return preg_replace_callback('/\d/', function ($matches) use ($westernToArabic) {
            return $westernToArabic[$matches[0]];
        }, $formatted);
    }
}