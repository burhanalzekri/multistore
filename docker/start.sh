#!/bin/bash
set -e
cd /var/www/html

echo "═══════════════════════════════════════"
echo "🚀 MultiStore — تشغيل الإنتاج"
echo "═══════════════════════════════════════"

# ═══ 1) Migrations ═══
echo "🗄️ Migrations..."
php artisan migrate --force --no-interaction 2>&1 || echo "⚠️ تحذير في migrate — نتجاوز"

# ═══ 1.5) Seeder (مناطق الشحن — آمن للتكرار) ═══
echo "🌱 Seeding shipping zones..."
php artisan db:seed --class=ShippingZonesSeeder --force 2>&1 || echo "⚠️ تحذير في seed — نتجاوز"

# ═══ 1.6) Super Admin Seeder (يعمل تلقائياً على كل Deploy) ═══
echo "🔑 Seeding super admin..."
php artisan db:seed --class=SuperAdminSeeder --force 2>&1 || echo "⚠️ تحذير في super admin seed — نتجاوز"

# ═══ 1.7) Fix Product Images (استبدال picsum بصور محلية) ═══
echo "🖼️ Fixing product images..."
php artisan db:seed --class=FixProductImagesSeeder --force 2>&1 || echo "⚠️ تحذير في product images seed — نتجاوز"

# ═══ 1.8) Settings Seeder (Landing + Marketing) ═══
echo "⚙️ Seeding settings..."
php artisan db:seed --class=SettingsSeeder --force 2>&1 || echo "⚠️ تحذير في settings seed — نتجاوز"

# ═══ 2) Caching ═══
echo "⚡ Cache..."
php artisan config:cache || true
php artisan route:clear || true
php artisan view:cache || true

# ═══ 3) Storage link ═══
echo "🔗 Storage link..."
php artisan storage:link || true

# ═══ 4) Apache ═══
echo "✅ تشغيل Apache على منفذ 80..."

# نستخدم منفذ 10000 (Render default)
PORT="${PORT:-10000}"
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf 2>/dev/null || true
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/000-default.conf 2>/dev/null || true

exec apache2-foreground
