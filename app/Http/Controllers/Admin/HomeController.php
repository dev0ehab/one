<?php

namespace App\Http\Controllers\Admin;

use App\Models\{
    Client,
    Provider,
    City,
    Contact,
    Notification,
    Admin,
    Role,
};
use App\Traits\MenuTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\admins\UpdateProfile;
use App\Http\Requests\Admin\auth\UpdatePassword;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    use MenuTrait;

    public function dashboard()
    {
        $totalClients       = Client::count();
        $totalProviders     = Provider::count();
        $totalCities        = City::count();
        $totalContacts      = Contact::count();
        $totalNotifications = Notification::where('notifiable_type', Admin::class)->count();
        $totalAdmins        = Admin::count();
        $totalRoles         = Role::count();

        $weekStart = now()->subDays(7);
        $recentClients   = Client::where('created_at', '>=', $weekStart)->count();
        $recentProviders = Provider::where('created_at', '>=', $weekStart)->count();
        $recentContacts  = Contact::where('created_at', '>=', $weekStart)->count();

        $chartMonths     = [];
        $chartClients    = [];
        $chartContacts   = [];
        $chartProviders  = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartMonths[] = $month->translatedFormat('M Y');
            $chartClients[] = Client::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $chartContacts[] = Contact::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $chartProviders[] = Provider::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        $chartWeekDays      = [];
        $chartWeekClients   = [];
        $chartWeekProviders = [];
        $chartWeekContacts  = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $chartWeekDays[] = $day->translatedFormat('D');
            $chartWeekClients[] = Client::whereDate('created_at', $day)->count();
            $chartWeekProviders[] = Provider::whereDate('created_at', $day)->count();
            $chartWeekContacts[] = Contact::whereDate('created_at', $day)->count();
        }

        $pieDistribution = [
            'clients'   => $totalClients,
            'providers' => $totalProviders,
            'admins'    => $totalAdmins,
        ];

        $systemOverview = [
            'contacts'      => $totalContacts,
            'notifications' => $totalNotifications,
            'cities'        => $totalCities,
            'roles'         => $totalRoles,
        ];

        $menus   = $this->home();
        $colores = ['info', 'danger', 'warning', 'success', 'primary'];

        return view('admin.dashboard.index', get_defined_vars());
    }

    public function profile()
    {
        return view('admin.admins.profile');
    }

    public function updateProfile(UpdateProfile $request)
    {
        auth('admin')->user()->update($request->validated());

        return back()->with('success', __('admin.update_successfully'));
    }

    public function updatePassword(UpdatePassword $request)
    {
        if (!Hash::check($request->old_password, auth('admin')->user()->password)) {
            return back()->with('danger', __('admin.not_old_password'));
        }

        auth('admin')->user()->update(['password' => $request->password]);

        return back()->with('success', __('admin.update_successfully'));
    }
}
