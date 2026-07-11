<?php

namespace App\Providers;

use App\Rules\LanguageMatchRule;
use App\Observers\SupplierObserver;
use App\Observers\WalletTransactionObserver;
use App\Models\Provider;
use App\Models\Client;
use App\Models\WalletTransaction;
use Exception;
use App\Models\Social;
use App\Models\SiteSetting;
use App\Models\Page;
use App\Services\SettingService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{

    protected $settings;
    protected $socials;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
        $this->loadMigrationsFrom([
            database_path() . '/migrations',
            database_path() . '/migrations/intro_site',
            database_path() . '/migrations/settings',
            database_path() . '/migrations/chat',
            database_path() . '/migrations/admin',
        ]);

        // try {
        //     $this->settings = Cache::rememberForever('settings', function () {
        //         return SettingService::appInformations(SiteSetting::pluck('value', 'key'));
        //     });
        //     $this->socials = Cache::rememberForever('socials', function () {
        //         return Social::get();
        //     });
        // } catch (Exception $e) {
        //     echo 'app service provider exception :::::::::: ' . $e->getMessage();
        // }

        view()->composer('admin.*', function ($view) {
            $view->with([
                'settings' => $this->settings,
            ]);
        });

        view()->composer('intro_site.*', function ($view) {
            $view->with([
                'terms'   => Page::where('slug', 'terms')->first(),
                'privacy' => Page::where('slug', 'privacy')->first(),
            ]);
        });

        // -------------- lang ---------------- \\
        app()->singleton('lang', function () {
            return session('lang', 'ar');
        });
        // -------------- lang ---------------- \\

        // Prevent Lazy Loading in development
        //Model::preventLazyLoading(!app()->isProduction());

        // Register observers
        Provider::observe(SupplierObserver::class);
        Client::observe(SupplierObserver::class);
        WalletTransaction::observe(WalletTransactionObserver::class);

        $this->languageMatchValidation();
    }



    private function languageMatchValidation()
    {
        Validator::extend('language_match', function ($attribute, $value, $parameters) {
            try {

                if (str_contains($attribute, '.')) {
                    [$attribute, $lang] = explode('.', $attribute);
                }

                $lang = data_get($parameters, '0' , $lang ?? lang() );
                
                in_array($lang, languages());

                return (new LanguageMatchRule($lang))->passes($attribute, $value);

            } catch (\Throwable $th) {
                throw new Exception("Invalid language match rule");
            }
        });
    }
}
