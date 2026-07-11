<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\MasterExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\WalletChargeRequest;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function __construct()
    {
        if (ob_get_length()) {
            ob_end_clean(); // this
            ob_start(); // and this
        }
    }

    public function master($export, Request $request)
    {
        $data =  $this->$export($request);
        return $this->masterExport($export, $data['cols'], $data['values']);
    }

    public function User()
    {
        return [
            'cols' => ['#', __('admin.name'), __('admin.email'), __('admin.phone') , __('admin.country_code')],
            'values' =>  ['id', 'name', 'email', 'phone' , 'country_code'],
        ];
    }

    public function Category()
    {
        return [
            'cols' => ['#', __('admin.name'), __('admin.parent_category')],
            'values' => ['id', 'name', 'parent_category'],
        ];
    }
    
    public function Country()
    {
        return [
            'cols' => ['#', __('admin.name'), __('admin.key')],
            'values' => ['id', 'name', 'key'],
        ];
    }

    public function Admin()
    {
        return [
            'cols' => ['#', __('admin.name'), __('admin.email'), __('admin.phone')],
            'values' => ['id', 'name', 'email', 'phone'],
        ];
    }

    public function City()
    {
        return [
            'cols' => ['#', __('admin.name'), __('admin.country')],
            'values' => ['id', 'name', 'country.name'],
        ];
    }

    public function WalletChargeRequest()
    {
        return [
            'cols' => ['#', __('admin.user_type'), __('admin.user_name'), __('admin.amount'), __('admin.status'), __('admin.created_at')],
            'values' => ['id', 'wallet.walletable_type', 'wallet.walletable.name', 'amount', 'status', 'created_at'],
        ];
    }


    public function Provider()
    {
        return [
            'cols' => ['#', __('admin.trade_name'), __('admin.owner_name'), __('admin.phone'), __('admin.email'), __('admin.national_id')],
            'values' => ['id', 'trade_name', 'owner_name', 'phone', 'email', 'national_id'],
        ];
    }

    public function Client()
    {
        return [
            'cols' => ['#', __('admin.name'), __('admin.phone'), __('admin.email') , __('admin.country_code')],
            'values' => ['id', 'name', 'phone', 'email' , 'country_code'],
        ];
    }

    public function masterExport($model, $cols, $values)
    {
        $folderName = strtolower(Str::plural(class_basename($model)));
        $model = app("App\Models\\$model");
        $records = $model::latest()
            ->when($model instanceof City, fn($q) => $q->with('country'))
            ->when($model instanceof Category, fn($q) => $q->with('parent'))
            ->when($model instanceof WalletChargeRequest, fn($q) => $q->with(['wallet.walletable']))
            ->search(request()->query())
            ->get();
        return Excel::download(new MasterExport($records, 'master-excel', ['cols' => $cols, 'values' => $values]), $folderName . '-reports-' . Carbon::now()->format('Y-m-d') . '.xlsx');
    }
}
