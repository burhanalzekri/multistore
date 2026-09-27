#!/bin/bash
set -e
cd /var/www/html

echo "═══════════════════════════════════════"
echo "🚀 MultiStore — تشغيل الإنتاج"
echo "═══════════════════════════════════════"

# ═══ 1) Migrations ═══
echo "🗄️ Migrations..."
php artisan migrate --force --no-interaction 2>&1 || echo "⚠️ تحذير في migrate"

# ═══ 2) Caching ═══
echo "⚡ Cache..."
php artisan config:cache || true
php artisan route:clear || true
php artisan view:cache || true

# ═══ 3) Storage link ═══
echo "🔗 Storage link..."
php artisan storage:link || true

# ═══ 4) Apache على منفذ 10000 (Render) ═══
PORT="${PORT:-10000}"
echo "✅ تشغيل Apache على منفذ $PORT..."

# نُحدّث Ports
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf

# نُحدّث VirtualHost
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
