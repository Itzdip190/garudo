<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    protected function getVendor()
    {
        return Auth::guard('vendor')->user();
    }

    public function index(Request $request)
    {
        $vendor = $this->getVendor();
        $quotations = $vendor->quotations()->with(['customer', 'product'])->latest()->paginate(15);

        return view('vendor.quotations.index', compact('vendor', 'quotations'));
    }

    public function show($id)
    {
        $vendor = $this->getVendor();
        $quotation = $vendor->quotations()->with(['customer', 'product'])->findOrFail($id);

        return view('vendor.quotations.show', compact('vendor', 'quotation'));
    }
}
