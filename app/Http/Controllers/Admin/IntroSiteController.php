<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\introSites\Update;
use App\Models\IntroText;
use App\Models\IntroImage;
use App\Models\IntroStats;
use App\Models\IntroReview;
use App\Models\IntroService;
use Illuminate\Support\Facades\DB;
use App\Traits\ResponseTrait;

class IntroSiteController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $intro_texts = IntroText::pluck('value', 'key');
        $intro_images = IntroImage::pluck('image', 'key');
        $intro_stats = IntroStats::all();
        $intro_reviews = IntroReview::all();
        $intro_services = IntroService::all();
        return view('admin.intro_sites.index', compact('intro_texts', 'intro_images', 'intro_stats', 'intro_reviews', 'intro_services'));
    }

    public function update(Update $request)
    {
        try {
            DB::beginTransaction();

            // Update each section
            $this->updateLandingSection($request);
            $this->updateServicesSection($request);
            $this->updateSectionsSection($request);
            $this->updateStatsSection($request);
            $this->updateVideoSection($request);
            $this->updateNewsletterSection($request);
            $this->updateContactSection($request);
            $this->updateAppSection($request);

            DB::commit();
            return redirect()->route('admin.intro_sites')->with('success', __('admin.intro_sites_updated_successfully'));
            
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.intro_sites.index')->with('error', __('admin.error_occurred') . ': ' . $e->getMessage());
        }
    }

    /**
     * Update Landing Section
     */
    private function updateLandingSection($request)
    {
        // Update landing texts
        if ($request->has('landing_title')) {
            foreach ($request->landing_title as $lang => $value) {
                IntroText::updateOrCreate(
                    ['key' => "landing_title_{$lang}"],
                    ['value' => $value]
                );
            }
        }

        if ($request->has('landing_description')) {
            foreach ($request->landing_description as $lang => $value) {
                IntroText::updateOrCreate(
                    ['key' => "landing_description_{$lang}"],
                    ['value' => $value]
                );
            }
        }

        if ($request->has('landing_sub_title')) {
            foreach ($request->landing_sub_title as $lang => $value) {
                IntroText::updateOrCreate(
                    ['key' => "landing_sub_title_{$lang}"],
                    ['value' => $value]
                );
            }
        }

        // Update landing images
        if ($request->hasFile('landing_background')) {
            IntroImage::updateOrCreate(
                ['key' => 'landing_background_image'],
                ['image' => $request->landing_background]
            );
        }

        if ($request->hasFile('landing_image_no_1')) {
            IntroImage::updateOrCreate(
                ['key' => 'landing_image_1'],
                ['image' => $request->landing_image_no_1]
            );
        }

        if ($request->hasFile('landing_image_no_2')) {
            IntroImage::updateOrCreate(
                ['key' => 'landing_image_2'],
                ['image' => $request->landing_image_no_2]
            );
        }
    }

    /**
     * Update Services Section
     */
    private function updateServicesSection($request)
    {
        if ($request->has('services')) {
            foreach ($request->services as $index => $serviceData) {
                $service = IntroService::find($index); // Assuming services have sequential IDs

                if ($service) {
                    $updateData = [
                        'title' => $serviceData['title'] ?? [],
                        'description' => $serviceData['description'] ?? [],
                    ];
                    if (isset($serviceData['image'])) {
                        $updateData['image'] = $serviceData['image'];
                    }
                    $service->update($updateData);
                }
            }
        }
    }

    /**
     * Update Sections Section
     */
    private function updateSectionsSection($request)
    {
        // Update sections texts
        if ($request->has('sections_title')) {
            foreach ($request->sections_title as $lang => $value) {
                IntroText::updateOrCreate(
                    ['key' => "sections_title_{$lang}"],
                    ['value' => $value]
                );
            }
        }

        if ($request->has('sections_description')) {
            foreach ($request->sections_description as $lang => $value) {
                IntroText::updateOrCreate(
                    ['key' => "sections_description_{$lang}"],
                    ['value' => $value]
                );
            }
        }

        // Update sections images
        $sectionsImages = [
            'sections_main_image' => 'sections_main_image',
            'sections_clients_image' => 'sections_clients_image',
            'sections_partners_image' => 'sections_partners_image',
            'sections_stations_image' => 'sections_stations_image',
            'sections_companies_image' => 'sections_companies_image',
        ];

        foreach ($sectionsImages as $requestKey => $imageKey) {
            if ($request->hasFile($requestKey)) {
                IntroImage::updateOrCreate(
                    ['key' => $imageKey],
                    ['image' => $request->$requestKey]
                );
            }
        }
    }

    /**
     * Update Stats Section
     */
    private function updateStatsSection($request)
    {
        if ($request->has('stats')) {
            foreach ($request->stats as $index => $statData) {
                $stat = IntroStats::find($index); // Assuming stats have sequential IDs

                if ($stat) {
                    $updateData = [
                        'title' => $statData['title'] ?? [],
                        'value' => $statData['value'],
                    ];

                    if (isset($statData['image'])) {
                        $updateData['image'] = $statData['image'];
                    }

                    $stat->update($updateData);
                }
            }
        }
    }

    /**
     * Update Video Section
     */
    private function updateVideoSection($request)
    {
        if ($request->hasFile('video')) {
            IntroImage::updateOrCreate(
                ['key' => 'video'],
                ['image' => $request->video]
            );
        }
    }

    /**
     * Update Newsletter Section
     */
    private function updateNewsletterSection($request)
    {
        if ($request->has('subscribe_title')) {
            foreach ($request->subscribe_title as $lang => $value) {
                IntroText::updateOrCreate(
                    ['key' => "subscribe_title_{$lang}"],
                    ['value' => $value]
                );
            }
        }

        if ($request->has('subscribe_description')) {
            foreach ($request->subscribe_description as $lang => $value) {
                IntroText::updateOrCreate(
                    ['key' => "subscribe_description_{$lang}"],
                    ['value' => $value]
                );
            }
        }
        $newsletterImages = [
            'app_data_1' => 'app_data_1',
            'app_data_2' => 'app_data_2',
            'app_data_3' => 'app_data_3',
            'app_data_4' => 'app_data_4',
            'app_data_5' => 'app_data_5',
            'subscribe_image' => 'subscribe_image',
        ];

        foreach ($newsletterImages as $requestKey => $imageKey) {
            if ($request->hasFile($requestKey)) {
                IntroImage::updateOrCreate(
                    ['key' => $imageKey],
                    ['image' => $request->$requestKey]
                );
            }
        }
    }

    /**
     * Update Contact Section
     */
    private function updateContactSection($request)
    {
        if ($request->has('contact_title')) {
            foreach ($request->contact_title as $lang => $value) {
                IntroText::updateOrCreate(
                    ['key' => "contact_title_{$lang}"],
                    ['value' => $value]
                );
            }
        }

        if ($request->has('contact_description')) {
            foreach ($request->contact_description as $lang => $value) {
                IntroText::updateOrCreate(
                    ['key' => "contact_description_{$lang}"],
                    ['value' => $value]
                );
            }
        }
    }

    /**
     * Update App Section
     */
    private function updateAppSection($request)
    {
        // Update app texts
        if ($request->has('app_title')) {
            foreach ($request->app_title as $lang => $value) {
                IntroText::updateOrCreate(
                    ['key' => "app_title_{$lang}"],
                    ['value' => $value]
                );
            }
        }

        // Update app links
        if ($request->has('android_app_link')) {
            IntroText::updateOrCreate(
                ['key' => 'android_app_link'],
                ['value' => $request->android_app_link]
            );
        }

        if ($request->has('ios_app_link')) {
            IntroText::updateOrCreate(
                ['key' => 'ios_app_link'],
                ['value' => $request->ios_app_link]
            );
        }

        // Update app image
        if ($request->hasFile('app_image')) {
            IntroImage::updateOrCreate(
                ['key' => 'app_image'],
                ['image' => $request->app_image]
            );
        }
    }
}
