#!/data/data/com.termux/files/usr/bin/bash

DATE=$(date +%Y%m%d_%H%M)
BACKUP_DIR="$HOME/multistore-backups"
PROJECT_DIR="$HOME/multistore"

mkdir -p "$BACKUP_DIR"

echo "🔵 [$DATE] بدء النسخ..."

# قاعدة البيانات فقط (الأهم)
if [ -f "$PROJECT_DIR/database/database.sqlite" ]; then
    cp "$PROJECT_DIR/database/database.sqlite" "$BACKUP_DIR/db_$DATE.sqlite"
    echo "✅ قاعدة البيانات"
fi

# الملفات الأساسية فقط (بدون الصور)
cd "$PROJECT_DIR"
tar -czf "$BACKUP_DIR/code_$DATE.tar.gz" \
    app config routes resources database \
    2>/dev/null
echo "✅ الكود"

# احذف الأقدم من 7 أيام
find "$BACKUP_DIR" -type f -mtime +7 -delete 2>/dev/null

TOTAL=$(du -sh "$BACKUP_DIR" 2>/dev/null | cut -f1)
echo ""
echo "🎉 اكتمل — الحجم: $TOTAL"
ls -lh "$BACKUP_DIR" | tail -5
