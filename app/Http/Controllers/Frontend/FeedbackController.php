<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PlatformFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display Platform Feedback & Software Reviews Page
     */
    public function index(Request $request)
    {
        $feedbacks = PlatformFeedback::approved()
            ->latest()
            ->paginate(12);

        $allApproved = PlatformFeedback::approved()->get();
        $totalCount = $allApproved->count();
        $avgScore = $totalCount > 0 ? round($allApproved->avg('rating'), 1) : 4.9;

        // Category counts
        $categories = [
            'overall'            => 'Overall Platform Experience',
            'software_ui'        => 'Software & Website UI/UX',
            'procurement_speed'  => 'Procurement & Bulk RFQ Speed',
            'supplier_quality'   => 'Verified Supplier Quality',
            'delivery_logistics' => 'Logistics & Dispatch Tracking',
            'customer_support'   => '24/7 Dedicated Support',
        ];

        return view('frontend.feedback.index', compact('feedbacks', 'totalCount', 'avgScore', 'categories'));
    }

    /**
     * Store Platform / Software Review
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating'                 => 'required|integer|between:1,5',
            'category'               => 'required|string|in:overall,software_ui,procurement_speed,supplier_quality,delivery_logistics,customer_support',
            'name'                   => 'required|string|max:150',
            'company_name'           => 'nullable|string|max:200',
            'email'                  => 'nullable|email|max:150',
            'phone'                  => 'nullable|string|max:30',
            'title'                  => 'nullable|string|max:255',
            'feedback'               => 'required|string|min:5|max:2000',
            'pros'                   => 'nullable|string|max:1000',
            'suggested_improvements' => 'nullable|string|max:1000',
        ]);

        $userType = 'visitor';
        $userId = null;

        if (Auth::guard('customer')->check()) {
            $userType = 'customer';
            $userId = Auth::guard('customer')->id();
        } elseif (Auth::guard('vendor')->check()) {
            $userType = 'vendor';
            $userId = Auth::guard('vendor')->id();
        }

        PlatformFeedback::create([
            'user_type'              => $userType,
            'user_id'                => $userId,
            'name'                   => $validated['name'],
            'company_name'           => $validated['company_name'] ?? null,
            'email'                  => $validated['email'] ?? null,
            'phone'                  => $validated['phone'] ?? null,
            'rating'                 => (int)$validated['rating'],
            'category'               => $validated['category'],
            'title'                  => $validated['title'] ?? null,
            'feedback'               => $validated['feedback'],
            'pros'                   => $validated['pros'] ?? null,
            'suggested_improvements' => $validated['suggested_improvements'] ?? null,
            'status'                 => 'approved',
        ]);

        return back()->with('success', 'Thank you for your feedback! Your review helps us continuously improve Garudo B2B marketplace and software.');
    }
}
