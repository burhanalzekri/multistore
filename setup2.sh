#!/data/data/com.termux/files/usr/bin/bash
cd ~/multistore

echo "📝 [1/8] TenantManager..."
cat > app/Services/Tenant/TenantManager.php << 'EOF'
<?php
namespace App\Services\Tenant;

use App\Models\Shop;

class TenantManager {
    private ?Shop $shop = null;
    public function set(Shop $shop): void { $this->shop = $shop; }
    public function get(): ?Shop { return $this->shop; }
    public function id(): ?int { return $this->shop?->id; }
    public function check(): bool { return $this->shop !== null; }
    public function clear(): void { $this->shop = null; }
}
EOF

echo "📝 [2/8] BelongsToTenant trait..."
cat > app/Models/Concerns/BelongsToTenant.php << 'EOF'
<?php
namespace App\Models\Concerns;

use App\Services\Tenant\TenantManager;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant {
    public static function bootBelongsToTenant(): void {
        static::addGlobalScope('tenant', function (Builder $b) {
            $t = app(TenantManager::class);
            if ($t->check() && !app()->bound('tenant.bypass')) {
                $b->where($b->getModel()->getTable().'.shop_id', $t->id());
            }
        });
        static::creating(function ($m) {
            $t = app(TenantManager::class);
            if ($t->check() && empty($m->shop_id)) {
                $m->shop_id = $t->id();
            }
        });
    }
}
EOF

echo "📝 [3/8] Shop Model..."
cat > app/Models/Shop.php << 'EOF'
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
EOF

echo "📝 [4/8] Product Model..."
cat > app/Models/Product.php << 'EOF'
<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['is_active'=>'bool','images'=>'array'];

    public function category() { return $this->belongsTo(Category::class); }
}
EOF

echo "📝 [5/8] Order Model..."
cat > app/Models/Order.php << 'EOF'
<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['paid_at'=>'datetime'];

    public function items() { return $this->hasMany(OrderItem::class); }
    public function shop()  { return $this->belongsTo(Shop::class); }

    public static function generateNumber(): string {
        return 'ORD-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));
    }
}
EOF

echo "📝 [6/8] باقي Models..."
cat > app/Models/OrderItem.php << 'EOF'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    protected $guarded = [];
    public $timestamps = false;
}
EOF

cat > app/Models/Category.php << 'EOF'
<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use BelongsToTenant;
    protected $guarded = [];
}
EOF

cat > app/Models/PaymentWallet.php << 'EOF'
<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PaymentWallet extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['is_active'=>'bool'];
}
EOF

cat > app/Models/SmsInbox.php << 'EOF'
<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class SmsInbox extends Model {
    use BelongsToTenant;
    protected $table = 'sms_inbox';
    protected $guarded = [];
    protected $casts = ['received_at'=>'datetime'];
}
EOF

cat > app/Models/SmsPattern.php << 'EOF'
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsPattern extends Model {
    protected $guarded = [];
    protected $casts = ['keywords'=>'array','is_active'=>'bool'];
}
EOF

cat > app/Models/PaymentTransaction.php << 'EOF'
<?php
namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model {
    use BelongsToTenant;
    protected $guarded = [];
    protected $casts = ['verified_at'=>'datetime'];
}
EOF

echo "📝 [7/8] User Model محدّث..."
cat > app/Models/User.php << 'EOF'
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;
    protected $guarded = [];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['password'=>'hashed'];

    public function shop() { return $this->belongsTo(Shop::class); }

    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isShopAdmin(): bool  { return $this->role === 'shop_admin'; }
}
EOF

echo "📝 [8/8] Seeder لأنماط SMS..."
cat > database/seeders/SmsPatternSeeder.php << 'EOF'
<?php
namespace Database\Seeders;

use App\Models\SmsPattern;
use Illuminate\Database\Seeder;

class SmsPatternSeeder extends Seeder {
    public function run(): void {
        SmsPattern::create([
            'provider' => 'kuraimi',
            'label' => 'استلام مبلغ الكريمي',
            'amount_regex' => '/مبلغ\s*:?\s*([\d,\.]+)/u',
            'sender_regex' => '/(7\d{8})/u',
            'reference_regex' => '/(?:رقم العملية|المرجع)\s*:?\s*(\d{4,})/u',
            'keywords' => ['الكريمي'],
            'is_active' => true,
        ]);
        SmsPattern::create([
            'provider' => 'jawali',
            'label' => 'إيداع جوالي',
            'amount_regex' => '/(?:إيداع|مبلغ)\s*([\d,\.]+)/u',
            'sender_regex' => '/(7\d{8})/u',
            'reference_regex' => '/(\d{6,})/',
            'keywords' => ['جوالي'],
            'is_active' => true,
        ]);
    }
}
EOF

echo ""
echo "════════════════════════════════"
echo "✅ كل الملفات أُنشئت بنجاح!"
echo "════════════════════════════════"
