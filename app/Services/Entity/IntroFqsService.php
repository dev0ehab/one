<?php

namespace App\Services\Entity;

use App\Models\IntroFqs;
use Illuminate\Http\JsonResponse;

class IntroFqsService extends BaseService
{
    /**
     * Initializes the ImageService class.
     *
     * @param IntroFqs $introFqs
     */
    public function __construct(IntroFqs $introFqs)
    {
        $this->model = $introFqs;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse|mixed
     */
    public function index()
    {
        if (request()->ajax()) {
            $introFqs = IntroFqs::with('category')->search(request()->searchArray)->paginate(30);
            $html = view('admin.introfqs.table', compact('introFqs'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.introfqs.index');
    }
}
