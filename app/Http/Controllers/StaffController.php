<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    private $permissionsList = [
        'products.view' => 'عرض المنتجات',
        'products.edit' => 'تعديل المنتجات',
        'orders.view' => 'عرض الطلبات',
        'orders.edit' => 'تعديل الطلبات (الحالة)',
        'orders.ship' => 'شحن الطلبات',
        'orders.deliver' => 'تأكيد التسليم',
        'orders.cancel' => 'إلغاء الطلبات',
        'sms.view' => 'عرض رسائل SMS',
        'reports.view' => 'عرض التقارير',
        'settings.edit' => 'تعديل الإعدادات',
    ];

    public function index()
    {
        $staff = User::whereIn('role', ['staff', 'shop_admin'])
            ->where('id', '!=', auth()->id())
            ->latest()
            ->get();

        $permissionsList = $this->permissionsList;
        return view('dashboard.staff.index', compact('staff', 'permissionsList'));
    }

    public function create()
    {
        $permissionsList = $this->permissionsList;
        return view('dashboard.staff.create', compact('permissionsList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:6',
            'role' => 'required|in:staff,shop_admin',
            'permissions' => 'nullable|array',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['shop_id'] = auth()->user()->shop_id;
        $data['created_by'] = auth()->id();
        $data['is_active'] = true;

        $user = User::create($data);

        ActivityLog::log('staff.created', 'تم إضافة موظف جديد: ' . $user->name, 'User', $user->id);

        return redirect('/dashboard/staff')->with('success', '✅ تم إضافة الموظف');
    }

    public function edit(User $staff)
    {
        if ($staff->shop_id !== auth()->user()->shop_id) abort(403);
        $permissionsList = $this->permissionsList;
        return view('dashboard.staff.edit', compact('staff', 'permissionsList'));
    }

    public function update(Request $request, User $staff)
    {
        if ($staff->shop_id !== auth()->user()->shop_id) abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:staff,shop_admin',
            'permissions' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($data['password'] ?? null) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $staff->update($data);

        ActivityLog::log('staff.updated', 'تم تحديث الموظف: ' . $staff->name, 'User', $staff->id);

        return redirect('/dashboard/staff')->with('success', '✅ تم التحديث');
    }

    public function destroy(User $staff)
    {
        if ($staff->shop_id !== auth()->user()->shop_id) abort(403);

        ActivityLog::log('staff.deleted', 'تم حذف الموظف: ' . $staff->name, 'User', $staff->id);
        $staff->delete();

        return back()->with('success', 'تم الحذف');
    }
}
