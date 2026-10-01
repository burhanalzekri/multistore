{{-- Partial: _templates.blade.php --}}
<style>
  .sms-wrapper { padding: 20px; max-width: 1400px; margin: 0 auto; }
  .sms-split { display: grid; grid-template-columns: 320px 1fr; height: min(calc(100vh - 160px), 680px); background: #fff; border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(15,23,42,.06); }
  .sms-sidebar { background: #f8fafc; border-inline-end: 1px solid #e2e8f0; display: flex; flex-direction: column; }
  .sms-search { padding: 14px; border-bottom: 1px solid #e2e8f0; }
  .sms-search input { width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 12px; font-family: inherit; font-size: 13px; }
  .sms-search input:focus { outline: none; border-color: #f97316; }
  .sms-filters { padding: 10px 14px; border-bottom: 1px solid #e2e8f0; display: flex; flex-wrap: wrap; gap: 6px; }
  .sms-filter-group { display: inline-flex; background: #e2e8f0; border-radius: 10px; padding: 3px; gap: 2px; }
  .sms-filter-group button { padding: 5px 10px; border: 0; background: transparent; border-radius: 8px; font-family: inherit; font-size: 11px; font-weight: 700; color: #64748b; cursor: pointer; }
  .sms-filter-group button.active { background: #fff; color: #0f172a; box-shadow: 0 1px 3px rgba(15,23,42,.1); }
  .sms-filter-count { margin-inline-start: auto; font-size: 11px; color: #94a3b8; font-weight: 700; padding-inline-start: 8px; align-self: center; }
  .sms-date-select { padding: 5px 10px; border: 0; background: transparent; border-radius: 8px; font-family: inherit; font-size: 11px; font-weight: 700; color: #64748b; cursor: pointer; outline: none; }
  .sms-date-select option { background: #fff; color: #0f172a; }
  .sms-item-date { font-size: 10px; color: #94a3b8; font-weight: 600; flex-shrink: 0; white-space: nowrap; }
  .sms-item.custom .sms-dot.on { background: #f59e0b; }
  .sms-list { flex: 1; overflow-y: auto; }
  .sms-empty { padding: 40px 20px; text-align: center; color: #94a3b8; font-size: 13px; }
  .sms-item { padding: 14px 18px; cursor: pointer; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 10px; border-inline-start: 4px solid transparent; transition: background .2s; }
  .sms-item:hover { background: #fff; }
  .sms-item.active { background: #fff; border-inline-start-color: #f97316; }
  .sms-item.hidden { display: none; }
  .sms-dot { width: 8px; height: 8px; border-radius: 99px; background: #cbd5e1; flex-shrink: 0; }
  .sms-dot.on { background: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.15); }
  .sms-editor { display: flex; flex-direction: column; overflow: hidden; }
  .sms-editor-header { padding: 16px 22px; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
  .sms-editor-body { flex: 1; overflow-y: auto; padding: 22px; }
  .sms-editor-footer { border-top: 1px solid #e2e8f0; padding: 14px 22px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; background: #fff; }
  .sms-badge { padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; }
  .sms-badge.custom { background: #dbeafe; color: #1e40af; }
  .sms-badge.default { background: #f1f5f9; color: #64748b; }
  .sms-label { display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 8px; }
  .sms-textarea { width: 100%; padding: 16px; min-height: 85px; border: 2px solid #e2e8f0; border-radius: 14px; font-family: inherit; font-size: 14px; line-height: 1.7; resize: vertical; }
  .sms-textarea:focus { outline: none; border-color: #f97316; }
  .sms-vars-help { background: #fffbeb; border: 1px solid #fde68a; border-radius: 14px; padding: 14px 16px; margin-top: 16px; }
  .sms-vars-help strong { color: #92400e; font-size: 13px; display: block; margin-bottom: 10px; }
  .sms-var { display: inline-block; padding: 4px 10px; background: #fff; border: 1px solid #fde68a; border-radius: 8px; font-size: 11px; color: #92400e; cursor: pointer; margin: 3px 4px 3px 0; }
  .sms-var:hover { background: #fef3c7; }
  .sms-var code { font-weight: 700; }
  .sms-preview { background: linear-gradient(135deg, #f1f5f9, #e2e8f0); padding: 18px; border-radius: 16px; margin-top: 18px; }
  .sms-preview-title { font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 10px; }
  .sms-bubble { background: #fff; padding: 14px 18px; border-radius: 18px 18px 18px 6px; max-width: 500px; box-shadow: 0 2px 10px rgba(15,23,42,.06); font-size: 14px; line-height: 1.7; color: #1e293b; word-wrap: break-word; }
  .sms-counter { font-size: 11px; color: #94a3b8; margin-top: 6px; text-align: end; font-family: monospace; }
  .sms-btn { padding: 10px 20px; border-radius: 12px; border: 0; font-weight: 900; font-size: 13px; cursor: pointer; font-family: inherit; }
  .sms-btn-primary { background: linear-gradient(135deg, #f59e0b, #f97316); color: #fff; box-shadow: 0 6px 16px rgba(249,115,22,.3); }
  .sms-btn-ghost { background: #f1f5f9; color: #334155; }
  .sms-btn-danger { background: #fee2e2; color: #991b1b; }
  .sms-back-btn { display: none; padding: 6px 12px; background: #f1f5f9; border: 0; border-radius: 10px; font-weight: 900; cursor: pointer; font-family: inherit; }
  @media (max-width: 768px) {
    .sms-wrapper { padding: 10px; }
    .sms-split { grid-template-columns: 1fr; height: auto; min-height: 70vh; }
    .sms-sidebar { max-height: 55vh; }
    .sms-split.editing .sms-sidebar { display: none; }
    .sms-split:not(.editing) .sms-editor { display: none; }
    .sms-back-btn { display: inline-block; }
  }
</style>

<div class="sms-wrapper">
  <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;flex-wrap:wrap">
    <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#f59e0b,#f97316);display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px">📧</div>
    <div style="flex:1;min-width:200px">
      <h1 style="font-size:20px;font-weight:900;margin:0">قوالب رسائل SMS</h1>
      <p style="color:#64748b;margin:4px 0 0;font-size:12px">خصّص الرسائل التي تُرسل لعملائك تلقائياً</p>
    </div>
  </div>

  @if(session("success"))
    <div style="padding:12px 16px;background:#dcfce7;color:#166534;border-radius:12px;margin-bottom:14px;font-weight:700;font-size:13px">{{ session("success") }}</div>
  @endif

  <div class="sms-split" id="smsSplit">
    <div class="sms-sidebar">
      <div class="sms-search">
        <input type="text" id="smsSearch" placeholder="🔍 ابحث عن قالب..." autocomplete="off">
      </div>
      <div class="sms-filters">
        <div class="sms-filter-group" data-filter="status">
          <button type="button" data-value="all" class="active">الكل</button>
          <button type="button" data-value="on">● مفعّل</button>
          <button type="button" data-value="off">○ معطّل</button>
        </div>
        <div class="sms-filter-group" data-filter="type">
          <button type="button" data-value="all" class="active">الكل</button>
          <button type="button" data-value="custom">مخصّص</button>
          <button type="button" data-value="default">افتراضي</button>
        </div>
        <div class="sms-filter-group sms-date-filter" data-filter="date">
          <select id="smsDateFilter" class="sms-date-select">
            <option value="all">📅 الكل</option>
            <option value="today">اليوم</option>
            <option value="7days">آخر 7 أيام</option>
            <option value="30days">آخر 30 يوم</option>
            <option value="older">أقدم من 30</option>
          </select>
        </div>
        <span class="sms-filter-count" id="smsCount">—</span>
      </div>
      <div class="sms-list" id="smsList">
        <div class="sms-empty" id="smsEmpty" style="display:none">لا توجد نتائج مطابقة</div>
        @foreach($events as $eventKey => $event)
          @php $tpl = $templates[$eventKey] ?? null; $isActive = $tpl?->is_active ?? true; @endphp
          <div class="sms-item {{ $loop->first ? 'active' : '' }} {{ $tpl ? 'custom' : '' }}"
               data-key="{{ $eventKey }}"
               data-label="{{ $event['label'] }}"
               data-custom="{{ $tpl ? '1' : '0' }}"
               data-updated="{{ $tpl?->updated_at?->timestamp ?? 0 }}">
            <span class="sms-dot {{ $isActive ? 'on' : '' }}"></span>
            <span style="flex:1;font-weight:700;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $event['label'] }}</span>
            @if($tpl?->updated_at)
              <span class="sms-item-date">{{ $tpl->updated_at->diffForHumans() }}</span>
            @endif
          </div>
        @endforeach
      </div>
    </div>

    <div class="sms-editor" id="smsEditor">
      <div class="sms-editor-header">
        <button type="button" class="sms-back-btn" onclick="backToList()">→</button>
        <div style="width:38px;height:38px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;font-size:18px">📦</div>
        <div style="flex:1;min-width:150px">
          <div id="editLabel" style="font-weight:900;font-size:15px">—</div>
          <code id="editKey" style="font-size:11px;color:#94a3b8">—</code>
        </div>
        <span id="editBadge" class="sms-badge default">افتراضي</span>
      </div>

      <div class="sms-editor-body">
        <form id="smsForm" method="POST" action="">
          @csrf
          <label class="sms-label">نص الرسالة</label>
          <textarea name="body" id="smsBody" maxlength="500" class="sms-textarea" placeholder="اكتب نص الرسالة..."></textarea>
          <div class="sms-counter"><span id="smsCount">0</span>/500</div>

          <div class="sms-vars-help">
            <strong>💡 المتغيرات المتاحة — اضغط للإضافة</strong>
            @foreach($vars as $key => $label)
              <span class="sms-var" onclick="insertVar('{{ $key }}')">
                <code>{{ "{" . $key . "}" }}</code>
                <span style="color:#a16207;margin-inline-start:6px">{{ $label }}</span>
              </span>
            @endforeach
          </div>

          <div class="sms-preview">
            <div class="sms-preview-title">📱 معاينة مباشرة (ببيانات نموذجية)</div>
            <div class="sms-bubble" id="smsBubble">—</div>
          </div>
        </form>

        <form id="smsResetForm" method="POST" action="" style="display:none">@csrf</form>
      </div>

      <div class="sms-editor-footer">
        <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;font-weight:700;color:#475569">
          <input type="checkbox" id="smsActive" form="smsForm" name="is_active" value="1" checked>
          <span>مُفعّل</span>
        </label>
        <div style="flex:1"></div>
        <button type="button" id="resetBtn" class="sms-btn sms-btn-danger" onclick="resetTemplate()" style="display:none">↺ استعادة</button>
        <button type="button" class="sms-btn sms-btn-ghost" onclick="updatePreview()">👁 معاينة</button>
        <button type="submit" form="smsForm" class="sms-btn sms-btn-primary">💾 حفظ</button>
      </div>
    </div>
  </div>
</div>

<script>
const TEMPLATES = {
@foreach($events as $eventKey => $event)
  @php $tpl = $templates[$eventKey] ?? null; @endphp
  "{{ $eventKey }}": {
    label: @json($event['label']),
    body: @json($tpl?->body ?? $event['default']),
    active: @json((bool) ($tpl?->is_active ?? true)),
    custom: @json($tpl !== null)
  },
@endforeach
};

const SAMPLE = {
  order_number: "ORD-1024",
  customer_name: "محمد أحمد",
  customer_phone: "777123456",
  total: "24,850",
  shop_name: @json($shop->name ?? "متجري"),
  status: "shipped",
  points: "150"
};

let currentKey = null;
const split = document.getElementById("smsSplit");
const bodyEl = document.getElementById("smsBody");
const countEl = document.getElementById("smsCount");
const bubbleEl = document.getElementById("smsBubble");
const activeEl = document.getElementById("smsActive");
const badgeEl = document.getElementById("editBadge");
const labelEl = document.getElementById("editLabel");
const keyEl = document.getElementById("editKey");
const formEl = document.getElementById("smsForm");
const resetFormEl = document.getElementById("smsResetForm");
const resetBtn = document.getElementById("resetBtn");
const listEl = document.getElementById("smsList");

function selectTemplate(key) {
  if (!TEMPLATES[key]) return;
  currentKey = key;
  const t = TEMPLATES[key];
  document.querySelectorAll(".sms-item").forEach(el => el.classList.toggle("active", el.dataset.key === key));
  labelEl.textContent = t.label;
  keyEl.textContent = key;
  bodyEl.value = t.body;
  activeEl.checked = t.active;
  badgeEl.textContent = t.custom ? "مخصّص" : "افتراضي";
  badgeEl.className = "sms-badge " + (t.custom ? "custom" : "default");
  resetBtn.style.display = t.custom ? "inline-block" : "none";
  formEl.action = "/dashboard/sms-templates/" + key;
  resetFormEl.action = "/dashboard/sms-templates/" + key + "/reset";
  updateCount();
  updatePreview();
  if (window.innerWidth <= 768) split.classList.add("editing");
}

function backToList() { split.classList.remove("editing"); }
function updateCount() { countEl.textContent = bodyEl.value.length; }
function render(t) {
  let r = t;
  for (const k in SAMPLE) r = r.split("{" + k + "}").join(SAMPLE[k]);
  return r;
}
function updatePreview() { bubbleEl.textContent = render(bodyEl.value) || "—"; }
function insertVar(key) {
  const s = bodyEl.selectionStart, e = bodyEl.selectionEnd;
  const ins = "{" + key + "}";
  bodyEl.value = bodyEl.value.slice(0, s) + ins + bodyEl.value.slice(e);
  bodyEl.focus();
  bodyEl.selectionStart = bodyEl.selectionEnd = s + ins.length;
  updateCount(); updatePreview();
}
function resetTemplate() {
  if (confirm("استعادة القالب الافتراضي؟")) resetFormEl.submit();
}

bodyEl.addEventListener("input", () => { updateCount(); updatePreview(); });
activeEl.addEventListener("change", () => {
  const item = document.querySelector(".sms-item[data-key=" + JSON.stringify(currentKey) + "]");
  if (item) item.querySelector(".sms-dot").classList.toggle("on", activeEl.checked);
});
listEl.addEventListener("click", e => {
  const item = e.target.closest(".sms-item");
  if (item) selectTemplate(item.dataset.key);
});
// ═══ الفلترة ═══
const filterState = { status: "all", type: "all", date: "all", q: "" };

function matchesDateFilter(updatedTs, isCustom, filter) {
  if (filter === "all") return true;
  if (!isCustom || !updatedTs) return false;

  const now = Math.floor(Date.now() / 1000);
  const diffDays = (now - updatedTs) / 86400;

  switch (filter) {
    case "today":   return diffDays < 1;
    case "7days":   return diffDays < 7;
    case "30days":  return diffDays < 30;
    case "older":   return diffDays >= 30;
    default:        return true;
  }
}

function applyFilters() {
  const items = document.querySelectorAll(".sms-item");
  let visible = 0;

  items.forEach(el => {
    const key = el.dataset.key;
    const t = TEMPLATES[key];
    if (!t) return;

    const q = filterState.q;
    const matchSearch = !q ||
      t.label.toLowerCase().includes(q) ||
      key.toLowerCase().includes(q);

    const matchStatus =
      filterState.status === "all" ||
      (filterState.status === "on" && t.active) ||
      (filterState.status === "off" && !t.active);

    const matchType =
      filterState.type === "all" ||
      (filterState.type === "custom" && t.custom) ||
      (filterState.type === "default" && !t.custom);

    const isCustom = el.dataset.custom === "1";
    const updatedTs = parseInt(el.dataset.updated, 10) || 0;
    const matchDate = matchesDateFilter(updatedTs, isCustom, filterState.date);

    const show = matchSearch && matchStatus && matchType && matchDate;
    el.classList.toggle("hidden", !show);
    if (show) visible++;
  });

  // مؤشر العدد
  const counterEl = document.getElementById("smsCount");
  if (counterEl) counterEl.textContent = visible + " قالب";

  // رسالة "لا توجد نتائج"
  const emptyEl = document.getElementById("smsEmpty");
  if (emptyEl) emptyEl.style.display = visible === 0 ? "block" : "none";

  // إن كان القالب الحالي مخفياً — اختر أول مرئي
  const currentItem = document.querySelector(".sms-item.active");
  if (currentItem && currentItem.classList.contains("hidden")) {
    const firstVisible = document.querySelector(".sms-item:not(.hidden)");
    if (firstVisible) selectTemplate(firstVisible.dataset.key);
  }
}

// أحداث البحث
document.getElementById("smsSearch").addEventListener("input", e => {
  filterState.q = e.target.value.trim().toLowerCase();
  applyFilters();
});

// أحداث فلاتر الأزرار
document.querySelectorAll(".sms-filter-group").forEach(group => {
  if (group.dataset.filter === "date") return;
  group.addEventListener("click", e => {
    const btn = e.target.closest("button");
    if (!btn) return;
    const filterName = group.dataset.filter;
    filterState[filterName] = btn.dataset.value;
    group.querySelectorAll("button").forEach(b => b.classList.toggle("active", b === btn));
    applyFilters();
  });
});

// فلتر التاريخ
const dateFilterEl = document.getElementById("smsDateFilter");
if (dateFilterEl) {
  dateFilterEl.addEventListener("change", e => {
    filterState.date = e.target.value;
    applyFilters();
  });
}

// تشغيل مبدئي
applyFilters();

const firstKey = Object.keys(TEMPLATES)[0];
if (firstKey) selectTemplate(firstKey);
</script>
