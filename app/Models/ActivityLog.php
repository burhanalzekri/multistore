<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {
    protected $guarded = [];
    protected $casts = ['meta' => 'array'];

    public static function log($action, $description = null, $subjectType = null, $subjectId = null, $meta = null)
    {
        $user = auth()->user();
        self::create([
            'shop_id' => $user?->shop_id ?? app(\App\Services\Tenant\TenantManager::class)->id(),
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'النظام',
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'description' => $description,
            'meta' => $meta,
            'ip_address' => request()->ip(),
        ]);
    }
}
