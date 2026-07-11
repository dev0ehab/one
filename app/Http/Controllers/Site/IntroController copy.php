<?php

namespace App\Http\Controllers\Site;

use App\Http\Requests\Site\NewsletterRequest;
use App\Http\Requests\Site\sendMessageRequest;
use App\Http\Resources\Api\Settings\PageResource;
use App\Http\Resources\Api\Settings\SocialResource;
use App\Http\Resources\Site\ReviewResource;
use App\Http\Resources\Site\ServiceResource;
use App\Http\Resources\Site\StateResource;
use App\Http\Resources\Site\UserCollection;
use App\Models\Client;
use App\Models\ContactUs;
use App\Models\IntroImage;
use App\Models\IntroReview;
use App\Models\IntroService;
use App\Models\IntroStats;
use App\Models\IntroText;
use App\Models\NewsLetters;
use App\Models\Page;
use App\Models\Organization;
use App\Models\SiteSetting;
use App\Models\Social;
use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Traits\ResponseTrait;
use App\Models\Provider;

class IntroController extends Controller
{
    use ResponseTrait;
    public function index()
    {
        $local = app()->getLocale();
        return $this->successData([
            'landing'    => $this->getLandingData($local),
            'socials'    => $this->getSocialLinks(),
            'services'   => $this->getServiceData($local),
            'sections'   => $this->getSectionData(),
            'clients'    => $this->getClientsData(),
            'partners'   => $this->getPartnersData(),
            'reviews'    => $this->getReviewData(),
            'stats'      => $this->getStatsData(),
            'video'      => IntroImage::where('key', 'video')->first()->image,
            'app'        => $this->getAppData(),
            'newsletter' => $this->getNewsletterData($local),
            'contact_us' => $this->getContactUsData($local),
            'static'     => $this->getStaticData(),
        ]);
    }

    public function getAllClients()
    {
        return $this->successData(UserCollection::make(Client::paginate(10)));
    }

    public function contactUs(sendMessageRequest $request)
    {
        ContactUs::create($request->validated());
        return $this->successMsg(__('apis.success'));
    }

    public function newsletter(NewsletterRequest $request)
    {
        NewsLetters::create($request->validated());
        return $this->successMsg(__('apis.success'));
    }

    public function getAllPartners()
    {
        return $this->successData(UserCollection::make(Organization::paginate(10)));
    }
    public function getAllStations()
    {
        return $this->successData(UserCollection::make(Provider::paginate(10)));
    }
    public function getAllCompanies()
    {
        return $this->successData(UserCollection::make(Merchant::paginate(10)));
    }


    protected function getLandingData($local = 'ar')
    {
        return [
            'title'        => IntroText::where('key', 'landing_title_' . $local)->first()->value,
            'sub_title'    => IntroText::where('key', 'landing_sub_title_' . $local)->first()->value,
            'description'  => IntroText::where('key', 'landing_description_' . $local)->first()->value,
            'android_link' => IntroText::where('key', 'android_app_link')->first()->value,
            'ios_link'     => IntroText::where('key', 'ios_app_link')->first()->value,
            'image1'       => IntroImage::where('key', 'landing_image_1')->first()->image,
            'image2'       => IntroImage::where('key', 'landing_image_2')->first()->image,
        ];
    }

    protected function getSocialLinks()
    {
        return $socials = SocialResource::collection(Social::latest()->get());
    }

    protected function getServiceData($local = 'ar')
    {
        $services = ServiceResource::collection(IntroService::all());
        return [
            'title'       => IntroText::where('key', 'services_title_' . $local)->first()->value,
            'description' => IntroText::where('key', 'services_description_' . $local)->first()->value,
            'services'    => $services,
        ];
    }

    protected function getSectionData()
    {
        return [
            'main_image'      => IntroImage::where('key', 'sections_main_image')->first()->image,
            "title" => "asdasd",
            'images' => [
                IntroImage::where('key', 'sections_clients_image')->first()->image,
                IntroImage::where('key', 'sections_partners_image')->first()->image,
                IntroImage::where('key', 'sections_stations_image')->first()->image,
                IntroImage::where('key', 'sections_companies_image')->first()->image,
            ]
        ];
    }

    protected function getClientsData()
    {
        $clients = Client::where('is_shown_in_landing', 1)
            ->select(['name', 'image'])->limit(6)->get();
        return [
            'title'   => IntroText::where('key', 'clients_title_ar')->first()->value,
            'clients' => $clients,
        ];
    }

    protected function getPartnersData(): array
    {
        $organizations = Organization::whereNotNull('image')->limit(6)->pluck('image');
        return [
            'title'    => IntroText::where('key', 'partners_title_ar')->first()->value,
            'partners' => $organizations,
        ];
    }

    protected function getReviewData()
    {
        return [
            'title'   => IntroText::where('key', 'reviews_title_ar')->first()->value,
            'count'   => IntroReview::count(),
            'reviews' => ReviewResource::collection(IntroReview::all())
        ];
    }

    protected function getStatsData()
    {
        return StateResource::collection(IntroStats::all());
    }

    protected function getAppData($local = 'ar')
    {
        return [
            'title'        => IntroText::where('key', 'app_title_' . $local)->first()->value,
            'android_link' => IntroText::where('key', 'android_app_link')->first()->value,
            'ios_link'     => IntroText::where('key', 'ios_app_link')->first()->value,
            'image'        => IntroImage::where('key', 'app_image')->first()->image,
        ];
    }

    protected function getNewsletterData($local = 'ar')
    {
        return [
            'title'       => IntroText::where('key', 'subscribe_title_' . $local)->first()->value,
            'description' => IntroText::where('key', 'subscribe_description_' . $local)->first()->value,
        ];
    }

    protected function getContactUsData($local = 'ar')
    {
        return [
            'title'    => IntroText::where('key', 'contact_title_' . $local)->first()->value,
            'email'    => SiteSetting::where('key', 'email')->first()->value,
            'phone'    => SiteSetting::where('key', 'phone')->first()->value,
            'whatsapp' => SiteSetting::where('key', 'whatsapp')->first()->value,
            'address'  => SiteSetting::where('key', 'address')->first()->value,
        ];
    }

    protected function getStaticData()
    {
        return [
            'about_us' => PageResource::make(Page::where('slug', 'about')->first()),
            'terms'    => PageResource::make(Page::where('slug', 'terms')->first()),
            'privacy'  => PageResource::make(Page::where('slug', 'privacy')->first()),
        ];
    }
}
