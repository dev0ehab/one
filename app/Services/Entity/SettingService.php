<?php

namespace App\Services\Entity;

use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use App\Traits\ReportTrait;
use Illuminate\View\View;
use Image;

class SettingService extends BaseService
{
    /**
     * Initializes the IntroService class.
     *
     * @param SiteSetting $siteSetting
     */
    public function __construct(SiteSetting $siteSetting)
    {
        $this->model = $siteSetting;
    }
    //--------------------------------------------------------------------------------------------------------------------------------

    public function index(): View
    {
        $data = Cache::rememberForever('settings', function () {
            return \App\Services\SettingService::appInformations(SiteSetting::pluck('value', 'key'));
        });

        return view('admin.settings.index', compact('data'));
    }
    //--------------------------------------------------------------------------------------------------------------------------------

    public function update($request, $id = null)
    {
        // dd($request->validated());
        Cache::forget('settings');
        $this->ensureDirectoriesExist();
        foreach ($request->validated() as $key => $val) {
            if (is_file($val)) {
                $this->handleImageUpload($key, $val);
            } elseif (!is_null($val)) {
                SiteSetting::where('key', $key)->update(['value' => $val]);
            }
        }
    

        $this->refreshCache();
        ReportTrait::addToLog('تعديل الاعدادت');

        return ['status' => 'success', 'url' => route('admin.settings.index')];
    }
    //--------------------------------------------------------------------------------------------------------------------------------

    public function messageAll(Request $request, $type)
    {

        $this->userRepo->messageAll($request->all(), $type);
        return back()->with('success', 'تم الارسال');
    }
    //--------------------------------------------------------------------------------------------------------------------------------

    public function messageOne(Request $request, $type)
    {

        $this->userRepo->messageOne($request->all(), $type);
        return back()->with('success', 'تم الارسال');
    }
    //--------------------------------------------------------------------------------------------------------------------------------

    public function sendEmail(Request $request)
    {

        $this->settingRepo->sendEmail($request->all());
        return back()->with('success', 'تم الارسال');
    }
    //---------------------------------------------------------------------------------------------------------------------------------

    // helpers
    private function ensureDirectoriesExist()
    {
        $directories = [
            storage_path('app/public/images/settings'),
            public_path('storage/images/users'),
            public_path('defaults'),
            public_path('storage/images/settings'),
        ];

        foreach ($directories as $path) {
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }
    }

    private function handleImageUpload($key, $val)
    {
        $img = Image::make($val);
        $thumbsPath = $this->getImagePath($key, $val);
        if ($thumbsPath) {
            $img->save($thumbsPath);
        }
    }

    private function getImagePath($key, $val)
    {
        $paths = [
            'default_user' => 'storage/images/users/default.png',
            'default_data_image' => 'defaults/default.png',
            'no_data' => 'storage/images/no_data.png',
        ];

        if (isset($paths[$key])) {
            return $paths[$key];
        }

        $path = public_path('storage/images/settings');
        $name = time() . rand(1000000, 9999999) . '.' . $val->getClientOriginalExtension();
        SiteSetting::where('key', $key)->update(['value' => $name]);

        return $path . '/' . $name;
    }

    private function refreshCache()
    {
        Cache::rememberForever('settings', function () {
            return \App\Services\SettingService::appInformations(SiteSetting::pluck('value', 'key'));
        });
    }
}