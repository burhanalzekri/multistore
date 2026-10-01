#!/bin/bash
set -e
cd /var/www/html

echo "═══════════════════════════════════════"
echo "🚀 MultiStore — تشغيل الإنتاج"
echo "═══════════════════════════════════════"

# ═══ 1) Migrations (سريعة — في المقدمة) ═══
echo "🗄️ Migrations..."
php artisan migrate --force --no-interaction 2>&1 | tail -20 || echo "⚠️ تحذير في migrate"

# ═══ 2) Caching (سريع) ═══
echo "⚡ Cache..."
php artisan config:cache || true
php artisan route:clear || true
php artisan view:cache || true

# ═══ 3) Storage link ═══
echo "🔗 Storage link..."
php artisan storage:link || true

# ═══ 4) Seeders (في الخلفية — لا تحجب Apache) ═══
echo "🌱 تشغيل Seeders في الخلفية..."
(
    sleep 5
    echo "[BG] $(date) — بدء Seeders" >> storage/logs/seeders.log
    php artisan db:seed --class=ShippingZonesSeeder --force >> storage/logs/seeders.log 2>&1
    php artisan db:seed --class=SuperAdminSeeder --force >> storage/logs/seeders.log 2>&1
    php artisan db:seed --class=FixProductImagesSeeder --force >> storage/logs/seeders.log 2>&1
    php artisan db:seed --class=SettingsSeeder --force >> storage/logs/seeders.log 2>&1
    echo "[BG] $(date) — انتهت Seeders" >> storage/logs/seeders.log
) &

# ═══ 5) Apache (يبدأ فوراً) ═══
echo "✅ تشغيل Apache على المنفذ 80..."

PORT="${PORT:-10000}"
echo "🌐 PORT=${PORT}"

sed -i "s/Listen [0-9]*/Listen ${PORT}/g" /etc/apache2/ports.conf 2>/dev/null || true
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/000-default.conf 2>/dev/null || true

echo "🔍 فحص إعدادات Apache..."
grep "Listen" /etc/apache2/ports.conf 2>/dev/null || echo "(لم يُعدّل)"
apache2ctl configtest 2>&1 || true

echo "▶️ بدء Apache..."
exec apache2-foreground
