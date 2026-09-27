<?php
namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index() {
        $addresses = CustomerAddress::where('user_id', Auth::id())->latest()->get();
        return view('customer.addresses', compact('addresses'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'label' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'city' => 'required|string|max:100',
            'area' => 'required|string|max:100',
            'address' => 'required|string',
        ]);
        $data['user_id'] = Auth::id();

        if ($request->boolean('is_default')) {
            CustomerAddress::where('user_id', Auth::id())->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        CustomerAddress::create($data);
        return back()->with('success', 'تم إضافة العنوان');
    }

    public function update(Request $request, CustomerAddress $address) {
        if ($address->user_id !== Auth::id()) abort(403);
        $data = $request->validate([
            'label' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'city' => 'required|string|max:100',
            'area' => 'required|string|max:100',
            'address' => 'required|string',
        ]);
        if ($request->boolean('is_default')) {
            CustomerAddress::where('user_id', Auth::id())->update(['is_default' => false]);
            $data['is_default'] = true;
        }
        $address->update($data);
        return back()->with('success', 'تم التحديث');
    }

    public function destroy(CustomerAddress $address) {
        if ($address->user_id !== Auth::id()) abort(403);
        $address->delete();
        return back()->with('success', 'تم الحذف');
    }

    public function setDefault(CustomerAddress $address) {
        if ($address->user_id !== Auth::id()) abort(403);
        CustomerAddress::where('user_id', Auth::id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return back()->with('success', 'تم التعيين');
    }
}
