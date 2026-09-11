<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        return view('vendor.settings.index', compact('vendor'));
    }
}
