<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_active' => 'bool'];

    public function product() { return $this->belongsTo(Product::class); }

    public function isLive(): bool {
        return $this->is_active
            && $this->starts_at->isPast()
            && $this->ends_at->isFuture()
            && ($this->max_qty === 0 || $this->sold_qty < $this->max_qty);
    }

    public function remainingSeconds(): int {
        return max(0, $this->ends_at->timestamp - now()->timestamp);
    }
}
