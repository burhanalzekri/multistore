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

    /**
     * 🏷️ تسميات Variants الافتراضية للمتجر
     */
    public function variantLabels(): array
    {
        $defaults = [
            'label_1' => 'اللون',
            'label_2' => 'المقاس',
            'icon_1' => '🎨',
            'icon_2' => '📏',
        ];
        $settings = is_array($this->settings) ? $this->settings : [];
        return [
            'label_1' => $settings['variant_label_1'] ?? $defaults['label_1'],
            'label_2' => $settings['variant_label_2'] ?? $defaults['label_2'],
            'icon_1'  => $settings['variant_icon_1']  ?? $defaults['icon_1'],
            'icon_2'  => $settings['variant_icon_2']  ?? $defaults['icon_2'],
        ];
    }

    public function variantLabel1(): string { return $this->variantLabels()['label_1']; }
    public function variantLabel2(): string { return $this->variantLabels()['label_2']; }
    public function variantIcon1(): string  { return $this->variantLabels()['icon_1']; }
    public function variantIcon2(): string  { return $this->variantLabels()['icon_2']; }

}
