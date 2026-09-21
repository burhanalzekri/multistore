<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsPattern extends Model {
    protected $guarded = [];
    protected $casts = ['keywords'=>'array','is_active'=>'bool'];
}
