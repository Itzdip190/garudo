<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SampleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerSampleController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $customer = Auth::guard('customer')->user();
            if (!$customer) {
                return redirect()->route('customer.login');
            }
            return $next($request);
        });
    }

    protected function getCustomer()
    {
        return Auth::guard('customer')->user();
    }

    public function index()
    {
        $customer = $this->getCustomer();
        $samples = $customer->sampleRequests()
            ->with(['product.primaryImage', 'vendor'])
            ->latest()
            ->paginate(12);

        $counts = [
            'total'       => $customer->sampleRequests()->count(),
            'in_progress' => $customer->sampleRequests()->whereIn('status', ['pending', 'approved', 'dispatched'])->count(),
            'delivered'   => $customer->sampleRequests()->whereIn('status', ['delivered', 'converted_to_order'])->count(),
        ];

        return view('customer.samples.index', compact('customer', 'samples', 'counts'));
    }

    public function show($id)
    {
        $customer = $this->getCustomer();
        $sample = $customer->sampleRequests()
            ->with(['product.primaryImage', 'product.category', 'vendor', 'convertedOrder'])
            ->findOrFail($id);

        return view('customer.samples.show', compact('customer', 'sample'));
    }
}
