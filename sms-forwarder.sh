#!/data/data/com.termux/files/usr/bin/bash

TOKEN="nIbjMCdSBBsuMeGLYAbq4LtPP0CGEN8KopZj301CAoX4m0ttTdkTx1KO0Pn0FKsk"
URL="http://127.0.0.1:8080/webhooks/sms/$TOKEN"
LAST_FILE="$HOME/multistore/storage/.last_sms_id"
LOG_FILE="$HOME/multistore/storage/logs/sms-forward.log"

mkdir -p "$(dirname "$LAST_FILE")" "$(dirname "$LOG_FILE")"

LAST=$(cat "$LAST_FILE" 2>/dev/null || echo 0)

termux-sms-list -l 30 -t inbox 2>/dev/null | jq -c '.[]?' | while read -r msg; do
    ID=$(echo "$msg" | jq -r '._id // empty')
    [ -z "$ID" ] && continue
    [ "$ID" -le "$LAST" ] && continue

    SENDER=$(echo "$msg" | jq -r '.number // ""')
    BODY=$(echo "$msg" | jq -r '.body // ""')
    [ -z "$BODY" ] && continue

    PAYLOAD=$(jq -n --arg s "$SENDER" --arg m "$BODY" '{sender:$s,message:$m}')

    RESPONSE=$(curl -s -X POST "$URL" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        -d "$PAYLOAD" 2>&1)

    echo "[$(date '+%Y-%m-%d %H:%M:%S')] SMS #$ID from $SENDER → $RESPONSE" >> "$LOG_FILE"
    echo "$ID" > "$LAST_FILE"
done
