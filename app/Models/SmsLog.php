<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $guarded = [];
    protected $casts = [
        'meta' => 'array',
        'sent_at' => 'datetime',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function scopePending($q) { return $q->where('status', 'pending'); }
    public function scopeSent($q) { return $q->where('status', 'sent'); }
    public function scopeFailed($q) { return $q->where('status', 'failed'); }
}
