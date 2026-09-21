<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PaymentWallet extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['is_active'=>'bool'];
}
