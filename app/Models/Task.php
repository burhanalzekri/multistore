<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Task extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['due_date' => 'datetime', 'completed_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
}
