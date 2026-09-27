<div class="admin-card" style="padding:20px;margin-bottom:16px;" id="maintenanceCard">
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
    <div style="display:flex;align-items:center;gap:12px;">
      <div style="width:44px;height:44px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:22px;">🛠️</div>
      <div>
        <div style="font-weight:900;font-size:15px;color:var(--text);">وضع الصيانة</div>
        <div style="font-size:12px;color:var(--text-muted);" id="maintenanceStatus">جاري الفحص...</div>
      </div>
    </div>
    <button onclick="toggleMaintenance()" id="maintenanceBtn" style="background:#fbbf24;color:white;border:none;padding:10px 20px;border-radius:10px;font-weight:900;font-size:13px;cursor:pointer;font-family:inherit;">
      ⏳ جاري...
    </button>
  </div>
</div>

<script>
function checkMaintenance() {
    fetch("/api/maintenance/status")
        .then(r => r.json())
        .then(data => {
            const status = document.getElementById("maintenanceStatus");
            const btn = document.getElementById("maintenanceBtn");
            if (!status || !btn) return;
            if (data.active) {
                status.textContent = "🔴 الموقع مغلق حاليًا";
                status.style.color = "#ef4444";
                btn.textContent = "🟢 تفعيل الموقع";
                btn.style.background = "#10b981";
            } else {
                status.textContent = "✅ الموقع يعمل بشكل طبيعي";
                status.style.color = "#10b981";
                btn.textContent = "🔴 تفعيل الصيانة";
                btn.style.background = "#ef4444";
            }
        })
        .catch(() => {});
}

function toggleMaintenance() {
    fetch("/api/maintenance/status").then(r => r.json()).then(data => {
        const willActivate = !data.active;
        const message = willActivate ? prompt("رسالة الصيانة:", "نعمل على تحسينات — سنعود قريبًا") : null;
        if (willActivate && message === null) return;

        fetch("/api/maintenance/toggle", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]")?.content || ""
            },
            body: JSON.stringify({ active: willActivate, message: message })
        }).then(() => checkMaintenance());
    });
}

if (document.getElementById("maintenanceCard")) {
    checkMaintenance();
    setInterval(checkMaintenance, 10000);
}
</script>
