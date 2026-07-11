<?php

namespace App\Http\Controllers\Admin;

use App\Services\Entity\SettingService;

class SettingController extends BaseController
{
    public function __construct(SettingService $service)
    {
        parent::__construct($service);
    }

    public function update($id = null)
    {
        $data = $this->service->update($this->request, $id);
        return redirect()->to($data['url'])->with('success', __('admin.updated'));
    }
}
