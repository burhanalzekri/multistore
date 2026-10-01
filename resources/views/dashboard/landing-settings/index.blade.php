@extends('layouts.app')
@section('title', 'إعدادات صفحة الهبوط')
@section('page-title', '🎨 إعدادات صفحة الهبوط')
@section('page-subtitle', 'تحكم كامل بمحتوى الصفحة الرئيسية')

@section('content')

<style>
  /* ═══════════════════════════════════════════════════════
     🎨 Landing Settings — Unified Navy Design System
     ═══════════════════════════════════════════════════════ */

  /* ─── Tabs Bar ─── */
  .ls-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 24px;
    flex-wrap: wrap;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 8px;
    border-radius: 20px;
    border: 2.5px solid #1e3a8a;
    box-shadow:
      0 15px 30px -12px rgba(30,58,138,.25),
      0 4px 10px -3px rgba(15,23,42,.05),
      inset 0 2px 4px rgba(255,255,255,.95);
  }
  .ls-tab {
    padding: 12px 22px;
    border-radius: 14px;
    border: 2px solid transparent;
    background: transparent;
    font-family: inherit;
    font-weight: 900;
    font-size: 13px;
    color: #64748b;
    cursor: pointer;
    transition: all .35s cubic-bezier(.2,.9,.3,1.1);
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
  }
  .ls-tab:hover {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    color: #1e3a8a;
    border-color: #1e3a8a;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px -6px rgba(30,58,138,.25);
  }
  .ls-tab.active {
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 50%, #ea580c 100%);
    color: #fff;
    border-color: #ea580c;
    box-shadow:
      0 12px 28px -8px rgba(249,115,22,.6),
      0 4px 10px -2px rgba(249,115,22,.35),
      inset 0 2px 4px rgba(255,255,255,.35);
    transform: translateY(-2px);
  }
  .ls-tab.active::before {
    content: "";
    position: absolute;
    top: 0; left: 20%; right: 20%;
    height: 2.5px;
    background: linear-gradient(90deg, transparent, #fff, transparent);
    border-radius: 2.5px;
    opacity: .7;
  }

  /* ─── Panels ─── */
  .ls-panel { display: none; }
  .ls-panel.active { display: block; animation: lsFadeIn .35s ease; }
  @keyframes lsFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ─── Cards ─── */
  .ls-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 2.5px solid #1e3a8a;
    border-radius: 24px;
    padding: 28px 24px;
    margin-bottom: 18px;
    position: relative;
    overflow: hidden;
    box-shadow:
      0 25px 50px -20px rgba(30,58,138,.22),
      0 10px 25px -10px rgba(15,23,42,.08),
      0 2px 6px -1px rgba(15,23,42,.04),
      inset 0 3px 6px rgba(255,255,255,.95),
      inset 0 -2px 4px rgba(30,58,138,.08);
  }
  .ls-card::before {
    content: "";
    position: absolute;
    top: 0; left: 20%; right: 20%;
    height: 3px;
    background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent);
    border-radius: 3px;
    opacity: .8;
    z-index: 2;
  }
  .ls-card-title {
    font-size: 15px;
    font-weight: 900;
    color: #0f172a;
    margin: 0 0 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 16px;
    border-bottom: 2px dashed rgba(30,58,138,.15);
    position: relative;
  }
  .ls-card-title::after {
    content: "";
    position: absolute;
    bottom: -2px;
    right: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #f97316);
    border-radius: 2px;
  }

  /* ─── Fields ─── */
  .ls-field { margin-bottom: 18px; }
  .ls-label {
    display: block;
    font-size: 12.5px;
    font-weight: 900;
    color: #334155;
    margin-bottom: 8px;
  }
  .ls-input, .ls-textarea, .ls-select {
    width: 100%;
    padding: 13px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    font-family: inherit;
    font-size: 14px;
    background: #fff;
    transition: all .3s;
    color: #0f172a;
  }
  .ls-input:hover, .ls-textarea:hover { border-color: #cbd5e1; }
  .ls-input:focus, .ls-textarea:focus, .ls-select:focus {
    outline: none;
    border-color: #1e3a8a;
    box-shadow:
      0 0 0 4px rgba(30,58,138,.1),
      0 8px 18px -8px rgba(30,58,138,.2);
  }
  .ls-textarea { min-height: 90px; resize: vertical; line-height: 1.7; }

  /* ─── Toggle Rows ─── */
  .ls-toggle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    margin-bottom: 10px;
    transition: all .3s cubic-bezier(.2,.9,.3,1.1);
    position: relative;
    overflow: hidden;
  }
  .ls-toggle-row::before {
    content: "";
    position: absolute;
    right: 0; top: 20%; bottom: 20%;
    width: 3px;
    background: linear-gradient(180deg, #3b82f6, #1e40af);
    border-radius: 3px;
    opacity: 0;
    transition: opacity .3s;
  }
  .ls-toggle-row:hover {
    border-color: #1e3a8a;
    transform: translateX(-3px);
    box-shadow:
      0 15px 30px -12px rgba(30,58,138,.25),
      0 4px 10px -3px rgba(30,58,138,.15);
  }
  .ls-toggle-row:hover::before { opacity: 1; }
  .ls-toggle-info { flex: 1; min-width: 0; }
  .ls-toggle-name {
    font-size: 14.5px;
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .ls-toggle-desc { font-size: 12px; color: #64748b; font-weight: 700; }

  /* ─── Toggle Switch ─── */
  .ls-switch {
    position: relative;
    width: 54px;
    height: 30px;
    flex-shrink: 0;
    cursor: pointer;
  }
  .ls-switch input { opacity: 0; width: 0; height: 0; }
  .ls-slider {
    position: absolute;
    inset: 0;
    background: #cbd5e1;
    border-radius: 30px;
    transition: all .35s cubic-bezier(.2,.9,.3,1.1);
    box-shadow: inset 0 2px 4px rgba(0,0,0,.1);
  }
  .ls-slider::before {
    content: "";
    position: absolute;
    height: 22px;
    width: 22px;
    left: 4px;
    top: 4px;
    background: #fff;
    border-radius: 50%;
    transition: all .35s cubic-bezier(.2,.9,.3,1.1);
    box-shadow: 0 2px 6px rgba(0,0,0,.25);
  }
  .ls-switch input:checked + .ls-slider {
    background: linear-gradient(135deg, #fbbf24, #f97316, #ea580c);
    box-shadow:
      inset 0 2px 4px rgba(0,0,0,.1),
      0 6px 16px -6px rgba(249,115,22,.5);
  }
  .ls-switch input:checked + .ls-slider::before {
    transform: translateX(24px);
  }

  /* ─── Mode Tabs ─── */
  .ls-mode-tabs {
    display: flex;
    gap: 10px;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    padding: 6px;
    border-radius: 16px;
    border: 2px solid #cbd5e1;
  }
  .ls-mode-btn {
    flex: 1;
    padding: 14px 18px;
    border-radius: 12px;
    border: 2px solid transparent;
    background: transparent;
    font-family: inherit;
    font-weight: 900;
    font-size: 13.5px;
    color: #64748b;
    cursor: pointer;
    transition: all .35s cubic-bezier(.2,.9,.3,1.1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }
  .ls-mode-btn:hover {
    color: #1e3a8a;
    background: rgba(255,255,255,.5);
  }
  .ls-mode-btn.active {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    color: #ea580c;
    border-color: #ea580c;
    box-shadow:
      0 8px 20px -6px rgba(249,115,22,.4),
      inset 0 2px 4px rgba(255,255,255,.95);
  }
  .ls-mode-btn.mode-real.active {
    color: #16a34a;
    border-color: #16a34a;
    box-shadow:
      0 8px 20px -6px rgba(22,163,74,.4),
      inset 0 2px 4px rgba(255,255,255,.95);
  }

  /* ─── Actions Bar ─── */
  .ls-actions {
    position: sticky;
    bottom: 16px;
    background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);
    border: 2.5px solid #1e3a8a;
    border-radius: 20px;
    padding: 18px 24px;
    display: flex;
    gap: 14px;
    align-items: center;
    flex-wrap: wrap;
    box-shadow:
      0 25px 50px -15px rgba(30,58,138,.35),
      0 10px 25px -8px rgba(15,23,42,.15),
      inset 0 3px 6px rgba(255,255,255,.95);
    z-index: 10;
    overflow: hidden;
  }
  .ls-actions::before {
    content: "";
    position: absolute;
    top: 0; left: 15%; right: 15%;
    height: 3px;
    background: linear-gradient(90deg, transparent, #fbbf24, #f97316, #fbbf24, transparent);
    border-radius: 3px;
    animation: lsActionsGlow 3s ease-in-out infinite;
  }
  @keyframes lsActionsGlow {
    0%, 100% { opacity: .7; transform: scaleX(.9); }
    50%      { opacity: 1; transform: scaleX(1); }
  }
  .ls-actions-info {
    flex: 1;
    min-width: 200px;
    font-size: 12.5px;
    color: #64748b;
    font-weight: 700;
  }
  .ls-actions-info strong { color: #ea580c; font-weight: 900; }

  /* ─── Buttons ─── */
  .ls-btn {
    padding: 13px 26px;
    border-radius: 14px;
    border: 0;
    font-family: inherit;
    font-weight: 900;
    font-size: 14px;
    cursor: pointer;
    transition: all .35s cubic-bezier(.2,.9,.3,1.1);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
  }
  .ls-btn-primary {
    background: linear-gradient(135deg, #fbbf24 0%, #f97316 50%, #ea580c 100%);
    color: #fff;
    box-shadow:
      0 15px 30px -10px rgba(249,115,22,.5),
      0 6px 15px -3px rgba(249,115,22,.3),
      inset 0 2px 4px rgba(255,255,255,.35);
  }
  .ls-btn-primary::before {
    content: "";
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.4), transparent);
    transition: left .8s cubic-bezier(.2,.9,.3,1.1);
  }
  .ls-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow:
      0 22px 45px -12px rgba(249,115,22,.7),
      0 8px 20px -5px rgba(249,115,22,.4),
      inset 0 3px 5px rgba(255,255,255,.5);
  }
  .ls-btn-primary:hover::before { left: 100%; }

  .ls-btn-ghost {
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    color: #334155;
    border: 2px solid #cbd5e1;
  }
  .ls-btn-ghost:hover {
    transform: translateY(-3px);
    border-color: #1e3a8a;
    color: #1e3a8a;
    box-shadow: 0 12px 25px -8px rgba(30,58,138,.3);
  }


  /* ═══ Repeater Compact (للمقارنة) ═══ */
  .rp-compact-list {
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    background: #fff;
  }
  .rp-compact-item {
    display: grid;
    grid-template-columns: 2fr 90px 90px 90px 40px;
    gap: 8px;
    align-items: center;
    padding: 10px 12px;
    border-bottom: 1px solid #f1f5f9;
    transition: background .2s;
    background: #fff;
  }
  .rp-compact-item:last-child { border-bottom: 0; }
  .rp-compact-item:hover { background: #fafbfc; }
  .rp-compact-item input,
  .rp-compact-item select {
    width: 100%;
    padding: 8px 10px;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    font-family: inherit;
    font-size: 12.5px;
    background: #fff;
    color: #0f172a;
    transition: all .2s;
  }
  .rp-compact-item input:focus,
  .rp-compact-item select:focus {
    outline: none;
    border-color: #1e3a8a;
    box-shadow: 0 0 0 3px rgba(30,58,138,.1);
  }
  .rp-compact-item input.rp-compact-feature {
    font-weight: 800;
    font-size: 13px;
  }
  .rp-compact-item select {
    text-align: center;
    cursor: pointer;
    font-weight: 800;
  }
  .rp-compact-del {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    border: 0;
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #dc2626;
    font-size: 14px;
    font-weight: 900;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all .25s;
    font-family: inherit;
  }
  .rp-compact-del:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #fff;
    transform: scale(1.1);
  }
  .rp-compact-header {
    display: grid;
    grid-template-columns: 2fr 90px 90px 90px 40px;
    gap: 8px;
    padding: 10px 12px;
    background: linear-gradient(180deg, #f8fafc, #f1f5f9);
    border-bottom: 2px solid #e2e8f0;
    font-size: 11px;
    font-weight: 900;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .5px;
    text-align: center;
  }
  .rp-compact-header > span:first-child { text-align: right; }

  @media (max-width: 640px) {
    .rp-compact-item, .rp-compact-header {
      grid-template-columns: 1fr;
      gap: 6px;
      padding: 12px;
    }
    .rp-compact-header { display: none; }
    .rp-compact-item {
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      margin-bottom: 8px;
    }
    .rp-compact-item:last-child { margin-bottom: 0; }
  }


  /* ═══ Upload Button ═══ */
  .rp-upload-row {
    display: flex;
    gap: 6px;
    align-items: center;
  }
  .rp-upload-row input {
    flex: 1;
    min-width: 0;
  }
  .rp-upload-btn {
    padding: 8px 12px;
    border-radius: 9px;
    border: 2px solid #1e3a8a;
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    color: #1e3a8a;
    font-family: inherit;
    font-size: 12px;
    font-weight: 900;
    cursor: pointer;
    transition: all .25s;
    white-space: nowrap;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .rp-upload-btn:hover {
    background: linear-gradient(135deg, #1e3a8a, #1e40af);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 14px -4px rgba(30,58,138,.4);
  }
  .rp-upload-btn:disabled {
    opacity: .6;
    cursor: wait;
  }
  .rp-upload-preview {
    display: none;
    margin-top: 6px;
    max-width: 100%;
    height: 60px;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
    object-fit: cover;
  }
  .rp-upload-preview.visible { display: block; }

  .upload-warning {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    padding: 10px 14px;
    border-radius: 11px;
    font-size: 11.5px;
    font-weight: 800;
    margin-top: 8px;
    border: 1.5px solid #fbbf24;
    display: flex;
    align-items: center;
    gap: 6px;
    line-height: 1.5;
  }

  /* ─── Repeater Items ─── */
  .rp-item {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 2px solid #cbd5e1;
    border-radius: 18px;
    padding: 18px;
    margin-bottom: 12px;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 14px;
    align-items: start;
    transition: all .35s cubic-bezier(.2,.9,.3,1.1);
    position: relative;
    overflow: hidden;
  }
  .rp-item::before {
    content: "";
    position: absolute;
    top: 0; left: 25%; right: 25%;
    height: 2.5px;
    background: linear-gradient(90deg, transparent, #3b82f6, #1e40af, #3b82f6, transparent);
    border-radius: 2.5px;
    opacity: .6;
    transition: opacity .35s;
  }
  .rp-item:hover {
    border-color: #1e3a8a;
    box-shadow:
      0 18px 35px -12px rgba(30,58,138,.25),
      0 6px 15px -4px rgba(30,58,138,.15);
    transform: translateY(-3px);
  }
  .rp-item:hover::before { opacity: 1; }

  .rp-item-body { display: grid; gap: 10px; }
  .rp-row {
    display: grid;
    grid-template-columns: 100px 1fr;
    gap: 12px;
    align-items: center;
  }
  .rp-label {
    font-size: 12px;
    font-weight: 900;
    color: #475569;
  }
  .rp-input {
    width: 100%;
    padding: 10px 14px;
    border: 2px solid #e2e8f0;
    border-radius: 11px;
    font-family: inherit;
    font-size: 13.5px;
    background: #fff;
    transition: all .3s;
    color: #0f172a;
  }
  .rp-input:focus {
    outline: none;
    border-color: #1e3a8a;
    box-shadow: 0 0 0 4px rgba(30,58,138,.1);
  }
  .rp-color-row { display: flex; gap: 8px; align-items: center; }
  .rp-emoji-input {
    width: 66px;
    text-align: center;
    font-size: 22px;
    padding: 8px;
    border: 2px solid #e2e8f0;
    border-radius: 11px;
    font-family: inherit;
    background: #fff;
  }
  .rp-emoji-input:focus { outline: none; border-color: #1e3a8a; }
  .rp-color {
    width: 46px;
    height: 42px;
    border-radius: 11px;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    padding: 3px;
    background: #fff;
  }

  /* ─── Repeater Actions ─── */
  .rp-actions { display: flex; flex-direction: column; gap: 8px; }
  .rp-btn-del, .rp-btn-move {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    border: 0;
    font-size: 15px;
    font-weight: 900;
    cursor: pointer;
    transition: all .3s cubic-bezier(.2,.9,.3,1.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: inherit;
  }
  .rp-btn-del {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #dc2626;
    box-shadow: 0 4px 10px -3px rgba(220,38,38,.3);
  }
  .rp-btn-del:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: #fff;
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 8px 20px -5px rgba(220,38,38,.5);
  }
  .rp-btn-move {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    color: #475569;
  }
  .rp-btn-move:hover {
    background: linear-gradient(135deg, #1e3a8a, #1e40af);
    color: #fff;
    transform: scale(1.1);
    box-shadow: 0 8px 20px -5px rgba(30,58,138,.5);
  }

  /* ─── Empty State ─── */
  .rp-empty {
    padding: 40px 20px;
    text-align: center;
    color: #94a3b8;
    border: 2.5px dashed #cbd5e1;
    border-radius: 16px;
    font-weight: 800;
    font-size: 13px;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
  }

  /* ─── Add Button ─── */
  .rp-add {
    margin-top: 14px;
    padding: 14px 26px;
    border-radius: 14px;
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #166534;
    border: 2px solid #86efac;
    font-family: inherit;
    font-weight: 900;
    font-size: 13.5px;
    cursor: pointer;
    transition: all .35s cubic-bezier(.2,.9,.3,1.1);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow:
      0 8px 18px -6px rgba(22,163,74,.3),
      inset 0 2px 4px rgba(255,255,255,.8);
    position: relative;
    overflow: hidden;
  }
  .rp-add::before {
    content: "";
    position: absolute;
    top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.5), transparent);
    transition: left .7s;
  }
  .rp-add:hover {
    transform: translateY(-3px);
    box-shadow:
      0 15px 30px -8px rgba(22,163,74,.5),
      0 6px 15px -3px rgba(22,163,74,.3),
      inset 0 2px 4px rgba(255,255,255,.9);
  }
  .rp-add:hover::before { left: 100%; }

  /* ─── Help Text ─── */
  .ls-help {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 8px;
    font-weight: 700;
    line-height: 1.6;
  }
  .ls-help strong { color: #ea580c; }

  /* ─── Success Alert ─── */
  .ls-alert-success {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    color: #166534;
    padding: 16px 22px;
    border-radius: 16px;
    margin-bottom: 18px;
    font-weight: 900;
    font-size: 13.5px;
    border: 2px solid #86efac;
    box-shadow:
      0 15px 30px -10px rgba(22,163,74,.3),
      inset 0 2px 4px rgba(255,255,255,.8);
    display: flex;
    align-items: center;
    gap: 10px;
    animation: lsAlertIn .4s cubic-bezier(.2,.9,.3,1.1);
    position: relative;
    overflow: hidden;
  }
  .ls-alert-success::before {
    content: "";
    position: absolute;
    top: 0; left: 20%; right: 20%;
    height: 2.5px;
    background: linear-gradient(90deg, transparent, #16a34a, transparent);
    border-radius: 2.5px;
  }
  @keyframes lsAlertIn {
    from { opacity: 0; transform: translateY(-10px) scale(.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
  }

  /* ─── Mobile ─── */
  @media (max-width: 640px) {
    .ls-tabs { padding: 6px; gap: 5px; border-radius: 16px; }
    .ls-tab { padding: 9px 14px; font-size: 11.5px; border-radius: 11px; }
    .ls-card { padding: 20px 16px; border-radius: 20px; }
    .ls-actions { flex-direction: column; align-items: stretch; padding: 14px; }
    .ls-btn { width: 100%; justify-content: center; }
    .rp-row { grid-template-columns: 1fr; gap: 6px; }
    .rp-label { margin-bottom: 0; }
    .rp-item { padding: 14px; grid-template-columns: 1fr; }
    .rp-actions { flex-direction: row; }
    .rp-btn-del, .rp-btn-move { width: 38px; height: 38px; }
  }

  /* ─── Dark Mode ─── */
  html.dark .ls-card {
    background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%);
    border-color: rgba(59,130,246,.5);
  }
  html.dark .ls-card-title { color: #f1f5f9; border-color: rgba(59,130,246,.2); }
  html.dark .ls-input, html.dark .ls-textarea, html.dark .rp-input, html.dark .rp-emoji-input {
    background: #0f172a;
    color: #f1f5f9;
    border-color: rgba(59,130,246,.3);
  }
  html.dark .ls-toggle-row, html.dark .rp-item {
    background: linear-gradient(135deg, #1e293b 0%, #1a2332 100%);
    border-color: rgba(59,130,246,.3);
  }
  html.dark .ls-toggle-name, html.dark .rp-label { color: #e2e8f0; }
  html.dark .ls-label { color: #cbd5e1; }
  html.dark .ls-tabs { background: linear-gradient(135deg, #1e293b, #0f172a); border-color: rgba(59,130,246,.5); }
  html.dark .ls-tab { color: #94a3b8; }
  html.dark .ls-tab:hover { background: rgba(30,41,59,.5); color: #93c5fd; }
</style>

@if(session('success'))
  <div class="ls-alert-success">
    <span style="font-size:20px;">✅</span>
    <span>{{ session('success') }}</span>
  </div>
@endif

<form method="POST" action="{{ route('landing-settings.update') }}" id="lsForm">
  @csrf

  {{-- ═══ Tabs ═══ --}}
  <div class="ls-tabs">
    <button type="button" class="ls-tab active" data-tab="hero">🎬 Hero</button>
    <button type="button" class="ls-tab" data-tab="stats">📊 Live Stats</button>
    <button type="button" class="ls-tab" data-tab="logos">🏪 شعارات المتاجر</button>
    <button type="button" class="ls-tab" data-tab="videos">🎬 الفيديوهات</button>
    <button type="button" class="ls-tab" data-tab="stories">🎓 قصص النجاح</button>
    <button type="button" class="ls-tab" data-tab="marketing">🖼️ عرض المنصة</button>
    <a href="/dashboard/testimonials" class="ls-tab" style="text-decoration:none;" title="إدارة آراء العملاء">💬 آراء العملاء</a>
    <button type="button" class="ls-tab" data-tab="features">✨ الميزات</button>
    <button type="button" class="ls-tab" data-tab="comparison">🆚 المقارنة</button>
    <button type="button" class="ls-tab" data-tab="sections">🎛️ الأقسام</button>
    <button type="button" class="ls-tab" data-tab="cta">🎯 CTA</button>
  </div>

  {{-- ═══ Hero Tab ═══ --}}
  <div class="ls-panel active" data-panel="hero">
    <div class="ls-card">
      <h3 class="ls-card-title">🎬 قسم Hero (الرئيسي)</h3>

      <div class="ls-field">
        <label class="ls-label">🏷️ الشارة العلوية (Badge)</label>
        <input type="text" class="ls-input" name="hero.badge" value="{{ $groups['hero']['badge'] ?? '' }}" placeholder="منصة متاجر إلكترونية يمنية 🇾🇪">
      </div>

      <div class="ls-field">
        <label class="ls-label">📝 العنوان الرئيسي</label>
        <textarea class="ls-textarea" name="hero.title" placeholder="أنشئ متجرك الإلكتروني وابدأ البيع بثقة">{{ $groups['hero']['title'] ?? '' }}</textarea>
      </div>

      <div class="ls-field">
        <label class="ls-label">💬 الوصف (Subtitle)</label>
        <textarea class="ls-textarea" name="hero.subtitle">{{ $groups['hero']['subtitle'] ?? '' }}</textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
        <div class="ls-field">
          <label class="ls-label">🔘 زر أساسي (نص)</label>
          <input type="text" class="ls-input" name="hero.cta_primary" value="{{ $groups['hero']['cta_primary'] ?? '' }}">
        </div>
        <div class="ls-field">
          <label class="ls-label">🔘 زر ثانوي (نص)</label>
          <input type="text" class="ls-input" name="hero.cta_secondary" value="{{ $groups['hero']['cta_secondary'] ?? '' }}">
        </div>
      </div>
    </div>
  </div>

  {{-- ═══ Stats Tab ═══ --}}
  <div class="ls-panel" data-panel="stats">
    <div class="ls-card">
      <h3 class="ls-card-title">📊 وضع عرض الأرقام</h3>

      <div class="ls-mode-tabs">
        <button type="button" class="ls-mode-btn {{ ($groups['stats']['mode'] ?? 'manual') === 'manual' ? 'active' : '' }}" data-mode="manual">
          ✏️ أرقام يدوية
        </button>
        <button type="button" class="ls-mode-btn mode-real {{ ($groups['stats']['mode'] ?? 'manual') === 'real' ? 'active' : '' }}" data-mode="real">
          🔴 أرقام حقيقية من النظام
        </button>
      </div>
      <input type="hidden" name="stats.mode" id="statsModeInput" value="{{ $groups['stats']['mode'] ?? 'manual' }}">

      <div class="ls-help" style="margin-top: 12px;">
        ℹ️ <strong>يدوية</strong>: تتحكم أنت بالأرقام أدناه · <strong>حقيقية</strong>: يقرأ النظام تلقائياً من قاعدة البيانات
      </div>
    </div>

    <div class="ls-card" id="manualStatsCard">
      <h3 class="ls-card-title">✏️ الأرقام اليدوية</h3>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
        <div class="ls-field">
          <label class="ls-label">🏪 عدد المتاجر</label>
          <input type="number" class="ls-input" name="stats.shop_count" value="{{ $groups['stats']['shop_count'] ?? 547 }}">
          <input type="text" class="ls-input" name="stats.shop_label" value="{{ $groups['stats']['shop_label'] ?? 'متجر نشط' }}" style="margin-top: 8px;" placeholder="التسمية">
        </div>

        <div class="ls-field">
          <label class="ls-label">📦 عدد الطلبات</label>
          <input type="number" class="ls-input" name="stats.order_count" value="{{ $groups['stats']['order_count'] ?? 52430 }}">
          <input type="text" class="ls-input" name="stats.order_label" value="{{ $groups['stats']['order_label'] ?? 'طلب مكتمل' }}" style="margin-top: 8px;" placeholder="التسمية">
        </div>

        <div class="ls-field">
          <label class="ls-label">💰 المبيعات (مليون)</label>
          <input type="number" step="0.1" class="ls-input" name="stats.sales_count" value="{{ $groups['stats']['sales_count'] ?? 8 }}">
          <input type="text" class="ls-input" name="stats.sales_label" value="{{ $groups['stats']['sales_label'] ?? 'مليون ريال مبيعات' }}" style="margin-top: 8px;" placeholder="التسمية">
        </div>

        <div class="ls-field">
          <label class="ls-label">👥 المستخدمون اليوم</label>
          <input type="number" class="ls-input" name="stats.user_count" value="{{ $groups['stats']['user_count'] ?? 1287 }}">
          <input type="text" class="ls-input" name="stats.user_label" value="{{ $groups['stats']['user_label'] ?? 'مستخدم اليوم' }}" style="margin-top: 8px;" placeholder="التسمية">
        </div>
      </div>
    </div>
  </div>


  {{-- ═══ Logos Tab ═══ --}}
  <div class="ls-panel" data-panel="logos">
    <div class="ls-card">
      <h3 class="ls-card-title">🏪 شعارات المتاجر (Trust Logos)</h3>

      <div class="ls-field">
        <label class="ls-label">📝 عنوان القسم</label>
        <input type="text" class="ls-input" name="logos.title" value="{{ $groups['logos']['title'] ?? 'يثق بنا أكثر من 500 متجر يمني' }}">
      </div>

      <label class="ls-label" style="margin-top: 16px;">🎨 قائمة الشعارات</label>
      <div id="logosRepeater"></div>
      <input type="hidden" name="logos.items" id="logosItemsInput" value="{{ $groups['logos']['items'] ?? '[]' }}">
      <button type="button" class="rp-add" onclick="logosRepeater.add()">
        ➕ إضافة متجر
      </button>
    </div>
  </div>

  {{-- ═══ Features Tab ═══ --}}
  <div class="ls-panel" data-panel="features">
    <div class="ls-card">
      <h3 class="ls-card-title">✨ ميزات المنصة</h3>

      <label class="ls-label">🎨 قائمة الميزات (6 بطاقات)</label>
      <div id="featuresRepeater"></div>
      <input type="hidden" name="features.items" id="featuresItemsInput" value="{{ $groups['features']['items'] ?? '[]' }}">
      <button type="button" class="rp-add" onclick="featuresRepeater.add()">
        ➕ إضافة ميزة
      </button>
    </div>
  </div>

  {{-- ═══ Comparison Tab ═══ --}}
  <div class="ls-panel" data-panel="comparison">
    <div class="ls-card">
      <h3 class="ls-card-title">🆚 المقارنة مع المنافسين</h3>

      <div class="ls-field">
        <label class="ls-label">📝 عنوان القسم</label>
        <input type="text" class="ls-input" name="comparison.title" value="{{ $groups['comparison']['title'] ?? 'قارن قبل أن تقرر 🧐' }}">
      </div>

      <div class="ls-field">
        <label class="ls-label">💬 الوصف</label>
        <textarea class="ls-textarea" name="comparison.subtitle">{{ $groups['comparison']['subtitle'] ?? '' }}</textarea>
      </div>

      <label class="ls-label" style="margin-top: 16px;">📊 صفوف المقارنة <span style="color:#94a3b8;font-weight:700;font-size:11px;">(اختر من القائمة المنسدلة)</span></label>
      <div id="comparisonRepeater"></div>
      <input type="hidden" name="comparison.rows" id="comparisonItemsInput" value="{{ $groups['comparison']['rows'] ?? '[]' }}">
      <button type="button" class="rp-add" onclick="comparisonRepeater.add()">
        ➕ إضافة صف
      </button>
    </div>
  </div>


  {{-- ═══ Videos Tab ═══ --}}
  <div class="ls-panel" data-panel="videos">
    <div class="ls-card">
      <h3 class="ls-card-title">🎬 شهادات الفيديو</h3>

      <div class="ls-field">
        <label class="ls-label">📝 عنوان القسم</label>
        <input type="text" class="ls-input" name="videos.title" value="{{ $groups['videos']['title'] ?? 'اسمع من تجّارنا الحقيقيين' }}">
      </div>

      <div class="ls-field">
        <label class="ls-label">💬 الوصف</label>
        <textarea class="ls-textarea" name="videos.subtitle">{{ $groups['videos']['subtitle'] ?? '' }}</textarea>
      </div>

      <label class="ls-label" style="margin-top: 16px;">🎥 قائمة الفيديوهات</label>
      <div id="videosRepeater"></div>
      <input type="hidden" name="videos.items" id="videosItemsInput" value="{{ $groups['videos']['items'] ?? '[]' }}">
      <button type="button" class="rp-add" onclick="videosRepeater.add()">
        ➕ إضافة فيديو
      </button>

      <div class="upload-warning">
        ⚠️ <strong>ملاحظة:</strong> الصور المرفوعة تُحفظ في <code>public/images/landing/</code> — على Render Free قد تُفقد عند كل Deploy.
      </div>
    </div>
  </div>


  {{-- ═══ Stories Tab ═══ --}}
  <div class="ls-panel" data-panel="stories">
    <div class="ls-card">
      <h3 class="ls-card-title">🎓 قصص النجاح</h3>

      <div class="ls-field">
        <label class="ls-label">📝 عنوان القسم</label>
        <input type="text" class="ls-input" name="stories.title" value="{{ $groups['stories']['title'] ?? 'عملاؤنا حقّقوا نتائج حقيقية' }}">
      </div>

      <div class="ls-field">
        <label class="ls-label">💬 الوصف</label>
        <textarea class="ls-textarea" name="stories.subtitle">{{ $groups['stories']['subtitle'] ?? '' }}</textarea>
      </div>

      <label class="ls-label" style="margin-top: 16px;">🏆 قائمة قصص النجاح</label>
      <div id="storiesRepeater"></div>
      <input type="hidden" name="stories.items" id="storiesItemsInput" value="{{ $groups['stories']['items'] ?? '[]' }}">
      <button type="button" class="rp-add" onclick="storiesRepeater.add()">
        ➕ إضافة قصة نجاح
      </button>
    </div>
  </div>


  {{-- ═══ Marketing Tab ═══ --}}
  <div class="ls-panel" data-panel="marketing">
    <div class="ls-card">
      <h3 class="ls-card-title">🖼️ قسم "شاهد قوة المنصة"</h3>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
        <div class="ls-field">
          <label class="ls-label">📝 العنوان الرئيسي</label>
          <input type="text" class="ls-input" name="marketing.title" value="{{ $groups['marketing']['title'] ?? 'شاهد قوة المنصة' }}" placeholder="شاهد قوة المنصة">
        </div>
        <div class="ls-field">
          <label class="ls-label">✨ العنوان (مميز)</label>
          <input type="text" class="ls-input" name="marketing.title_hl" value="{{ $groups['marketing']['title_hl'] ?? 'في صور حقيقية' }}" placeholder="في صور حقيقية">
        </div>
      </div>

      <div class="ls-field">
        <label class="ls-label">💬 الوصف</label>
        <textarea class="ls-textarea" name="marketing.subtitle">{{ $groups['marketing']['subtitle'] ?? '' }}</textarea>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
        <div class="ls-field">
          <label class="ls-label">🔘 نص الزر</label>
          <input type="text" class="ls-input" name="marketing.cta_text" value="{{ $groups['marketing']['cta_text'] ?? 'شاهد المتجر التجريبي' }}">
        </div>
        <div class="ls-field">
          <label class="ls-label">🔗 رابط الزر</label>
          <input type="text" class="ls-input" name="marketing.cta_link" value="{{ $groups['marketing']['cta_link'] ?? '/demo-shop' }}">
        </div>
      </div>
    </div>

    <div class="ls-card">
      <h3 class="ls-card-title">🎨 بطاقات العرض (3 بطاقات)</h3>

      <label class="ls-label">🖼️ الصور والتفاصيل</label>
      <div id="marketingRepeater"></div>
      <input type="hidden" name="marketing.items" id="marketingItemsInput" value="{{ $groups['marketing']['items'] ?? '[]' }}">
      <button type="button" class="rp-add" onclick="marketingRepeater.add()">
        ➕ إضافة بطاقة
      </button>

      <div class="upload-warning">
        ⚠️ <strong>ملاحظة:</strong> الصور تُحفظ في <code>public/images/landing/</code> — على Render Free قد تُفقد عند Deploy.
      </div>
    </div>
  </div>

  {{-- ═══ Sections Tab ═══ --}}
  <div class="ls-panel" data-panel="sections">
    <div class="ls-card">
      <h3 class="ls-card-title">🎛️ إظهار / إخفاء أقسام الصفحة</h3>

      @php
        $sectionLabels = [
          'features_visible'   => ['✨', 'الأدوات والميزات', 'قسم الميزات الست'],
          'steps_visible'      => ['🚀', 'الخطوات (كيف يعمل)', 'قسم 3 خطوات'],
          'roi_visible'        => ['💰', 'حاسبة التوفير', 'ROI Calculator'],
          'guarantee_visible'  => ['🛡️', 'الضمان', 'ضمان استرداد 30 يوم'],
          'comparison_visible' => ['🆚', 'مقارنة المنافسين', 'جدول المقارنة'],
          'pricing_visible'    => ['💳', 'الأسعار', 'خطط الأسعار'],
          'reviews_visible'    => ['⭐', 'آراء العملاء', 'شهادات نصية'],
          'stories_visible'    => ['🎓', 'قصص النجاح', 'حالات دراسية'],
          'videos_visible'     => ['🎬', 'شهادات الفيديو', 'فيديوهات العملاء'],
          'faq_visible'        => ['❓', 'الأسئلة الشائعة', 'FAQ'],
          'cta_visible'        => ['🎯', 'الدعوة النهائية', 'CTA + Footer'],
        ];
      @endphp

      @foreach($sectionLabels as $key => $info)
        @php
          $name = "sections.{$key}";
          $val = $groups['sections'][$key] ?? '1';
          $isOn = filter_var($val, FILTER_VALIDATE_BOOLEAN);
        @endphp
        <div class="ls-toggle-row">
          <div class="ls-toggle-info">
            <div class="ls-toggle-name">
              <span>{{ $info[0] }}</span>
              <span>{{ $info[1] }}</span>
            </div>
            <div class="ls-toggle-desc">{{ $info[2] }}</div>
          </div>
          <label class="ls-switch">
            <input type="checkbox" name="{{ $name }}" value="1" {{ $isOn ? 'checked' : '' }}>
            <span class="ls-slider"></span>
          </label>
        </div>
      @endforeach
    </div>
  </div>

  {{-- ═══ CTA Tab ═══ --}}
  <div class="ls-panel" data-panel="cta">
    <div class="ls-card">
      <h3 class="ls-card-title">🎯 القسم النهائي</h3>

      <div class="ls-field">
        <label class="ls-label">📝 العنوان</label>
        <input type="text" class="ls-input" name="cta.title" value="{{ $groups['cta']['title'] ?? 'جاهز لإنشاء متجرك؟' }}">
      </div>

      <div class="ls-field">
        <label class="ls-label">💬 الوصف</label>
        <textarea class="ls-textarea" name="cta.subtitle">{{ $groups['cta']['subtitle'] ?? '' }}</textarea>
      </div>
    </div>
  </div>

  {{-- ═══ Actions ═══ --}}
  <div class="ls-actions">
    <div class="ls-actions-info">
      💾 <strong>الحفظ فوري</strong> — سيظهر التغيير على الصفحة الرئيسية مباشرة
    </div>
    <a href="/" target="_blank" class="ls-btn ls-btn-ghost">👁 معاينة</a>
    <button type="submit" class="ls-btn ls-btn-primary">💾 حفظ التغييرات</button>
  </div>
</form>

<script>
(function(){
  // ═══ Tabs ═══
  document.querySelectorAll('.ls-tab').forEach(function(tab){
    tab.addEventListener('click', function(){
      var target = this.dataset.tab;
      document.querySelectorAll('.ls-tab').forEach(function(t){ t.classList.remove('active'); });
      document.querySelectorAll('.ls-panel').forEach(function(p){ p.classList.remove('active'); });
      this.classList.add('active');
      document.querySelector('.ls-panel[data-panel="' + target + '"]').classList.add('active');
    });
  });

  // ═══ Mode Switch ═══
  var modeInput = document.getElementById('statsModeInput');
  var manualCard = document.getElementById('manualStatsCard');

  document.querySelectorAll('.ls-mode-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
      var mode = this.dataset.mode;
      modeInput.value = mode;
      document.querySelectorAll('.ls-mode-btn').forEach(function(b){ b.classList.remove('active'); });
      this.classList.add('active');
      manualCard.style.opacity = (mode === 'real') ? '0.5' : '1';
      manualCard.style.pointerEvents = (mode === 'real') ? 'none' : 'auto';
    });
  });

  // تطبيق الحالة الأولية
  if (modeInput.value === 'real' && manualCard) {
    manualCard.style.opacity = '0.5';
    manualCard.style.pointerEvents = 'none';
  }

  // ═══ Presets للـHero ═══
  var heroTitle = document.querySelector('textarea[name="hero.title"]');
  if (heroTitle) {
    // يمكن إضافة presets لاحقاً
  }
})();
</script>

<script>
(function(){
  'use strict';

  // ═══ Repeater Class ═══
  function Repeater(config) {
    this.containerId = config.containerId;
    this.inputId = config.inputId;
    this.schema = config.schema;
    this.items = [];
    this.maxItems = config.maxItems || 99;

    this.init();
  }

  Repeater.prototype.init = function() {
    var self = this;
    var inputEl = document.getElementById(this.inputId);

    try {
      this.items = JSON.parse(inputEl.value || '[]');
    } catch (e) {
      this.items = [];
    }

    this.render();
  };

  Repeater.prototype.render = function() {
    var container = document.getElementById(this.containerId);
    var self = this;

    if (this.items.length === 0) {
      container.innerHTML = '<div class="rp-empty">لا توجد عناصر — اضغط "إضافة" للبدء</div>';
      return;
    }

    var html = '';
    this.items.forEach(function(item, idx) {
      html += self.renderItem(item, idx);
    });

    container.innerHTML = html;

    // إرفاق الأحداث
    container.querySelectorAll('[data-action="delete"]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var i = parseInt(this.dataset.idx, 10);
        self.items.splice(i, 1);
        self.save();
        self.render();
      });
    });

    container.querySelectorAll('[data-action="up"]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var i = parseInt(this.dataset.idx, 10);
        if (i > 0) {
          var temp = self.items[i];
          self.items[i] = self.items[i-1];
          self.items[i-1] = temp;
          self.save();
          self.render();
        }
      });
    });

    container.querySelectorAll('[data-action="down"]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var i = parseInt(this.dataset.idx, 10);
        if (i < self.items.length - 1) {
          var temp = self.items[i];
          self.items[i] = self.items[i+1];
          self.items[i+1] = temp;
          self.save();
          self.render();
        }
      });
    });

    container.querySelectorAll('[data-field]').forEach(function(input) {
      input.addEventListener('input', function() {
        var i = parseInt(this.dataset.idx, 10);
        var field = this.dataset.field;
        self.items[i][field] = this.value;
        self.save();
      });
    });

    // رفع الصور
    container.querySelectorAll('[data-upload]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var i = parseInt(this.dataset.idx, 10);
        var field = this.dataset.upload;
        var self = this;

        var input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';

        input.onchange = function() {
          var file = input.files[0];
          if (!file) return;

          btn.disabled = true;
          btn.textContent = '⏳...';

          var formData = new FormData();
          formData.append('image', file);
          formData.append('_token', document.querySelector('input[name="_token"]').value);

          fetch('{{ route("landing-settings.upload") }}', {
            method: 'POST',
            body: formData,
            headers: { 'Accept': 'application/json' },
          })
          .then(function(r) { return r.json(); })
          .then(function(data) {
            btn.disabled = false;
            btn.textContent = '📤 رفع';

            if (data.ok) {
              self.items[i][field] = data.url;
              self.save();
              self.render();
            } else {
              alert('❌ ' + (data.message || 'فشل الرفع'));
            }
          })
          .catch(function(err) {
            btn.disabled = false;
            btn.textContent = '📤 رفع';
            alert('❌ خطأ في الشبكة');
          });
        };

        input.click();
      });
    });

  };

  Repeater.prototype.renderItem = function(item, idx) {
    var self = this;
    var html = '<div class="rp-item">';
    html += '<div class="rp-item-body">';

    this.schema.forEach(function(field) {
      if (field.type === 'image') {
        html += '<div class="rp-row">';
        html += '<div class="rp-label">' + field.label + '</div>';
        html += '<div>';
        html += '<div class="rp-upload-row">';
        html += '<input type="text" class="rp-input" data-field="' + field.key + '" data-idx="' + idx + '" value="' + (item[field.key] || '') + '" placeholder="' + (field.placeholder || '/images/demo/...') + '">';
        html += '<button type="button" class="rp-upload-btn" data-upload="' + field.key + '" data-idx="' + idx + '">📤 رفع</button>';
        html += '</div>';
        if (item[field.key]) {
          html += '<img class="rp-upload-preview visible" src="' + item[field.key] + '" onerror="this.style.display=\'none\'">';
        }
        html += '</div>';
        html += '</div>';
      } else if (field.type === 'color') {
        html += '<div class="rp-row">';
        html += '<div class="rp-label">' + field.label + '</div>';
        html += '<div class="rp-color-row">';
        html += '<input type="text" class="rp-emoji-input" data-field="' + field.key + '" data-idx="' + idx + '" value="' + (item[field.key] || '') + '" placeholder="🍯">';
        html += '<input type="color" class="rp-color" data-field="' + field.colorKey + '" data-idx="' + idx + '" value="' + (item[field.colorKey] || '#fbbf24') + '">';
        html += '</div>';
        html += '</div>';
      } else {
        html += '<div class="rp-row">';
        html += '<div class="rp-label">' + field.label + '</div>';
        html += '<input type="text" class="rp-input" data-field="' + field.key + '" data-idx="' + idx + '" value="' + (item[field.key] || '') + '" placeholder="' + (field.placeholder || '') + '">';
        html += '</div>';
      }
    });

    html += '</div>';
    html += '<div class="rp-actions">';
    html += '<button type="button" class="rp-btn-del" data-action="delete" data-idx="' + idx + '" title="حذف">🗑</button>';
    if (idx > 0) {
      html += '<button type="button" class="rp-btn-move" data-action="up" data-idx="' + idx + '" title="أعلى">▲</button>';
    }
    if (idx < this.items.length - 1) {
      html += '<button type="button" class="rp-btn-move" data-action="down" data-idx="' + idx + '" title="أسفل">▼</button>';
    }
    html += '</div>';
    html += '</div>';

    return html;
  };

  Repeater.prototype.add = function() {
    if (this.items.length >= this.maxItems) {
      alert('وصلت للحد الأقصى (' + this.maxItems + ')');
      return;
    }
    var newItem = {};
    this.schema.forEach(function(field) {
      newItem[field.key] = field.default || '';
      if (field.type === 'color') {
        newItem[field.colorKey] = field.colorDefault || '#fbbf24';
      }
    });
    this.items.push(newItem);
    this.save();
    this.render();
  };

  Repeater.prototype.save = function() {
    document.getElementById(this.inputId).value = JSON.stringify(this.items);
  };

  // ═══ Initialization ═══

  // Logos
  window.logosRepeater = new Repeater({
    containerId: 'logosRepeater',
    inputId: 'logosItemsInput',
    schema: [
      { key: 'emoji', label: '🎨 الشعار', type: 'color', colorKey: 'color', colorDefault: '#fbbf24' },
      { key: 'name',  label: '📝 الاسم',  type: 'text', placeholder: 'متجر العسل' },
    ],
    maxItems: 12,
  });

  // Features
  window.featuresRepeater = new Repeater({
    containerId: 'featuresRepeater',
    inputId: 'featuresItemsInput',
    schema: [
      { key: 'icon',  label: '🎯 الأيقونة', type: 'text', placeholder: 'store', default: 'star' },
      { key: 'title', label: '📝 العنوان',   type: 'text', placeholder: 'إدارة المنتجات' },
      { key: 'desc',  label: '💬 الوصف',     type: 'text', placeholder: 'أضف منتجاتك بصور...' },
    ],
    maxItems: 12,
  });

  // Videos
  window.videosRepeater = new Repeater({
    containerId: 'videosRepeater',
    inputId: 'videosItemsInput',
    schema: [
      { key: 'name',          label: '👤 الاسم',           type: 'text', placeholder: 'أحمد الحميري' },
      { key: 'role',          label: '💼 النشاط',          type: 'text', placeholder: 'صاحب متجر عسل 🍯' },
      { key: 'avatar_letter', label: '🔤 الحرف',           type: 'text', placeholder: 'أ' },
      { key: 'avatar_color',  label: '🎨 لون',             type: 'color', colorKey: 'avatar_color', colorDefault: '#fbbf24' },
      { key: 'poster_url',    label: '🖼️ صورة Poster',     type: 'image', placeholder: '/images/demo/shirt.jpg' },
      { key: 'video_url',     label: '🎬 رابط الفيديو',     type: 'text', placeholder: 'YouTube: https://www.youtube.com/embed/XXXXX' },
      { key: 'duration',      label: '⏱️ المدة',            type: 'text', placeholder: '0:45' },
      { key: 'is_new',        label: '🆕 جديد (1=نعم)',    type: 'text', placeholder: '0 أو 1' },
    ],
    maxItems: 12,
  });

  // Marketing
  window.marketingRepeater = new Repeater({
    containerId: 'marketingRepeater',
    inputId: 'marketingItemsInput',
    schema: [
      { key: 'image',        label: '🖼️ الصورة',         type: 'image', placeholder: '/images/marketing/...' },
      { key: 'badge_text',   label: '🏷️ نص الشارة',      type: 'text', placeholder: '3D EXPERIENCE' },
      { key: 'badge_color',  label: '🎨 لون الشارة',     type: 'text', placeholder: 'orange / blue / green' },
      { key: 'title',        label: '📝 العنوان',        type: 'text', placeholder: 'عرض المنتجات باحترافية' },
      { key: 'desc',         label: '💬 الوصف',          type: 'text', placeholder: 'تجربة بصرية حديثة...' },
    ],
    maxItems: 6,
  });

  // Stories
  window.storiesRepeater = new Repeater({
    containerId: 'storiesRepeater',
    inputId: 'storiesItemsInput',
    schema: [
      { key: 'name',         label: '🏪 اسم المتجر',   type: 'text', placeholder: 'متجر عسل حضرموت' },
      { key: 'type',         label: '📋 النشاط',       type: 'text', placeholder: 'متجر منتجات طبيعية' },
      { key: 'emoji',        label: '🎨 الأيقونة',     type: 'text', placeholder: '🍯' },
      { key: 'color',        label: '🎨 اللون',        type: 'color', colorKey: 'color', colorDefault: '#fbbf24' },
      { key: 'quote',        label: '💬 الاقتباس',     type: 'text', placeholder: 'في 3 أشهر فقط...' },
      { key: 'stat1_num',    label: '📊 رقم 1',        type: 'text', placeholder: '+320%' },
      { key: 'stat1_label',  label: '📝 تسمية 1',      type: 'text', placeholder: 'نمو المبيعات' },
      { key: 'stat2_num',    label: '📊 رقم 2',        type: 'text', placeholder: '3 أشهر' },
      { key: 'stat2_label',  label: '📝 تسمية 2',      type: 'text', placeholder: 'مدة النمو' },
      { key: 'tag',          label: '🏷️ شارة',         type: 'text', placeholder: '✅ 98% تقييمات إيجابية' },
    ],
    maxItems: 12,
  });

  // ═══ Comparison — Custom Compact Repeater ═══
  var cmpItems = [];
  var cmpInputEl = document.getElementById('comparisonItemsInput');

  try {
    cmpItems = JSON.parse(cmpInputEl.value || '[]');
  } catch (e) { cmpItems = []; }

  var CMP_OPTIONS = [
    { value: 'yes',     label: '✅ متوفر' },
    { value: 'partial', label: '🟡 جزئي' },
    { value: 'no',      label: '❌ غير متوفر' },
    { value: 'free',    label: '🆓 مجاناً' },
    { value: '',        label: '—' },
  ];

  function renderCmp() {
    var container = document.getElementById('comparisonRepeater');
    var html = '<div class="rp-compact-list">';

    // Header
    html += '<div class="rp-compact-header">';
    html += '<span>الميزة</span>';
    html += '<span>MultiStore</span>';
    html += '<span>Salla</span>';
    html += '<span>Zid</span>';
    html += '<span></span>';
    html += '</div>';

    if (cmpItems.length === 0) {
      html += '<div style="padding: 30px; text-align: center; color: #94a3b8; font-weight: 700;">لا توجد صفوف — اضغط "➕ إضافة صف"</div>';
    }

    // Rows
    cmpItems.forEach(function(item, idx) {
      html += '<div class="rp-compact-item">';
      html += '<input type="text" class="rp-compact-feature" data-idx="' + idx + '" data-field="feature" value="' + (item.feature || '').replace(/"/g, '&quot;') + '" placeholder="الميزة...">';

      ['us', 'salla', 'zid'].forEach(function(key) {
        html += '<select data-idx="' + idx + '" data-field="' + key + '">';
        CMP_OPTIONS.forEach(function(opt) {
          var sel = (item[key] === opt.value) ? ' selected' : '';
          html += '<option value="' + opt.value + '"' + sel + '>' + opt.label + '</option>';
        });
        html += '</select>';
      });

      html += '<button type="button" class="rp-compact-del" data-action="del" data-idx="' + idx + '">🗑</button>';
      html += '</div>';
    });

    html += '</div>';
    container.innerHTML = html;

    // Events
    container.querySelectorAll('input, select').forEach(function(el) {
      el.addEventListener('input', function() {
        var i = parseInt(this.dataset.idx, 10);
        cmpItems[i][this.dataset.field] = this.value;
        saveCmp();
      });
      el.addEventListener('change', function() {
        var i = parseInt(this.dataset.idx, 10);
        cmpItems[i][this.dataset.field] = this.value;
        saveCmp();
      });
    });

    container.querySelectorAll('[data-action="del"]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var i = parseInt(this.dataset.idx, 10);
        if (confirm('حذف هذا الصف؟')) {
          cmpItems.splice(i, 1);
          saveCmp();
          renderCmp();
        }
      });
    });
  }

  function saveCmp() {
    cmpInputEl.value = JSON.stringify(cmpItems);
  }

  window.comparisonRepeater = {
    add: function() {
      if (cmpItems.length >= 15) {
        alert('وصلت للحد الأقصى (15)');
        return;
      }
      cmpItems.push({ feature: '', us: 'yes', salla: 'no', zid: 'no' });
      saveCmp();
      renderCmp();
    }
  };

  renderCmp();

  // ═══ Sections Visibility (checkbox) — إصلاح الحفظ ═══
  document.getElementById('lsForm').addEventListener('submit', function() {
    // للأقسام المخفية: أضف قيمة فارغة
    document.querySelectorAll('.ls-toggle-row input[type="checkbox"]').forEach(function(cb) {
      if (!cb.checked) {
        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = cb.name;
        hidden.value = '0';
        this.appendChild(hidden);
      }
    }, this);
  });

})();
</script>

@endsection
