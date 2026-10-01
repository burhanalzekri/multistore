(function(){'use strict';
window.MultiStoreUI=window.MultiStoreUI||{};
window.MultiStoreUI.notify=function(message,type){type=type||'info';if(typeof window.showToast==='function'){window.showToast(String(message||''),type);return;}var n=document.createElement('div');n.textContent=String(message||'');n.style.cssText='position:fixed;z-index:999999;top:20px;left:50%;transform:translateX(-50%);padding:14px 18px;border-radius:14px;background:#0f172a;color:#fff;font:700 14px Cairo,sans-serif;box-shadow:0 12px 35px rgba(0,0,0,.2)';document.body.appendChild(n);setTimeout(function(){n.remove()},4500)};
window.MultiStoreUI.confirm=function(message,yes,no){if(window.MultiStoreModal&&typeof window.MultiStoreModal.confirm==='function')return window.MultiStoreModal.confirm(message,yes,no);if(window.showToast)window.showToast(message,'warning');return false};
})();
