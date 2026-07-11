<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExportPdfController extends Controller
{
    public function export(Request $request)
    {
        return response()->json(['status' => false, 'message' => 'Feature not available'], 404);
    }
}
