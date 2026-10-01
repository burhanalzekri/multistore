<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SystemHealthController extends Controller {
    public function index() {
        $checks = [];
        try { DB::select('select 1'); $checks['Database']=['ok'=>true,'detail'=>'متصل']; } catch (\Throwable $e) { $checks['Database']=['ok'=>false,'detail'=>'فشل الاتصال']; }
        try { Cache::put('_multistore_health',1,10); $checks['Cache']=['ok'=>Cache::get('_multistore_health')===1,'detail'=>'متاح']; } catch (\Throwable $e) { $checks['Cache']=['ok'=>false,'detail'=>'غير متاح']; }
        try { $checks['Storage']=['ok'=>Storage::disk(config('filesystems.default'))->exists('.'),'detail'=>'متاح']; } catch (\Throwable $e) { $checks['Storage']=['ok'=>false,'detail'=>'غير متاح']; }
        $checks['Queue']=['ok'=>config('queue.default') !== 'sync','detail'=>config('queue.default')];
        $checks['Mail']=['ok'=>(bool)config('mail.default'),'detail'=>config('mail.default')];
        $checks['Telegram']=['ok'=>(bool)config('services.telegram.bot_token'),'detail'=>config('services.telegram.bot_token') ? 'مهيأ' : 'غير مهيأ'];
        $checks['Push']=['ok'=>(bool)config('services.push.public_key'),'detail'=>config('services.push.public_key') ? 'مهيأ' : 'غير مهيأ'];
        return view('super-admin/system-health', compact('checks'));
    }
}
