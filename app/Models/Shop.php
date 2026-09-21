<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model {
    protected $guarded = [];
    protected $casts = ['settings'=>'array','trial_ends_at'=>'datetime'];

    public function users()    { return $this->hasMany(User::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function orders()   { return $this->hasMany(Order::class); }
    public function wallets()  { return $this->hasMany(PaymentWallet::class); }
    public function categories() { return $this->hasMany(Category::class); }
}
