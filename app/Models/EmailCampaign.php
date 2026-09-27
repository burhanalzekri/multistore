<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $guarded = [];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    // ═══ العلاقات ═══
    public function recipients()
    {
        return $this->hasMany(EmailCampaignRecipient::class, 'campaign_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // ═══ Scopes ═══
    public function scopeForShop($query, $shopId)
    {
        return $query->where('shop_id', $shopId);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    // ═══ Helpers ═══
    public function progressPct(): int
    {
        if ($this->recipients_count <= 0) return 0;
        return (int) round(($this->sent_count / $this->recipients_count) * 100);
    }

    public function openRatePct(): int
    {
        if ($this->sent_count <= 0) return 0;
        return (int) round(($this->opens_count / $this->sent_count) * 100);
    }

    public function clickRatePct(): int
    {
        if ($this->sent_count <= 0) return 0;
        return (int) round(($this->clicks_count / $this->sent_count) * 100);
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'scheduled' => 'مجدولة',
            'sending' => 'جاري الإرسال',
            'sent' => 'تم الإرسال',
            'failed' => 'فاشلة',
            default => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'draft' => '#64748b',
            'scheduled' => '#3b82f6',
            'sending' => '#f59e0b',
            'sent' => '#10b981',
            'failed' => '#dc2626',
            default => '#64748b',
        };
    }
}
