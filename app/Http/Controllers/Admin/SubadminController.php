<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use App\Services\SubadminPermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubadminController extends Controller
{
    /**
     * Ensure requester is Super Admin or has subadmin access permissions.
     */
    protected function authorizeAccess(string $action = 'view'): void
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            abort(401, 'Unauthenticated.');
        }

        if ($admin->is_super_admin) {
            return;
        }

        $permMap = [
            'view'   => 'subadmins.view',
            'create' => 'subadmins.create',
            'edit'   => 'subadmins.edit',
            'delete' => 'subadmins.delete',
        ];

        $required = $permMap[$action] ?? 'subadmins.view';
        if (!$admin->hasPermission($required)) {
            abort(403, "Unauthorized: You do not have permission [{$required}] to manage subadmins.");
        }
    }

    /**
     * Display a listing of all subadmin staff accounts.
     */
    public function index(Request $request)
    {
        $this->authorizeAccess('view');
        SubadminPermissionService::ensureSystemReady();

        $query = Admin::with(['roles', 'permissions']);

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Role filter
        if ($roleId = $request->input('role_id')) {
            $query->whereHas('roles', fn ($q) => $q->where('roles.id', $roleId));
        }

        // Put Super Admin at top, then newest
        $admins = $query->orderByDesc('is_super_admin')
                        ->orderByDesc('id')
                        ->paginate(15)
                        ->withQueryString();

        $stats = [
            'total'          => Admin::count(),
            'super_admins'   => Admin::where('is_super_admin', true)->count(),
            'subadmins'      => Admin::where('is_super_admin', false)->count(),
            'active'         => Admin::where('status', 'active')->count(),
            'inactive'       => Admin::where('status', '!=', 'active')->count(),
            'roles_count'    => Role::count(),
        ];

        $allRoles = Role::all();

        return view('admin.subadmins.index', compact('admins', 'stats', 'allRoles'));
    }

    /**
     * Show the form for creating a new subadmin account.
     */
    public function create()
    {
        $this->authorizeAccess('create');
        SubadminPermissionService::ensureSystemReady();

        $modules = SubadminPermissionService::getModuleDefinitions();
        $presets = SubadminPermissionService::getPresets();
        $roles   = Role::where('name', '!=', 'super_admin')->get();
        $dbPermissions = Permission::all()->keyBy('name');

        return view('admin.subadmins.create', compact('modules', 'presets', 'roles', 'dbPermissions'));
    }

    /**
     * Store a newly created subadmin account with specific permissions.
     */
    public function store(Request $request)
    {
        $this->authorizeAccess('create');
        SubadminPermissionService::ensureSystemReady();

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:200'],
            'email'                 => ['required', 'email', 'max:200', 'unique:admins,email'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['required', 'string', 'min:6', 'confirmed'],
            'status'                => ['required', 'in:active,inactive,suspended'],
            'role_id'               => ['nullable', 'exists:roles,id'],
            'permissions'           => ['nullable', 'array'],
            'permissions.*'         => ['string'],
        ], [
            'email.unique'          => 'This login email address is already in use by another administrator.',
            'password.confirmed'    => 'Password confirmation does not match.',
            'password.min'          => 'Password must be at least 6 characters long.',
        ]);

        DB::beginTransaction();
        try {
            $admin = Admin::create([
                'name'           => $validated['name'],
                'email'          => strtolower(trim($validated['email'])),
                'phone'          => $validated['phone'] ?? null,
                'password'       => Hash::make($validated['password']),
                'status'         => $validated['status'],
                'is_super_admin' => false,
            ]);

            // Assign standard role if chosen
            if (!empty($validated['role_id'])) {
                $admin->roles()->sync([$validated['role_id']]);
            }

            // Sync direct permissions
            $permissionNames = $validated['permissions'] ?? [];
            if (!empty($permissionNames)) {
                $permissionIds = Permission::whereIn('name', $permissionNames)->pluck('id')->toArray();
                if (!empty($permissionIds)) {
                    $admin->permissions()->sync($permissionIds);
                }
            }

            DB::commit();

            return redirect()->route('admin.subadmins.index')->with('success', "Subadmin account '{$admin->name}' (Login: {$admin->email}) created successfully with configured access!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating subadmin: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing an existing subadmin.
     */
    public function edit($id)
    {
        $this->authorizeAccess('edit');
        SubadminPermissionService::ensureSystemReady();

        $subadmin = Admin::with(['roles', 'permissions'])->findOrFail($id);

        // Disallow non-super admins from modifying Super Admin
        $currentAdmin = Auth::guard('admin')->user();
        if ($subadmin->is_super_admin && !$currentAdmin->is_super_admin) {
            abort(403, 'Only the Super Administrator can view or edit the Super Admin account.');
        }

        $modules = SubadminPermissionService::getModuleDefinitions();
        $presets = SubadminPermissionService::getPresets();
        $roles   = Role::where('name', '!=', 'super_admin')->get();
        $dbPermissions = Permission::all()->keyBy('name');

        // Gather currently granted permission names
        $assignedPermissionNames = $subadmin->getAllPermissions()->pluck('name')->toArray();

        return view('admin.subadmins.edit', compact(
            'subadmin', 'modules', 'presets', 'roles', 'dbPermissions', 'assignedPermissionNames'
        ));
    }

    /**
     * Update the specified subadmin in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeAccess('edit');
        SubadminPermissionService::ensureSystemReady();

        $subadmin = Admin::findOrFail($id);
        $currentAdmin = Auth::guard('admin')->user();

        // Disallow non-super admins from modifying Super Admin
        if ($subadmin->is_super_admin && !$currentAdmin->is_super_admin) {
            abort(403, 'Only the Super Administrator can update the Super Admin account.');
        }

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:200'],
            'email'                 => ['required', 'email', 'max:200', Rule::unique('admins', 'email')->ignore($subadmin->id)],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['nullable', 'string', 'min:6', 'confirmed'],
            'status'                => ['required', 'in:active,inactive,suspended'],
            'role_id'               => ['nullable', 'exists:roles,id'],
            'permissions'           => ['nullable', 'array'],
            'permissions.*'         => ['string'],
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'name'   => $validated['name'],
                'email'  => strtolower(trim($validated['email'])),
                'phone'  => $validated['phone'] ?? null,
            ];

            // Prevent deactivating root super admin
            if ($subadmin->is_super_admin) {
                $data['status'] = 'active';
            } else {
                $data['status'] = $validated['status'];
            }

            // Update password only if provided
            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $subadmin->update($data);

            // If not super admin, sync roles & permissions
            if (!$subadmin->is_super_admin) {
                // Sync Role
                if (!empty($validated['role_id'])) {
                    $subadmin->roles()->sync([$validated['role_id']]);
                } else {
                    $subadmin->roles()->detach();
                }

                // Sync Direct Permissions
                $permissionNames = $validated['permissions'] ?? [];
                $permissionIds = !empty($permissionNames)
                    ? Permission::whereIn('name', $permissionNames)->pluck('id')->toArray()
                    : [];

                $subadmin->permissions()->sync($permissionIds);
            }

            DB::commit();

            return redirect()->route('admin.subadmins.index')->with('success', "Subadmin '{$subadmin->name}' updated successfully!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating subadmin: ' . $e->getMessage());
        }
    }

    /**
     * Quick toggle status between active and inactive.
     */
    public function toggleStatus($id)
    {
        $this->authorizeAccess('edit');

        $subadmin = Admin::findOrFail($id);

        if ($subadmin->is_super_admin) {
            return back()->with('error', 'The Super Administrator account cannot be deactivated.');
        }

        $newStatus = ($subadmin->status === 'active') ? 'inactive' : 'active';
        $subadmin->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';
        return back()->with('success', "Subadmin '{$subadmin->name}' has been {$statusText}.");
    }

    /**
     * Fast modal/inline password reset for a subadmin.
     */
    public function resetPassword(Request $request, $id)
    {
        $this->authorizeAccess('edit');

        $subadmin = Admin::findOrFail($id);
        $currentAdmin = Auth::guard('admin')->user();

        if ($subadmin->is_super_admin && !$currentAdmin->is_super_admin) {
            return back()->with('error', 'You cannot reset the Super Administrator password.');
        }

        $request->validate([
            'new_password' => ['required', 'string', 'min:6'],
        ]);

        $subadmin->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return back()->with('success', "Password for '{$subadmin->name}' (Login: {$subadmin->email}) has been reset successfully.");
    }

    /**
     * Remove the specified subadmin from storage.
     */
    public function destroy($id)
    {
        $this->authorizeAccess('delete');

        $subadmin = Admin::findOrFail($id);
        $currentAdmin = Auth::guard('admin')->user();

        if ($subadmin->is_super_admin) {
            return back()->with('error', 'Critical Protection: Super Administrator cannot be deleted!');
        }

        if ($subadmin->id === $currentAdmin->id) {
            return back()->with('error', 'You cannot delete your own logged-in admin account.');
        }

        DB::beginTransaction();
        try {
            $name = $subadmin->name;
            $subadmin->roles()->detach();
            $subadmin->permissions()->detach();
            $subadmin->delete();

            DB::commit();

            return redirect()->route('admin.subadmins.index')->with('success', "Subadmin '{$name}' has been removed successfully.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting subadmin: ' . $e->getMessage());
        }
    }
}
