<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Address;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\Enquiry;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $customer = Auth::guard('customer')->user();
            if ($customer && !empty($customer->must_change_password)) {
                return redirect()->route('customer.force_change_password')
                    ->with('warning', 'You logged in with a temporary demo password. Please set your new permanent password before proceeding.');
            }
            return $next($request);
        });
    }

    /**
     * Get the authenticated customer or fallback if authenticated.
     */
    protected function getCustomer()
    {
        return Auth::guard('customer')->user();
    }

    /**
     * Customer Dashboard
     */
    public function index()
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return redirect()->route('customer.login');
        }

        // Live Dynamic Stats from Database
        $totalOrders = $customer->orders()->count();
        $ordersInProgress = $customer->orders()->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped'])->count();
        $completedOrders = $customer->orders()->where('status', 'delivered')->count();
        $totalSpent = $customer->orders()->where('payment_status', 'paid')->sum('grand_total');

        $stats = [
            'total_orders' => $totalOrders,
            'orders_in_progress' => $ordersInProgress,
            'completed_orders' => $completedOrders,
            'total_spent' => number_format($totalSpent, 2),
        ];

        // Live Recent Orders
        $recentOrders = $customer->orders()
            ->with(['items.vendor', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Live Delivery Addresses
        $addresses = $customer->addresses()
            ->orderBy('is_default', 'desc')
            ->latest()
            ->take(3)
            ->get();

        return view('customer.dashboard.index', compact('customer', 'stats', 'recentOrders', 'addresses'));
    }

    /**
     * Orders List
     */
    public function orders(Request $request)
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $statusFilter = $request->query('status', 'all');

        $query = $customer->orders()->with(['items.vendor', 'items.product', 'items.shipment.deliveryPartner'])->latest();

        if ($statusFilter === 'in_progress') {
            $query->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped']);
        } elseif ($statusFilter === 'delivered') {
            $query->where('status', 'delivered');
        } elseif ($statusFilter === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        $orders = $query->paginate(10);

        $counts = [
            'all' => $customer->orders()->count(),
            'in_progress' => $customer->orders()->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped'])->count(),
            'delivered' => $customer->orders()->where('status', 'delivered')->count(),
            'cancelled' => $customer->orders()->where('status', 'cancelled')->count(),
        ];

        return view('customer.orders.index', compact('customer', 'orders', 'statusFilter', 'counts'));
    }

    /**
     * Order Details
     */
    public function orderDetails($id)
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $order = $customer->orders()
            ->with(['items.vendor', 'items.product', 'items.shipment.deliveryPartner'])
            ->where('id', $id)
            ->first();

        if (!$order) {
            return redirect()->route('customer.orders')->with('error', 'Order not found.');
        }

        return view('customer.orders.show', compact('customer', 'order'));
    }

    /**
     * Quotes (RFQ)
     */
    public function quotes()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $quotes = Quotation::where('customer_id', $customer->id)
            ->with(['vendor', 'product'])
            ->latest()
            ->paginate(10);

        return view('customer.quotes.index', compact('customer', 'quotes'));
    }

    /**
     * Wishlist
     */
    public function wishlist()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $wishlistItems = Wishlist::where('customer_id', $customer->id)
            ->with(['product.vendor', 'product.brand'])
            ->latest('id')
            ->paginate(12);

        return view('customer.wishlist.index', compact('customer', 'wishlistItems'));
    }

    /**
     * Addresses
     */
    public function addresses()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $addresses = $customer->addresses()
            ->orderBy('is_default', 'desc')
            ->latest()
            ->get();

        return view('customer.addresses.index', compact('customer', 'addresses'));
    }

    /**
     * Store Address
     */
    public function storeAddress(Request $request)
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $validated = $request->validate([
            'label' => 'nullable|string|max:100',
            'contact_name' => 'required|string|max:200',
            'contact_phone' => 'required|string|max:15',
            'address_line1' => 'required|string|max:500',
            'address_line2' => 'nullable|string|max:500',
            'landmark' => 'nullable|string|max:300',
            'city' => 'required|string|max:150',
            'state' => 'required|string|max:150',
            'pincode' => 'required|string|max:10',
            'address_type' => 'nullable|in:billing,shipping,both',
            'is_default' => 'nullable',
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $customer->addresses()->create([
            'label' => $validated['label'] ?? 'Site / Home',
            'contact_name' => $validated['contact_name'],
            'contact_phone' => $validated['contact_phone'],
            'address_line1' => $validated['address_line1'],
            'address_line2' => $validated['address_line2'] ?? null,
            'landmark' => $validated['landmark'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'],
            'country' => 'India',
            'pincode' => $validated['pincode'],
            'address_type' => $validated['address_type'] ?? 'both',
            'is_default' => $isDefault || ($customer->addresses()->count() === 0),
        ]);

        return back()->with('success', 'Delivery address added successfully.');
    }

    /**
     * Payment Methods
     */
    public function paymentMethods()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        return view('customer.payments.index', compact('customer'));
    }

    /**
     * Reviews
     */
    public function reviews()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $reviews = Review::where('customer_id', $customer->id)
            ->with(['product', 'vendor'])
            ->latest()
            ->paginate(10);

        return view('customer.reviews.index', compact('customer', 'reviews'));
    }

    /**
     * Returns & Refunds
     */
    public function returns()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $returns = $customer->orders()
            ->whereIn('status', ['returned', 'refunded', 'cancelled'])
            ->with(['items.vendor', 'items.product'])
            ->latest()
            ->paginate(10);

        return view('customer.returns.index', compact('customer', 'returns'));
    }

    /**
     * Inquiries & RFQ
     */
    public function inquiries()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $inquiries = Enquiry::where('customer_id', $customer->id)
            ->with(['vendor', 'product'])
            ->latest()
            ->paginate(10);

        return view('customer.inquiries.index', compact('customer', 'inquiries'));
    }

    /**
     * Notifications
     */
    public function notifications()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        $notifications = $customer->notifications()->latest()->paginate(15);

        return view('customer.notifications.index', compact('customer', 'notifications'));
    }

    /**
     * Settings / Profile
     */
    public function settings()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return redirect()->route('customer.login');
        }

        return view('customer.settings.index', compact('customer'));
    }

    /**
     * Update Profile
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            $customer = Customer::first();
        }

        if ($customer) {
            $validated = $request->validate([
                'name' => 'required|string|max:200',
                'email' => 'required|email|max:200|unique:customers,email,' . $customer->id,
                'phone' => 'nullable|string|max:20',
                'company_name' => 'nullable|string|max:300',
                'gstin' => 'nullable|string|max:20',
            ]);
            $customer->update($validated);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $customer = Auth::guard('customer')->user();
        if ($customer) {
            if (!Hash::check($request->current_password, $customer->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $customer->password = Hash::make($request->password);
            if (\Illuminate\Support\Facades\Schema::hasColumn('customers', 'must_change_password')) {
                $customer->must_change_password = false;
            }
            $customer->save();
        }

        return back()->with('success', 'Your password has been changed successfully.');
    }

    /**
     * Customer Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('customer.login')->with('success', 'Logged out successfully.');
    }
}
