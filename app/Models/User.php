<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;
    protected $guarded = [];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['password'=>'hashed', 'permissions'=>'array', 'is_active'=>'bool'];

    public function shop() { return $this->belongsTo(Shop::class); }

    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isShopAdmin(): bool  { return $this->role === 'shop_admin'; }
}
