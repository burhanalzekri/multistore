<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MultiStore | منصة التجارة الإلكترونية المتكاملة</title>
    <meta name="description" content="MultiStore منصة متكاملة لإنشاء وإدارة المتاجر الإلكترونية بسهولة واحترافية.">
    <meta name="theme-color" content="#f59e0b">

    <meta property="og:title" content="MultiStore | منصة التجارة الإلكترونية">
    <meta property="og:description" content="أنشئ متجرك وأدر منتجاتك وطلباتك من لوحة تحكم واحدة.">
    <meta property="og:image" content="/icon-512.png">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        cairo: ['Cairo', 'sans-serif']
                    },
                    colors: {
                        primary: '#F59E0B',
                        secondary: '#F97316',
                        dark: '#111827',
                        soft: '#FFF7ED'
                    },
                    boxShadow: {
                        premium: '0 20px 60px rgba(15, 23, 42, .10)',
                        orange: '0 18px 45px rgba(245, 158, 11, .20)'
                    }
                }
            }
        }
    </script>

    <style>
        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{margin:0;font-family:'Cairo',sans-serif;background:#fff;color:#111827;overflow-x:hidden}
        ::selection{background:#f59e0b;color:#fff}
        .container-main{width:min(1180px,calc(100% - 32px));margin-inline:auto}
        .glass{background:rgba(255,255,255,.82);backdrop-filter:blur(18px);-webkit-backdrop-filter:blur(18px)}
        .header-shadow{box-shadow:0 8px 30px rgba(15,23,42,.06)}
        .hero-bg{background:radial-gradient(circle at 15% 20%,rgba(245,158,11,.20),transparent 28%),radial-gradient(circle at 85% 15%,rgba(249,115,22,.13),transparent 28%),linear-gradient(180deg,#fff 0%,#fffaf4 100%)}
        .grid-pattern{background-image:linear-gradient(rgba(245,158,11,.045) 1px,transparent 1px),linear-gradient(90deg,rgba(245,158,11,.045) 1px,transparent 1px);background-size:32px 32px}
        .hero-glow{position:absolute;width:420px;height:420px;border-radius:50%;background:rgba(245,158,11,.12);filter:blur(80px);pointer-events:none}
        .premium-card{background:#fff;border:1px solid #f3f4f6;border-radius:26px;box-shadow:0 15px 45px rgba(15,23,42,.07);transition:.35s ease}
        .premium-card:hover{transform:translateY(-7px);border-color:rgba(245,158,11,.25);box-shadow:0 25px 65px rgba(15,23,42,.11)}
        .icon-box{width:54px;height:54px;border-radius:17px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#fff7ed,#ffedd5);color:#ea580c}
        .primary-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:14px 24px;border-radius:15px;font-weight:800;color:#fff;background:linear-gradient(135deg,#f59e0b,#f97316);box-shadow:0 14px 32px rgba(245,158,11,.25);transition:.3s ease}
        .primary-btn:hover{transform:translateY(-3px);box-shadow:0 20px 42px rgba(245,158,11,.35)}
        .secondary-btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:14px 24px;border-radius:15px;font-weight:800;color:#374151;background:#fff;border:1px solid #e5e7eb;transition:.3s ease}
        .secondary-btn:hover{border-color:#f59e0b;color:#d97706;transform:translateY(-2px)}
        .badge{display:inline-flex;align-items:center;gap:7px;padding:8px 13px;border-radius:999px;background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;font-size:13px;font-weight:800}
        .phone-frame{position:relative;width:285px;max-width:80vw;margin:auto;padding:10px;border-radius:38px;background:#111827;box-shadow:0 35px 80px rgba(15,23,42,.25);transform:rotate(-2deg)}
        .phone-screen{overflow:hidden;border-radius:30px;background:#f8fafc;min-height:560px}
        .phone-top{height:27px;background:#111827;position:relative}
        .phone-notch{position:absolute;left:50%;top:0;transform:translateX(-50%);width:90px;height:20px;background:#000;border-radius:0 0 16px 16px}
        .dashboard-window{background:#fff;border-radius:25px;overflow:hidden;box-shadow:0 30px 80px rgba(15,23,42,.13);border:1px solid #f1f5f9}
        .window-bar{height:52px;display:flex;align-items:center;gap:7px;padding:0 18px;background:#f8fafc;border-bottom:1px solid #eef2f7}
        .dot{width:10px;height:10px;border-radius:50%;background:#cbd5e1}
        .dashboard-body{display:grid;grid-template-columns:190px 1fr;min-height:420px}
        .dashboard-sidebar{background:#111827;color:#fff;padding:20px 14px}
        .sidebar-item{display:flex;align-items:center;gap:9px;padding:10px 11px;margin-bottom:5px;border-radius:11px;font-size:12px;color:#cbd5e1}
        .sidebar-item.active{color:#fff;background:linear-gradient(135deg,#f59e0b,#f97316)}
        .dashboard-content{padding:22px;background:#f8fafc}
        .stat-card{background:#fff;border:1px solid #eef2f7;border-radius:15px;padding:15px}
        .chart{height:125px;display:flex;align-items:end;gap:8px;padding:15px 8px 0}
        .bar{flex:1;border-radius:7px 7px 2px 2px;background:linear-gradient(to top,#f59e0b,#fdba74);min-height:18px}
        .store-preview{border-radius:25px;padding:16px;background:linear-gradient(135deg,#111827,#1f2937);box-shadow:0 30px 70px rgba(15,23,42,.18)}
        .store-inner{border-radius:18px;overflow:hidden;background:#fff}
        .product-mini{border:1px solid #f1f5f9;border-radius:14px;padding:9px;background:#fff}
        .product-image{height:85px;border-radius:11px;background:linear-gradient(135deg,#fff7ed,#fed7aa)}
        .step-number{width:48px;height:48px;border-radius:15px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f59e0b,#f97316);color:#fff;font-weight:900;box-shadow:0 12px 28px rgba(245,158,11,.22)}
        .pricing-card{position:relative;border-radius:27px;padding:28px;background:#fff;border:1px solid #e5e7eb;box-shadow:0 15px 45px rgba(15,23,42,.06)}
        .pricing-feature{display:flex;align-items:center;gap:9px;font-size:13px;color:#4b5563;margin-bottom:12px}
        .pricing-feature i{color:#16a34a}
        .popular{border:2px solid #f59e0b;box-shadow:0 25px 70px rgba(245,158,11,.15)}
        .popular-label{position:absolute;top:-14px;right:25px;padding:7px 15px;border-radius:999px;color:#fff;font-size:12px;font-weight:800;background:linear-gradient(135deg,#f59e0b,#f97316)}
        .faq-item{border:1px solid #eef2f7;border-radius:18px;background:#fff;overflow:hidden}
        .faq-button{width:100%;display:flex;align-items:center;justify-content:space-between;gap:15px;padding:19px 20px;text-align:right;font-weight:800}
        .faq-answer{display:none;padding:0 20px 20px;color:#6b7280;line-height:1.9;font-size:14px}
        .faq-item.open .faq-answer{display:block}
        .faq-item.open .faq-icon{transform:rotate(180deg)}
        .faq-icon{transition:.3s;flex-shrink:0}
        .reveal{opacity:0;transform:translateY(25px);transition:opacity .7s ease,transform .7s ease}
        .reveal.show{opacity:1;transform:translateY(0)}
        .float{animation:floating 4s ease-in-out infinite}
        @keyframes floating{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
        .pulse-dot{animation:pulseDot 2s infinite}
        @keyframes pulseDot{0%,100%{box-shadow:0 0 0 0 rgba(34,197,94,.3)}50%{box-shadow:0 0 0 8px rgba(34,197,94,0)}}
        .mobile-menu{display:none}
        .mobile-menu.open{display:block}

        /* Marketing Showcase */
        .marketing-showcase{
            position:relative;
            overflow:hidden;
            border-radius:32px;
            background:
                radial-gradient(circle at 10% 20%,rgba(245,158,11,.20),transparent 30%),
                radial-gradient(circle at 90% 80%,rgba(249,115,22,.15),transparent 32%),
                linear-gradient(135deg,#111827,#1f2937);
            box-shadow:0 30px 80px rgba(15,23,42,.16);
        }
        .marketing-image-card{
            position:relative;
            overflow:hidden;
            border-radius:24px;
            background:#fff;
            border:1px solid rgba(255,255,255,.12);
            box-shadow:0 20px 55px rgba(0,0,0,.18);
            transition:.4s ease;
        }
        .marketing-image-card:hover{
            transform:translateY(-8px) scale(1.01);
            box-shadow:0 30px 70px rgba(0,0,0,.25);
        }
        .marketing-image-card img{
            width:100%;
            height:100%;
            object-fit:cover;
            display:block;
            transition:.5s ease;
        }
        .marketing-image-card:hover img{transform:scale(1.045)}
        .marketing-overlay{
            position:absolute;
            inset:auto 0 0;
            padding:24px;
            color:#fff;
            background:linear-gradient(transparent,rgba(0,0,0,.78));
        }
        .marketing-tag{
            display:inline-flex;
            padding:6px 11px;
            border-radius:999px;
            background:rgba(245,158,11,.95);
            color:#fff;
            font-size:11px;
            font-weight:900;
        }

        @media(max-width:900px){
            .dashboard-body{grid-template-columns:1fr}
            .dashboard-sidebar{display:none}
        }
        @media(max-width:768px){
            .container-main{width:min(100% - 24px,600px)}
            .desktop-nav{display:none}
            .hero-title{font-size:2.25rem!important;line-height:1.35!important}
            .phone-frame{width:245px}
            .phone-screen{min-height:490px}
            .dashboard-content{padding:13px}
            .dashboard-window{border-radius:18px}
            .store-preview{padding:10px;border-radius:19px}
            .pricing-card{padding:22px}
            .marketing-showcase{border-radius:24px}
        }
        @media(max-width:480px){
            .hero-title{font-size:1.9rem!important}
            .hero-actions{flex-direction:column}
            .hero-actions a{width:100%}
            .phone-frame{width:225px}
        }
    </style>
</head>

<body>

<header id="mainHeader" class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur-xl border-b border-gray-100 transition-all">
    <div class="container-main">
        <div class="h-[72px] flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-white shadow-lg" style="background:linear-gradient(135deg,#f59e0b,#f97316)">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="font-black text-xl text-gray-900 leading-none">Multi<span class="text-orange-500">Store</span></div>
                    <div class="text-[10px] text-gray-400 mt-1">منصتك التجارية</div>
                </div>
            </a>

            <nav class="desktop-nav flex items-center gap-7 text-sm font-bold text-gray-600">
                <a href="#features" class="hover:text-orange-500 transition">المميزات</a>
                <a href="#how" class="hover:text-orange-500 transition">كيف يعمل؟</a>
                <a href="#showcase" class="hover:text-orange-500 transition">النظام</a>
                <a href="#marketing" class="hover:text-orange-500 transition">المتجر</a>
                <a href="#pricing" class="hover:text-orange-500 transition">الأسعار</a>
                <a href="#faq" class="hover:text-orange-500 transition">الأسئلة الشائعة</a>
            </nav>

            <div class="hidden md:flex items-center gap-3">
                <a href="/login" class="px-4 py-2.5 text-sm font-bold text-gray-700 hover:text-orange-500 transition">تسجيل الدخول</a>
                <a href="/register" class="primary-btn text-sm py-2.5 px-5">
                    ابدأ الآن
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
            </div>

            <button id="menuButton" class="md:hidden w-11 h-11 rounded-xl bg-gray-50 flex items-center justify-center">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
        </div>

        <div id="mobileMenu" class="mobile-menu pb-4">
            <div class="rounded-2xl bg-white border border-gray-100 shadow-lg p-3 space-y-1">
                <a href="#features" class="block px-4 py-3 rounded-xl hover:bg-orange-50 font-bold">المميزات</a>
                <a href="#how" class="block px-4 py-3 rounded-xl hover:bg-orange-50 font-bold">كيف يعمل؟</a>
                <a href="#showcase" class="block px-4 py-3 rounded-xl hover:bg-orange-50 font-bold">النظام</a>
                <a href="#marketing" class="block px-4 py-3 rounded-xl hover:bg-orange-50 font-bold">المتجر</a>
                <a href="#pricing" class="block px-4 py-3 rounded-xl hover:bg-orange-50 font-bold">الأسعار</a>
                <a href="#faq" class="block px-4 py-3 rounded-xl hover:bg-orange-50 font-bold">الأسئلة الشائعة</a>
                <div class="pt-2 border-t flex gap-2">
                    <a href="/login" class="flex-1 text-center py-3 font-bold rounded-xl bg-gray-50">دخول</a>
                    <a href="/register" class="flex-1 text-center py-3 font-bold rounded-xl text-white bg-orange-500">ابدأ الآن</a>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="hero-bg grid-pattern relative overflow-hidden pt-32 pb-20 md:pt-40 md:pb-28">
    <div class="hero-glow top-20 right-[-150px]"></div>
    <div class="hero-glow bottom-[-180px] left-[-150px]"></div>

    <div class="container-main relative">
        <div class="grid lg:grid-cols-2 gap-14 lg:gap-20 items-center">
            <div class="reveal">
                <div class="badge mb-6">
                    <span class="w-2 h-2 rounded-full bg-green-500 pulse-dot"></span>
                    منصة متكاملة لإدارة تجارتك
                </div>

                <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-black leading-tight text-gray-950">
                    أنشئ متجرك
                    <span class="block mt-2 bg-gradient-to-l from-orange-500 to-amber-400 bg-clip-text text-transparent">وابدأ البيع بثقة</span>
                </h1>

                <p class="mt-6 text-gray-600 text-base md:text-lg leading-8 max-w-xl">
                    منصة MultiStore تمنحك الأدوات التي تحتاجها لإدارة المنتجات والطلبات والعملاء والمتاجر من مكان واحد، بواجهة بسيطة وسريعة ومتوافقة مع الجوال.
                </p>

                <div class="hero-actions flex flex-wrap gap-3 mt-8">
                    <a href="/register" class="primary-btn">ابدأ مجانًا <i data-lucide="arrow-left" class="w-5 h-5"></i></a>
                    <a href="#showcase" class="secondary-btn"><i data-lucide="play-circle" class="w-5 h-5"></i> شاهد النظام</a>
                </div>

                <div class="flex flex-wrap gap-5 mt-8 text-sm text-gray-500">
                    <div class="flex items-center gap-2"><i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i> إعداد سريع</div>
                    <div class="flex items-center gap-2"><i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i> متوافق مع الجوال</div>
                    <div class="flex items-center gap-2"><i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i> لوحة تحكم متكاملة</div>
                </div>
            </div>

            <div class="reveal flex justify-center">
                <div class="relative float">
                    <div class="absolute -top-5 -right-7 bg-white rounded-2xl shadow-xl border border-gray-100 px-4 py-3 z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-green-50 text-green-600 flex items-center justify-center"><i data-lucide="trending-up" class="w-4 h-4"></i></div>
                            <div><div class="text-[10px] text-gray-400">المبيعات اليوم</div><div class="font-black text-sm">+24.8%</div></div>
                        </div>
                    </div>

                    <div class="absolute -bottom-4 -left-7 bg-white rounded-2xl shadow-xl border border-gray-100 px-4 py-3 z-10">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center"><i data-lucide="shopping-cart" class="w-4 h-4"></i></div>
                            <div><div class="text-[10px] text-gray-400">طلبات جديدة</div><div class="font-black text-sm">128 طلب</div></div>
                        </div>
                    </div>

                    <div class="phone-frame">
                        <div class="phone-screen">
                            <div class="phone-top"><div class="phone-notch"></div></div>
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-5">
                                    <div><div class="text-[10px] text-gray-400">مرحباً بك</div><div class="font-black text-gray-900">متجرك الإلكتروني</div></div>
                                    <div class="w-9 h-9 rounded-full bg-orange-100 flex items-center justify-center"><i data-lucide="user" class="w-4 h-4 text-orange-600"></i></div>
                                </div>

                                <div class="rounded-2xl p-5 text-white mb-4" style="background:linear-gradient(135deg,#f59e0b,#f97316)">
                                    <div class="text-xs opacity-80">إجمالي المبيعات</div>
                                    <div class="text-3xl font-black mt-2">24,850 ر.س</div>
                                    <div class="text-xs mt-2 opacity-80">↑ 18.4% هذا الشهر</div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-white border rounded-2xl p-3"><div class="text-[10px] text-gray-400">الطلبات</div><div class="font-black text-xl mt-1">128</div></div>
                                    <div class="bg-white border rounded-2xl p-3"><div class="text-[10px] text-gray-400">المنتجات</div><div class="font-black text-xl mt-1">356</div></div>
                                </div>

                                <div class="mt-4 bg-white border rounded-2xl p-3">
                                    <div class="flex justify-between items-center"><span class="font-bold text-xs">آخر الطلبات</span><span class="text-[10px] text-orange-500">عرض الكل</span></div>
                                    <div class="space-y-3 mt-4">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2"><div class="w-8 h-8 rounded-lg bg-orange-50"></div><div><div class="text-[10px] font-bold">طلب #1024</div><div class="text-[9px] text-gray-400">منذ 5 دقائق</div></div></div>
                                            <span class="text-[9px] px-2 py-1 rounded-full bg-green-50 text-green-600">مكتمل</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-2"><div class="w-8 h-8 rounded-lg bg-amber-50"></div><div><div class="text-[10px] font-bold">طلب #1023</div><div class="text-[9px] text-gray-400">منذ 12 دقيقة</div></div></div>
                                            <span class="text-[9px] px-2 py-1 rounded-full bg-yellow-50 text-yellow-600">جديد</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Marketing Images Showcase -->
<section id="marketing" class="py-16 md:py-24 bg-gray-50">
    <div class="container-main">
        <div class="text-center max-w-3xl mx-auto reveal">
            <span class="badge">تجربة المتجر الحقيقية</span>
            <h2 class="text-3xl md:text-4xl font-black mt-5 text-gray-950">
                كل ما تحتاجه لعرض منتجاتك وتسويق متجرك
            </h2>
            <p class="text-gray-500 mt-4 leading-8">
                واجهة تسويقية تجمع بين جمال العرض، تجربة التسوق، وإدارة المتجر في تجربة واحدة.
            </p>
        </div>

        <div class="marketing-showcase p-4 md:p-7 mt-12 reveal">
            <div class="grid lg:grid-cols-3 gap-5">

                <a href="#showcase" class="marketing-image-card min-h-[280px] lg:min-h-[420px]">
                    <img
                        src="{{ asset('images/marketing/store-3d.png') }}"
                        alt="عرض ثلاثي الأبعاد للمتجر"
                        loading="lazy"
                        onerror="this.style.display='none'">
                    <div class="marketing-overlay">
                        <span class="marketing-tag">3D EXPERIENCE</span>
                        <h3 class="font-black text-xl mt-3">عرض المنتجات بطريقة جذابة</h3>
                        <p class="text-xs text-white/75 mt-2 leading-6">تجربة بصرية حديثة تساعد على إبراز المنتجات.</p>
                    </div>
                </a>

                <a href="#showcase" class="marketing-image-card min-h-[280px] lg:min-h-[420px]">
                    <img
                        src="{{ asset('images/marketing/dashboard.png') }}"
                        alt="لوحة تحكم MultiStore"
                        loading="lazy"
                        onerror="this.style.display='none'">
                    <div class="marketing-overlay">
                        <span class="marketing-tag">SMART DASHBOARD</span>
                        <h3 class="font-black text-xl mt-3">إدارة متجرك من لوحة واحدة</h3>
                        <p class="text-xs text-white/75 mt-2 leading-6">الطلبات والمنتجات والعملاء والإحصائيات في مكان واحد.</p>
                    </div>
                </a>

                <a href="#showcase" class="marketing-image-card min-h-[280px] lg:min-h-[420px]">
                    <img
                        src="{{ asset('images/marketing/store-marketing.png') }}"
                        alt="واجهة تسويقية للمتجر"
                        loading="lazy"
                        onerror="this.style.display='none'">
                    <div class="marketing-overlay">
                        <span class="marketing-tag">ONLINE STORE</span>
                        <h3 class="font-black text-xl mt-3">متجر جاهز للعرض والبيع</h3>
                        <p class="text-xs text-white/75 mt-2 leading-6">تصميم متجاوب وسريع مناسب للجوال والحاسوب.</p>
                    </div>
                </a>

            </div>

            <div class="text-center mt-7">
                <a href="/register" class="primary-btn">
                    ابدأ إنشاء متجرك
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<section id="features" class="py-20 md:py-28 bg-white">
    <div class="container-main">
        <div class="text-center max-w-2xl mx-auto reveal">
            <span class="badge">كل ما تحتاجه في مكان واحد</span>
            <h2 class="text-3xl md:text-4xl font-black text-gray-950 mt-5">أدوات تساعدك على إدارة تجارتك</h2>
            <p class="text-gray-500 mt-4 leading-8">صممنا MultiStore لتكون عملية، واضحة وسريعة سواء كنت تدير متجرًا واحدًا أو عدة متاجر.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-12">
            @php
                $features = [
                    ['icon'=>'store','title'=>'متاجر متعددة','text'=>'أنشئ وأدر أكثر من متجر من منصة واحدة.'],
                    ['icon'=>'package','title'=>'إدارة المنتجات','text'=>'أضف منتجاتك وصنفها وتابع المخزون بسهولة.'],
                    ['icon'=>'shopping-bag','title'=>'إدارة الطلبات','text'=>'تابع الطلبات وحالاتها من لوحة واضحة.'],
                    ['icon'=>'users','title'=>'إدارة العملاء','text'=>'احتفظ ببيانات العملاء وسجل الطلبات.'],
                    ['icon'=>'bar-chart-3','title'=>'تقارير وتحليلات','text'=>'تابع أداء متجرك ومبيعاتك من مكان واحد.'],
                    ['icon'=>'shield-check','title'=>'أمان وصلاحيات','text'=>'تحكم في المستخدمين والصلاحيات والإدارة.'],
                ];
            @endphp

            @foreach($features as $feature)
                <div class="premium-card p-6 reveal">
                    <div class="icon-box mb-5"><i data-lucide="{{ $feature['icon'] }}" class="w-6 h-6"></i></div>
                    <h3 class="font-black text-lg text-gray-900">{{ $feature['title'] }}</h3>
                    <p class="text-gray-500 text-sm leading-7 mt-3">{{ $feature['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="how" class="py-20 md:py-28 bg-gray-50">
    <div class="container-main">
        <div class="grid lg:grid-cols-2 gap-14 items-center">
            <div class="reveal">
                <span class="badge">ابدأ خلال دقائق</span>
                <h2 class="text-3xl md:text-4xl font-black mt-5 text-gray-950">من الفكرة إلى المتجر <span class="text-orange-500">بخطوات بسيطة</span></h2>
                <p class="text-gray-500 mt-5 leading-8">لا تحتاج إلى خبرة تقنية كبيرة. كل ما تحتاجه هو إنشاء حساب وإضافة بيانات متجرك ثم البدء بإدارة المنتجات والطلبات.</p>

                <div class="space-y-6 mt-9">
                    <div class="flex gap-4"><div class="step-number shrink-0">1</div><div><h3 class="font-black text-lg">أنشئ حسابك</h3><p class="text-sm text-gray-500 mt-1 leading-7">سجل بياناتك وابدأ إعداد متجرك.</p></div></div>
                    <div class="flex gap-4"><div class="step-number shrink-0">2</div><div><h3 class="font-black text-lg">أضف المنتجات</h3><p class="text-sm text-gray-500 mt-1 leading-7">أضف الصور والأسعار والمخزون والتصنيفات.</p></div></div>
                    <div class="flex gap-4"><div class="step-number shrink-0">3</div><div><h3 class="font-black text-lg">ابدأ البيع</h3><p class="text-sm text-gray-500 mt-1 leading-7">شارك متجرك وابدأ استقبال الطلبات.</p></div></div>
                </div>
            </div>

            <div class="reveal">
                <div class="store-preview">
                    <div class="store-inner">
                        <div class="h-14 border-b flex items-center justify-between px-5">
                            <div class="font-black text-lg">متجري <span class="text-orange-500">.</span></div>
                            <div class="flex items-center gap-3 text-gray-400"><i data-lucide="search" class="w-4 h-4"></i><i data-lucide="shopping-cart" class="w-4 h-4"></i></div>
                        </div>
                        <div class="p-5">
                            <div class="rounded-2xl p-6 text-white" style="background:linear-gradient(135deg,#f59e0b,#f97316)">
                                <div class="text-xs opacity-80">عروض اليوم</div>
                                <div class="text-2xl font-black mt-2">اكتشف منتجاتك المفضلة</div>
                                <button class="mt-4 bg-white text-orange-600 px-4 py-2 rounded-xl text-xs font-black">تسوق الآن</button>
                            </div>

                            <div class="flex justify-between items-center mt-6"><h3 class="font-black">الأكثر مبيعًا</h3><span class="text-xs text-orange-500 font-bold">عرض الكل</span></div>

                            <div class="grid grid-cols-3 gap-3 mt-4">
                                @for($i=1;$i<=3;$i++)
                                    <div class="product-mini">
                                        <div class="product-image"></div>
                                        <div class="font-bold text-[10px] mt-2">منتج مميز</div>
                                        <div class="text-orange-600 font-black text-xs mt-1">120 ر.س</div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="showcase" class="py-20 md:py-28 bg-white">
    <div class="container-main">
        <div class="text-center max-w-2xl mx-auto reveal">
            <span class="badge">لوحة تحكم احترافية</span>
            <h2 class="text-3xl md:text-4xl font-black mt-5">كل أرقام متجرك أمامك</h2>
            <p class="text-gray-500 mt-4 leading-8">لوحة تحكم مصممة لتعرض لك أهم المعلومات بدون تعقيد أو ازدحام.</p>
        </div>

        <div class="dashboard-window mt-12 reveal">
            <div class="window-bar"><span class="dot"></span><span class="dot"></span><span class="dot"></span><div class="flex-1 mx-5 bg-white border rounded-lg h-7 flex items-center px-3"><span class="text-[9px] text-gray-400">dashboard.multistore.local</span></div></div>

            <div class="dashboard-body">
                <aside class="dashboard-sidebar">
                    <div class="flex items-center gap-2 mb-7 px-2">
                        <div class="w-8 h-8 rounded-xl bg-orange-500 flex items-center justify-center"><i data-lucide="shopping-bag" class="w-4 h-4"></i></div>
                        <span class="font-black text-sm">MultiStore</span>
                    </div>
                    <div class="sidebar-item active"><i data-lucide="layout-dashboard" class="w-4 h-4"></i> الرئيسية</div>
                    <div class="sidebar-item"><i data-lucide="store" class="w-4 h-4"></i> المتاجر</div>
                    <div class="sidebar-item"><i data-lucide="package" class="w-4 h-4"></i> المنتجات</div>
                    <div class="sidebar-item"><i data-lucide="shopping-cart" class="w-4 h-4"></i> الطلبات</div>
                    <div class="sidebar-item"><i data-lucide="users" class="w-4 h-4"></i> العملاء</div>
                    <div class="sidebar-item"><i data-lucide="settings" class="w-4 h-4"></i> الإعدادات</div>
                </aside>

                <main class="dashboard-content">
                    <div class="flex items-center justify-between mb-5">
                        <div><div class="text-lg font-black">نظرة عامة</div><div class="text-[10px] text-gray-400 mt-1">آخر تحديث قبل دقيقة</div></div>
                        <div class="bg-white border rounded-xl px-3 py-2 text-[10px] font-bold">هذا الشهر</div>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="stat-card"><div class="text-[10px] text-gray-400">إجمالي المبيعات</div><div class="font-black text-lg mt-2">48,250</div><div class="text-[9px] text-green-600 mt-1">↑ 18.4%</div></div>
                        <div class="stat-card"><div class="text-[10px] text-gray-400">الطلبات</div><div class="font-black text-lg mt-2">428</div><div class="text-[9px] text-green-600 mt-1">↑ 12.1%</div></div>
                        <div class="stat-card"><div class="text-[10px] text-gray-400">العملاء</div><div class="font-black text-lg mt-2">1,284</div><div class="text-[9px] text-green-600 mt-1">↑ 8.7%</div></div>
                        <div class="stat-card"><div class="text-[10px] text-gray-400">المنتجات</div><div class="font-black text-lg mt-2">356</div><div class="text-[9px] text-orange-600 mt-1">12 منخفضة المخزون</div></div>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-4 mt-4">
                        <div class="lg:col-span-2 bg-white rounded-2xl border p-4">
                            <div class="flex justify-between items-center"><div class="font-black text-xs">المبيعات</div><div class="text-[9px] text-gray-400">آخر 7 أيام</div></div>
                            <div class="chart">
                                <div class="bar" style="height:45%"></div><div class="bar" style="height:62%"></div><div class="bar" style="height:50%"></div><div class="bar" style="height:78%"></div><div class="bar" style="height:64%"></div><div class="bar" style="height:88%"></div><div class="bar" style="height:72%"></div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border p-4">
                            <div class="font-black text-xs mb-4">آخر الطلبات</div>
                            <div class="space-y-3">
                                @for($i=1;$i<=4;$i++)
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg bg-orange-50"></div>
                                            <div><div class="text-[9px] font-bold">#10{{20+$i}}</div><div class="text-[8px] text-gray-400">عميل جديد</div></div>
                                        </div>
                                        <span class="text-[8px] text-green-600 bg-green-50 px-2 py-1 rounded-full">مكتمل</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <div class="text-center mt-7 reveal">
            <a href="/dashboard" class="secondary-btn">معاينة اللوحة مباشرة <i data-lucide="external-link" class="w-4 h-4"></i></a>
        </div>
    </div>
</section>

<section class="py-20 md:py-28 bg-gray-950 text-white">
    <div class="container-main">
        <div class="grid lg:grid-cols-2 gap-14 items-center">
            <div class="reveal">
                <span class="inline-flex px-3 py-2 rounded-full bg-orange-500/10 text-orange-400 border border-orange-500/20 text-xs font-bold">لماذا MultiStore؟</span>
                <h2 class="text-3xl md:text-4xl font-black mt-6 leading-tight">نظام واحد لإدارة <span class="text-orange-400">كل تجارتك</span></h2>
                <p class="text-gray-400 leading-8 mt-5">بدل التنقل بين أدوات متعددة، اجمع منتجاتك وطلباتك وعملائك وتقاريرك في مكان واحد بواجهة واضحة.</p>

                <div class="grid sm:grid-cols-2 gap-4 mt-8">
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-5"><i data-lucide="zap" class="w-6 h-6 text-orange-400"></i><h3 class="font-black mt-4">سرعة الاستخدام</h3><p class="text-xs text-gray-400 mt-2 leading-6">واجهات بسيطة للوصول إلى أهم المهام بسرعة.</p></div>
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-5"><i data-lucide="smartphone" class="w-6 h-6 text-orange-400"></i><h3 class="font-black mt-4">Mobile First</h3><p class="text-xs text-gray-400 mt-2 leading-6">تجربة مناسبة للجوال والتابلت والحاسوب.</p></div>
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-5"><i data-lucide="layers-3" class="w-6 h-6 text-orange-400"></i><h3 class="font-black mt-4">متاجر متعددة</h3><p class="text-xs text-gray-400 mt-2 leading-6">إدارة أكثر من متجر من حساب مركزي.</p></div>
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-5"><i data-lucide="shield" class="w-6 h-6 text-orange-400"></i><h3 class="font-black mt-4">صلاحيات واضحة</h3><p class="text-xs text-gray-400 mt-2 leading-6">تنظيم الوصول بحسب أدوار المستخدمين.</p></div>
                </div>
            </div>

            <div class="reveal">
                <div class="rounded-[30px] bg-white/5 border border-white/10 p-5">
                    <div class="rounded-2xl bg-white p-4 text-gray-900">
                        <div class="flex justify-between items-center border-b pb-4"><div class="font-black">ملخص المتجر</div><div class="w-9 h-9 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center"><i data-lucide="store" class="w-4 h-4"></i></div></div>
                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <div class="rounded-2xl bg-orange-50 p-4"><div class="text-xs text-gray-500">المبيعات</div><div class="font-black text-xl mt-2">48,250</div></div>
                            <div class="rounded-2xl bg-green-50 p-4"><div class="text-xs text-gray-500">الطلبات</div><div class="font-black text-xl mt-2">428</div></div>
                            <div class="rounded-2xl bg-blue-50 p-4"><div class="text-xs text-gray-500">العملاء</div><div class="font-black text-xl mt-2">1,284</div></div>
                            <div class="rounded-2xl bg-purple-50 p-4"><div class="text-xs text-gray-500">المنتجات</div><div class="font-black text-xl mt-2">356</div></div>
                        </div>
                        <div class="mt-4 rounded-2xl border p-4">
                            <div class="flex justify-between"><span class="font-black text-sm">أداء المبيعات</span><span class="text-xs text-green-600">+18.4%</span></div>
                            <div class="chart h-[150px]"><div class="bar" style="height:40%"></div><div class="bar" style="height:55%"></div><div class="bar" style="height:48%"></div><div class="bar" style="height:75%"></div><div class="bar" style="height:62%"></div><div class="bar" style="height:92%"></div><div class="bar" style="height:80%"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="pricing" class="py-20 md:py-28 bg-gray-50">
    <div class="container-main">
        <div class="text-center max-w-2xl mx-auto reveal">
            <span class="badge">خطط مرنة</span>
            <h2 class="text-3xl md:text-4xl font-black mt-5">اختر الخطة المناسبة لك</h2>
            <p class="text-gray-500 mt-4">ابدأ بما يناسبك ويمكنك التوسع لاحقًا.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-5 mt-12">
            <div class="pricing-card reveal">
                <div class="text-sm font-bold text-gray-500">مجاني</div>
                <div class="text-4xl font-black mt-4">0 <span class="text-sm text-gray-400">/ شهر</span></div>
                <p class="text-sm text-gray-500 leading-7 mt-4">مناسب لتجربة المنصة والبدء بمشروع صغير.</p>
                <a href="/register" class="secondary-btn w-full mt-7">ابدأ مجانًا</a>
                <div class="border-t mt-7 pt-6">
                    <div class="pricing-feature"><i data-lucide="check" class="w-4 h-4"></i> متجر واحد</div>
                    <div class="pricing-feature"><i data-lucide="check" class="w-4 h-4"></i> إدارة المنتجات</div>
                    <div class="pricing-feature"><i data-lucide="check" class="w-4 h-4"></i> إدارة الطلبات</div>
                </div>
            </div>

            <div class="pricing-card popular reveal">
                <div class="popular-label">الأكثر استخدامًا</div>
                <div class="text-sm font-bold text-orange-600">أساسي</div>
                <div class="text-4xl font-black mt-4">— <span class="text-sm text-gray-400">/ شهر</span></div>
                <p class="text-sm text-gray-500 leading-7 mt-4">للشركات والمتاجر التي تحتاج أدوات أكثر.</p>
                <a href="/register" class="primary-btn w-full mt-7">ابدأ الآن</a>
                <div class="border-t mt-7 pt-6">
                    <div class="pricing-feature"><i data-lucide="check" class="w-4 h-4"></i> متاجر متعددة</div>
                    <div class="pricing-feature"><i data-lucide="check" class="w-4 h-4"></i> تقارير وتحليلات</div>
                    <div class="pricing-feature"><i data-lucide="check" class="w-4 h-4"></i> إدارة العملاء</div>
                    <div class="pricing-feature"><i data-lucide="check" class="w-4 h-4"></i> صلاحيات المستخدمين</div>
                </div>
            </div>

            <div class="pricing-card reveal">
                <div class="text-sm font-bold text-gray-500">احترافي</div>
                <div class="text-4xl font-black mt-4">— <span class="text-sm text-gray-400">/ شهر</span></div>
                <p class="text-sm text-gray-500 leading-7 mt-4">للشركات التي تحتاج تجربة موسعة وإدارة متقدمة.</p>
                <a href="/register" class="secondary-btn w-full mt-7">تواصل معنا</a>
                <div class="border-t mt-7 pt-6">
                    <div class="pricing-feature"><i data-lucide="check" class="w-4 h-4"></i> كل مزايا الأساسي</div>
                    <div class="pricing-feature"><i data-lucide="check" class="w-4 h-4"></i> أدوات متقدمة</div>
                    <div class="pricing-feature"><i data-lucide="check" class="w-4 h-4"></i> دعم موسع</div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="py-20 md:py-28 bg-white">
    <div class="container-main max-w-4xl">
        <div class="text-center reveal">
            <span class="badge">الأسئلة الشائعة</span>
            <h2 class="text-3xl md:text-4xl font-black mt-5">لديك سؤال؟</h2>
            <p class="text-gray-500 mt-4">إليك إجابات سريعة على أكثر الأسئلة شيوعًا.</p>
        </div>

        <div class="space-y-3 mt-10">
            @php
                $faqs = [
                    ['q'=>'هل أحتاج إلى خبرة تقنية؟','a'=>'لا. تم تصميم الواجهة لتكون بسيطة وسهلة الاستخدام، ويمكنك إدارة المنتجات والطلبات من خلال لوحة التحكم.'],
                    ['q'=>'هل يمكنني إدارة أكثر من متجر؟','a'=>'نعم، النظام مصمم لدعم بيئة متعددة المتاجر مع إدارة مركزية.'],
                    ['q'=>'هل النظام يعمل على الجوال؟','a'=>'نعم، الواجهة متجاوبة ومصممة لتعمل على الجوال والتابلت والحاسوب.'],
                    ['q'=>'هل يمكنني إضافة منتجات كثيرة؟','a'=>'يمكنك إضافة المنتجات وفق إعدادات وخطة النظام الخاصة بك، مع إدارة المخزون والتصنيفات.'],
                    ['q'=>'هل يمكن تطوير النظام لاحقًا؟','a'=>'نعم، يمكن توسيع النظام وإضافة بوابات دفع وشحن وتقارير وصلاحيات وخدمات أخرى حسب الحاجة.'],
                ];
            @endphp

            @foreach($faqs as $faq)
                <div class="faq-item reveal">
                    <button class="faq-button" type="button">
                        <span>{{ $faq['q'] }}</span>
                        <i data-lucide="chevron-down" class="faq-icon w-5 h-5 text-gray-400"></i>
                    </button>
                    <div class="faq-answer">{{ $faq['a'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 md:py-24">
    <div class="container-main">
        <div class="relative overflow-hidden rounded-[32px] px-6 py-14 md:px-14 md:py-16 text-center text-white" style="background:linear-gradient(135deg,#111827,#1f2937)">
            <div class="absolute w-72 h-72 rounded-full bg-orange-500/10 blur-3xl -top-32 -right-20"></div>
            <div class="absolute w-72 h-72 rounded-full bg-orange-500/10 blur-3xl -bottom-32 -left-20"></div>

            <div class="relative reveal">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-xs font-bold"><span class="w-2 h-2 rounded-full bg-green-400"></span> جاهز للبدء؟</div>
                <h2 class="text-3xl md:text-5xl font-black mt-6">ابدأ بناء متجرك اليوم</h2>
                <p class="text-gray-400 mt-5 max-w-2xl mx-auto leading-8">أنشئ متجرك، أضف منتجاتك، وتابع طلباتك من لوحة واحدة سهلة الاستخدام.</p>

                <div class="flex flex-col sm:flex-row justify-center gap-3 mt-8">
                    <a href="/register" class="primary-btn bg-white !text-orange-600">إنشاء حساب مجاني <i data-lucide="arrow-left" class="w-5 h-5"></i></a>
                    <a href="/login" class="secondary-btn !bg-white/5 !border-white/10 !text-white hover:!bg-white hover:!text-gray-900">تسجيل الدخول</a>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="bg-gray-950 text-white pt-14 pb-8">
    <div class="container-main">
        <div class="grid md:grid-cols-4 gap-10">
            <div class="md:col-span-2">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center" style="background:linear-gradient(135deg,#f59e0b,#f97316)">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    </div>
                    <div class="font-black text-xl">Multi<span class="text-orange-400">Store</span></div>
                </div>
                <p class="text-gray-400 text-sm leading-8 mt-5 max-w-md">منصة متكاملة تساعدك على إنشاء وإدارة متجرك الإلكتروني ومتابعة المنتجات والطلبات والعملاء من مكان واحد.</p>
            </div>

            <div>
                <h3 class="font-black mb-4">روابط سريعة</h3>
                <div class="space-y-3 text-sm text-gray-400">
                    <a href="#features" class="block hover:text-orange-400">المميزات</a>
                    <a href="#showcase" class="block hover:text-orange-400">النظام</a>
                    <a href="#marketing" class="block hover:text-orange-400">المتجر</a>
                    <a href="#pricing" class="block hover:text-orange-400">الأسعار</a>
                    <a href="#faq" class="block hover:text-orange-400">الأسئلة الشائعة</a>
                </div>
            </div>

            <div>
                <h3 class="font-black mb-4">الحساب</h3>
                <div class="space-y-3 text-sm text-gray-400">
                    <a href="/login" class="block hover:text-orange-400">تسجيل الدخول</a>
                    <a href="/register" class="block hover:text-orange-400">إنشاء حساب</a>
                    <a href="/dashboard" class="block hover:text-orange-400">لوحة التحكم</a>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 mt-12 pt-6 flex flex-col md:flex-row gap-3 justify-between text-xs text-gray-500">
            <div>© {{ date('Y') }} MultiStore. جميع الحقوق محفوظة.</div>
            <div>منصة التجارة الإلكترونية</div>
        </div>
    </div>
</footer>

<button id="backTop" class="fixed bottom-5 left-5 w-11 h-11 rounded-2xl bg-gray-900 text-white shadow-xl hidden items-center justify-center z-40">
    <i data-lucide="arrow-up" class="w-5 h-5"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded',function(){

    if(window.lucide){lucide.createIcons();}

    const menuButton=document.getElementById('menuButton');
    const mobileMenu=document.getElementById('mobileMenu');

    if(menuButton&&mobileMenu){
        menuButton.addEventListener('click',function(){
            mobileMenu.classList.toggle('open');
        });

        mobileMenu.querySelectorAll('a').forEach(function(link){
            link.addEventListener('click',function(){
                mobileMenu.classList.remove('open');
            });
        });
    }

    const header=document.getElementById('mainHeader');

    window.addEventListener('scroll',function(){
        if(window.scrollY>20){
            header.classList.add('header-shadow');
        }else{
            header.classList.remove('header-shadow');
        }
    },{passive:true});

    document.querySelectorAll('.faq-button').forEach(function(button){
        button.addEventListener('click',function(){
            const item=button.closest('.faq-item');

            document.querySelectorAll('.faq-item.open').forEach(function(openItem){
                if(openItem!==item){
                    openItem.classList.remove('open');
                }
            });

            item.classList.toggle('open');
        });
    });

    const revealItems=document.querySelectorAll('.reveal');

    if('IntersectionObserver' in window){
        const observer=new IntersectionObserver(function(entries){
            entries.forEach(function(entry){
                if(entry.isIntersecting){
                    entry.target.classList.add('show');
                    observer.unobserve(entry.target);
                }
            });
        },{threshold:.08});

        revealItems.forEach(function(item){
            observer.observe(item);
        });
    }else{
        revealItems.forEach(function(item){
            item.classList.add('show');
        });
    }

    const backTop=document.getElementById('backTop');

    window.addEventListener('scroll',function(){
        if(window.scrollY>500){
            backTop.classList.remove('hidden');
            backTop.classList.add('flex');
        }else{
            backTop.classList.add('hidden');
            backTop.classList.remove('flex');
        }
    },{passive:true});

    backTop.addEventListener('click',function(){
        window.scrollTo({top:0,behavior:'smooth'});
    });

});
</script>

</body>
</html>
