<?php
namespace App\Http\Controllers;

use App\Models\PaymentTransaction;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = PaymentTransaction::with('order')->latest()->paginate(20);
        return view('dashboard.payments.index', compact('payments'));
    }
}
