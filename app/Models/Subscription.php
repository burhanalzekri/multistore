<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {
    protected $guarded = [];
    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'trial_ends_at' => 'datetime'];

    public function shop() { return $this->belongsTo(Shop::class); }
    public function plan() { return $this->belongsTo(Plan::class); }

    public function isActive(): bool {
        if ($this->status === 'cancelled' || $this->status === 'expired') return false;
        if ($this->ends_at && $this->ends_at->isPast()) return false;
        return true;
    }

    public function daysRemaining(): int {
        if (!$this->ends_at) return 0;
        return max(0, now()->diffInDays($this->ends_at, false));
    }
}
