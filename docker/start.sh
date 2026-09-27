#!/bin/bash
set -e
cd /var/www/html

if [ -z "$APP_KEY" ]; then
    echo "⚠️ APP_KEY غير معرّف"
fi

echo "🗄️ Migrations..."
php artisan migrate --force --no-interaction || echo "⚠️ فشل migrate"

echo "⚡ Cache..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "🔗 Storage link..."
php artisan storage:link || true

echo "✅ تشغيل Apache على المنفذ $PORT..."
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf 2>/dev/null || true

exec apache2-foreground
