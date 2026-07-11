<?php

namespace App\Http\Controllers\Site;

use App\Models\IntroSlider;
use App\Models\IntroSocial;
use App\Models\SiteSetting;
use App\Models\IntroHowWork;
use App\Models\IntroService;
use App\Models\IntroMessages;
use App\Models\IntroPartner;
use App\Models\IntroFqsCategory;
use App\Services\SettingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Site\sendMessageRequest;

class IntroController extends Controller
{
    public function index()
    {
        view()->share([
            'services'      => IntroService::get(),
            'sliders'       => IntroSlider::get(),
            'fqsCategories' => IntroFqsCategory::get(),
            'partners'      => IntroPartner::get(),
            'howWorks'      => IntroHowWork::get(),
            'socials'       => IntroSocial::get(),
            'settings'      => SettingService::appInformations(SiteSetting::pluck('value', 'key')),
        ]);
        return view('intro_site.index');
    }

    public function privacyPolicy()
    {
        view()->share([
            'socials'  => IntroSocial::get(),
            'settings' => SettingService::appInformations(SiteSetting::pluck('value', 'key')),
        ]);
        return view('intro_site.privacy');
    }

    public function sendMessage(sendMessageRequest $request)
    {
        IntroMessages::create($request->validated());
        return response()->json(['status' => 'done', 'message' => __('intro_site.message_sent')]);
    }

    /***************** change lang  *****************/
    public function setLanguage($lang)
    {
        if (in_array($lang, ['ar', 'en'])) {

            if (session()->has('lang')) {
                session()->forget('lang');
            }

            session()->put('lang', $lang);
        } else {
            if (session()->has('lang')) {
                session()->forget('lang');
            }

            session()->put('lang', 'ar');
        }

        return back();
    }

    public function deleteAccount()
    {
        view()->share([
            'socials'  => IntroSocial::get(),
            'settings' => SettingService::appInformations(SiteSetting::pluck('value', 'key')),
        ]);
        return view('intro_site.deleteAccount');
    }
}
