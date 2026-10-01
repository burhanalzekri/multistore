<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailCampaignRecipient extends Model
{
    protected $guarded = [];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'clicked_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(EmailCampaign::class, 'campaign_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending' => 'في الانتظار',
            'sent' => 'تم الإرسال',
            'failed' => 'فشل',
            'opened' => 'تم الفتح',
            'clicked' => 'تم النقر',
            default => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'pending' => '#94a3b8',
            'sent' => '#10b981',
            'failed' => '#dc2626',
            'opened' => '#3b82f6',
            'clicked' => '#8b5cf6',
            default => '#94a3b8',
        };
    }
}
