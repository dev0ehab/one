<?php

namespace App\Http\Controllers\Admin;

use App\Models\SiteSetting;
use App\Services\Entity\SettingService as EntitySettingService;
use App\Services\SettingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\siteSettings\Update;

class IntroSetting extends Controller
{
    protected $cashedData ; 
    public function __construct()
    {
        $this->cashedData = SettingService::appInformations(SiteSetting::pluck('value', 'key'));
    }

    public function index()
    {
        $data =  $this->cashedData;
        return view('admin.intro_setting.index' , compact('data'));
    }

    public function Update(Update $request , SiteSetting $siteSetting)
    {
        (new EntitySettingService($siteSetting))->update ($request);
        return redirect()->route('admin.intro_settings.index')->with('success', __('admin.updated'));
    }
}