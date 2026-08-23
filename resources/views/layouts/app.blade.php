<!DOCTYPE html>
<html lang="bn" class="h-full antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmartPOS') — {{ $currentBranch->restaurant_name ?? "Sultan's Dine" }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@600;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body x-data="globalLayout()" class="h-screen w-screen flex flex-col md:flex-row overflow-hidden" style="background:#F5F0EC;">

    <!-- ═══════════════════════════════════════════════════ -->
    <!--  MOBILE TOP HEADER BAR (Mobile Only: < md)         -->
    <!-- ═══════════════════════════════════════════════════ -->
    <header class="md:hidden h-[54px] px-3.5 flex items-center justify-between shrink-0 sidebar z-30 shadow-md">
        <!-- Left: Hamburger & Brand -->
        <div class="flex items-center gap-2.5">
            <button @click="mobileSidebarOpen = true"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-white"
                    style="background: rgba(255,255,255,0.12);"
                    aria-label="Open Navigation">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>

            <a href="{{ route('pos.index') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center shrink-0 shadow-xs"
                     style="background: rgba(255,255,255,0.08); border: 1px solid rgba(212,172,80,0.5);">
                    <img src="{{ $currentBranch->logo ?? '/images/logo.svg' }}" alt="Logo" class="w-full h-full object-contain p-0.5">
                </div>
                <div class="leading-tight">
                    <p class="text-xs font-black text-white truncate max-w-[130px]">{{ $currentBranch->restaurant_name ?? "Sultan's Dine" }}</p>
                </div>
            </a>
        </div>

        <!-- Right: Shift Pill & Online status -->
        <div class="flex items-center gap-2">
            <button @click="openShiftModal = true"
                    class="px-2 py-1 rounded-lg text-[10px] font-bold flex items-center gap-1 text-white"
                    style="background: rgba(255,255,255,0.12);">
                <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $activeShift ? '#4ADE80' : '#EF4444' }};"></span>
                <span>{{ $activeShift ? 'শিফট চালু' : 'শিফট বন্ধ' }}</span>
            </button>
        </div>
    </header>

    <!-- ═══════════════════════════════════════════════════ -->
    <!--  MOBILE OFF-CANVAS SIDEBAR DRAWER (Mobile: < md)   -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="mobileSidebarOpen" x-cloak class="md:hidden fixed inset-0 z-50 flex">
        <!-- Backdrop Overlay -->
        <div @click="mobileSidebarOpen = false"
             x-show="mobileSidebarOpen"
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

        <!-- Slide Drawer Panel -->
        <div x-show="mobileSidebarOpen"
             x-transition:enter="transition ease-in-out duration-250 transform"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="relative w-[280px] max-w-[85vw] h-full sidebar flex flex-col justify-between z-10 shadow-2xl">

            <!-- Drawer Header -->
            <div class="h-[60px] px-4 flex items-center justify-between border-b" style="border-color: rgba(255,255,255,0.1);">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl overflow-hidden flex items-center justify-center shrink-0 shadow-xs"
                         style="background: rgba(255,255,255,0.08); border: 1.5px solid rgba(212,172,80,0.5);">
                        <img src="{{ $currentBranch->logo ?? '/images/logo.svg' }}" alt="Logo" class="w-full h-full object-contain p-0.5">
                    </div>
                    <div class="leading-tight">
                        <p class="text-sm font-black text-white truncate">{{ $currentBranch->restaurant_name ?? "Sultan's Dine" }}</p>
                        <p class="text-[10px] font-bold uppercase" style="color:#D4AC50;">{{ $currentBranch->name ?? "Main Branch" }}</p>
                    </div>
                </div>
                <button @click="mobileSidebarOpen = false" class="text-white/60 hover:text-white p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Drawer Cashier Widget -->
            <div class="p-3 border-b" style="border-color: rgba(255,255,255,0.08);">
                <div class="p-2.5 rounded-xl cursor-pointer"
                     style="background: rgba(255,255,255,0.08);"
                     @click="mobileSidebarOpen = false; openShiftModal = true;">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs shrink-0"
                             style="background: #D4AC50; color: #3D0A12;">
                            {{ strtoupper(substr($currentUser->name ?? 'C', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-white truncate">{{ $currentUser->name ?? 'ক্যাশিয়ার' }}</p>
                            @if($activeShift)
                                <p class="text-[10px] flex items-center gap-1 mt-0.5" style="color:#86EFAC;">
                                    <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#4ADE80;"></span>
                                    শিফট চালু (৳{{ number_format($activeShift->opening_float, 0) }})
                                </p>
                            @else
                                <p class="text-[10px] flex items-center gap-1 mt-0.5" style="color:#FCA5A5;">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background:#EF4444;"></span>
                                    শিফট বন্ধ · শুরু করুন
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Drawer Navigation Links -->
            <nav class="flex-1 overflow-y-auto p-2 space-y-1">
                @php
                    $navLinks = [
                        ['route'=>'pos.index',         'match'=>'pos.*',             'icon'=>'shopping-cart',   'label'=>'POS টার্মিনাল'],
                        ['route'=>'waiter.index',      'match'=>'waiter.*',          'icon'=>'chef-hat',        'label'=>'ক্যাপ্টেন ও ওয়েটার'],
                        ['route'=>'menu.index',        'match'=>'menu.*',            'icon'=>'utensils',        'label'=>'মেনু ও খাবার আইটেম'],
                        ['route'=>'tables.index',      'match'=>'tables.*',          'icon'=>'layout-grid',     'label'=>'টেবিল ও ফ্লোরপ্ল্যান'],
                        ['route'=>'tables.qrCards',    'match'=>'tables.qrCards',    'icon'=>'qr-code',         'label'=>'টেবিল QR কার্ডস'],
                        ['route'=>'customers.index',   'match'=>'customers.*',       'icon'=>'users',           'label'=>'কাস্টমার CRM ও পয়েন্ট'],
                        ['route'=>'expenses.index',    'match'=>'expenses.*',        'icon'=>'calculator',      'label'=>'খরচ ও লাভ-ক্ষতি P&L'],
                        ['route'=>'delivery.index',    'match'=>'delivery.*',        'icon'=>'bike',            'label'=>'অনলাইন ডেলিভারি'],
                        ['route'=>'transfers.index',   'match'=>'transfers.*',       'icon'=>'truck',           'label'=>'ব্রাঞ্চ স্টক ট্রান্সফার'],
                        ['route'=>'inventory.index',   'match'=>'inventory.*',       'icon'=>'boxes',           'label'=>'ইনভেন্টরি ও BOM'],
                        ['route'=>'reports.dashboard', 'match'=>'reports.dashboard', 'icon'=>'bar-chart-3',     'label'=>'সেলস ড্যাশবোর্ড'],
                        ['route'=>'reports.mushak',    'match'=>'reports.mushak',    'icon'=>'file-badge-2',    'label'=>'NBR মূসক ৬.৩ চালান'],
                    ];

                    if (auth()->user()?->isSuperAdmin()) {
                        $navLinks[] = ['route'=>'saas.dashboard', 'match'=>'saas.*', 'icon'=>'building-2', 'label'=>'SaaS সুপার-অ্যাডমিন'];
                    }

                    $navLinks[] = ['route'=>'settings.index', 'match'=>'settings.*', 'icon'=>'settings', 'label'=>'সিস্টেম সেটিংস'];
                @endphp

                @foreach($navLinks as $link)
                    @php $active = request()->routeIs($link['match']); @endphp
                    <a href="{{ route($link['route']) }}"
                       @click="mobileSidebarOpen = false"
                       class="nav-item {{ $active ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold">
                        <i data-lucide="{{ $link['icon'] }}" class="nav-icon w-[18px] h-[18px] shrink-0"
                           style="color: {{ $active ? '#D4AC50' : 'rgba(255,255,255,0.6)' }};"></i>
                        <span class="flex-1 truncate text-white">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <!-- Drawer Footer with Logout -->
            <div class="p-3 border-t space-y-2" style="border-color: rgba(255,255,255,0.1);">
                <button @click="logoutConfirm()"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl text-xs font-bold text-white transition-all shadow-xs"
                        style="background:rgba(239,68,68,0.25); border:1px solid rgba(239,68,68,0.4);">
                    <i data-lucide="log-out" class="w-4 h-4 text-rose-300"></i>
                    <span>🚪 লগআউট (Logout)</span>
                </button>
                <div class="text-[10px] text-white/50 text-center">
                    {{ $currentBranch->restaurant_name ?? "Sultan's Dine" }} POS v2.0
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!--  DESKTOP SIDEBAR (Hidden on mobile: md:flex)       -->
    <!-- ═══════════════════════════════════════════════════ -->
    <aside class="hidden md:flex sidebar h-screen flex-col justify-between shrink-0 z-40 transition-all duration-200 ease-in-out select-none"
           :class="collapsed ? 'w-[64px]' : 'w-[245px]'">

        <!-- Brand Header -->
        <div class="flex-1 overflow-y-auto">
            <div class="sidebar-brand h-[60px] px-4 flex items-center justify-between sticky top-0 z-10">
                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 overflow-hidden min-w-0">
                    <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center shrink-0 shadow-xs"
                         style="background: rgba(255,255,255,0.08); border: 1.5px solid rgba(212,172,80,0.5);">
                        <img src="{{ $currentBranch->logo ?? '/images/logo.svg' }}" alt="Logo" class="w-full h-full object-contain p-0.5">
                    </div>
                    <div x-show="!collapsed" class="min-w-0 leading-tight">
                        <p class="text-sm font-black tracking-tight text-white truncate">{{ $currentBranch->restaurant_name ?? "Sultan's Dine" }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-widest truncate" style="color:#D4AC50;">{{ $currentBranch->name ?? "Main Branch" }}</p>
                    </div>
                </a>
                <button @click="collapsed = !collapsed"
                        class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors shrink-0"
                        style="color: rgba(255,255,255,0.45);"
                        onmouseover="this.style.color='#fff'; this.style.background='rgba(255,255,255,0.1)'"
                        onmouseout="this.style.color='rgba(255,255,255,0.45)'; this.style.background='transparent'">
                    <i :data-lucide="collapsed ? 'chevrons-right' : 'chevrons-left'" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Dynamic Active Cashier / Shift Widget -->
            <div x-show="!collapsed" class="mx-3 mt-3 p-2.5 rounded-xl cursor-pointer transition-all"
                 style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12);"
                 @click="openShiftModal = true"
                 onmouseover="this.style.background='rgba(255,255,255,0.12)'"
                 onmouseout="this.style.background='rgba(255,255,255,0.07)'"
                 title="প্রোফাইল ও শিফট ম্যানেজমেন্ট খুলতে ক্লিক করুন">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs shrink-0"
                         style="background: #D4AC50; color: #3D0A12;">
                        {{ strtoupper(substr($currentUser->name ?? 'C', 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-white truncate">{{ $currentUser->name ?? 'ক্যাশিয়ার' }}</p>
                        @if($activeShift)
                            <p class="text-[10px] flex items-center gap-1 mt-0.5" style="color:#86EFAC;">
                                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#4ADE80;"></span>
                                শিফট চালু ({{ $currentBranch->currency_symbol ?? '৳' }}{{ number_format($activeShift->opening_float, 0) }})
                            </p>
                        @else
                            <p class="text-[10px] flex items-center gap-1 mt-0.5" style="color:#FCA5A5;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background:#EF4444;"></span>
                                শিফট বন্ধ · শুরু করুন
                            </p>
                        @endif
                    </div>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5" style="color:rgba(255,255,255,0.4);"></i>
                </div>
            </div>

            <!-- Section Label -->
            <p x-show="!collapsed" class="section-heading px-5 pt-4 pb-1.5" style="color:rgba(255,255,255,0.3);">মেইন মেনু</p>

            <!-- Desktop Navigation Links -->
            <nav class="px-2 space-y-0.5">
                @foreach($navLinks as $link)
                    @php $active = request()->routeIs($link['match']); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="nav-item {{ $active ? 'active' : '' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold">
                        <i data-lucide="{{ $link['icon'] }}" class="nav-icon w-[18px] h-[18px] shrink-0 stroke-[1.8]"
                           style="color: {{ $active ? '#D4AC50' : 'rgba(255,255,255,0.5)' }};"></i>
                        <span x-show="!collapsed" class="flex-1 truncate">{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-bottom p-3 space-y-2 shrink-0">
            <!-- Online / Offline -->
            <div x-data="{ online: navigator.onLine }"
                 x-init="window.addEventListener('online',()=>online=true); window.addEventListener('offline',()=>online=false);"
                 class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl text-[11px] font-semibold"
                 :style="online ? 'background:rgba(46,125,82,0.25); border:1px solid rgba(46,125,82,0.4); color:#86EFAC;' : 'background:rgba(192,32,32,0.25); border:1px solid rgba(192,32,32,0.4); color:#FCA5A5;'">
                <span class="w-2 h-2 rounded-full shrink-0 animate-pulse"
                      :style="online ? 'background:#4ADE80;' : 'background:#F87171;'"></span>
                <span x-show="!collapsed" x-text="online ? 'অনলাইন ডাটাবেস' : 'অফলাইন মোড'"></span>
            </div>
            <!-- Clock -->
            <div x-show="!collapsed"
                 x-data="{ t:'' }"
                 x-init="setInterval(()=>{ const d=new Date(); t=d.toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:true}); },1000)"
                 class="flex justify-between items-center px-3 py-1 rounded-xl text-[11px]"
                 style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.1);">
                <span style="color:rgba(255,255,255,0.35);">সময়</span>
                <span class="pos-nums font-bold text-white" x-text="t||'{{ now()->format('h:i:s A') }}'"></span>
            </div>

            <!-- Prominent Direct Logout Button -->
            <button @click="logoutConfirm()"
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-white transition-all group"
                    style="background:rgba(239,68,68,0.2); border:1px solid rgba(239,68,68,0.35);"
                    onmouseover="this.style.background='rgba(239,68,68,0.4)'; this.style.borderColor='#EF4444'"
                    onmouseout="this.style.background='rgba(239,68,68,0.2)'; this.style.borderColor='rgba(239,68,68,0.35)'"
                    title="সফটওয়্যার থেকে লগআউট করুন">
                <i data-lucide="log-out" class="w-4 h-4 text-rose-400 group-hover:text-white transition-colors"></i>
                <span x-show="!collapsed" class="truncate text-rose-200 group-hover:text-white">🚪 লগআউট (Logout)</span>
            </button>
        </div>
    </aside>

    <!-- ═══════════════════════════════════════════════════ -->
    <!--  MAIN CONTENT WRAPPER                              -->
    <!-- ═══════════════════════════════════════════════════ -->
    <main class="flex-1 h-[calc(100vh-54px)] md:h-screen flex flex-col overflow-hidden" style="background:#F5F0EC;">
        @if(session()->has('impersonating_superadmin_id'))
            <div class="bg-amber-500 text-black px-4 py-2 text-xs font-black flex items-center justify-between shadow-md shrink-0 z-50 border-b border-amber-600">
                <div class="flex items-center gap-2">
                    <i data-lucide="eye" class="w-4 h-4 text-black shrink-0"></i>
                    <span class="truncate">⚠️ আপনি বর্তমানে <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role }}) হিসেবে কাজ করছেন। রেস্টুরেন্ট: {{ $currentBranch->restaurant_name ?? '' }}</span>
                </div>
                <a href="{{ route('impersonate.leave') }}" class="bg-black text-white hover:bg-neutral-900 px-3 py-1 rounded-lg text-xs font-bold transition-all shrink-0 shadow-xs flex items-center gap-1">
                    <span>সুপার-অ্যাডমিনে ফেরত যান</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        @endif
        <div class="flex-1 overflow-y-auto overflow-x-hidden">
            @yield('content')
        </div>
    </main>

    <!-- ═══════════════════════════════════════════════════ -->
    <!--  MOBILE BOTTOM NAVIGATION BAR                      -->
    <!-- ═══════════════════════════════════════════════════ -->
    <nav class="md:hidden h-[56px] bg-white border-t flex items-center justify-around shrink-0 z-20 shadow-lg px-1"
         style="border-color:#E0D4CF;">
        @php
            $mobileTabs = [
                ['route'=>'pos.index',         'match'=>'pos.*',             'icon'=>'shopping-cart', 'label'=>'POS'],
                ['route'=>'kds.index',         'match'=>'kds.*',             'icon'=>'flame',         'label'=>'KDS'],
                ['route'=>'menu.index',        'match'=>'menu.*',            'icon'=>'utensils',      'label'=>'মেনু'],
                ['route'=>'tables.index',      'match'=>'tables.*',          'icon'=>'layout-grid',   'label'=>'টেবিল'],
                ['route'=>'reports.dashboard', 'match'=>'reports.dashboard', 'icon'=>'bar-chart-3',   'label'=>'রিপোর্ট'],
            ];
        @endphp
        @foreach($mobileTabs as $mt)
            @php $active = request()->routeIs($mt['match']); @endphp
            <a href="{{ route($mt['route']) }}"
               class="flex flex-col items-center justify-center py-1 px-2.5 rounded-xl transition-all"
               style="{{ $active ? 'color:#8B1A2C;' : 'color:#9B7A7E;' }}">
                <i data-lucide="{{ $mt['icon'] }}" class="w-5 h-5 {{ $active ? 'stroke-[2.5]' : '' }}"></i>
                <span class="text-[10px] font-bold mt-0.5">{{ $mt['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <!-- ═══════════════════════════════════════════════════ -->
    <!--  GLOBAL SHIFT MANAGEMENT MODAL                     -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openShiftModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openShiftModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <div class="flex items-center gap-2 text-white">
                    <i data-lucide="clock" class="w-5 h-5" style="color:#D4AC50;"></i>
                    <h3 class="text-sm font-bold">ক্যাশিয়ার শিফট ম্যানেজমেন্ট</h3>
                </div>
                <button @click="openShiftModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 space-y-4">
                @if($activeShift)
                    <!-- Active Shift Details -->
                    <div class="p-4 rounded-2xl border space-y-2" style="background:#FBF1F3; border-color:rgba(139,26,44,0.2);">
                        <div class="flex justify-between text-xs">
                            <span style="color:#9B7A7E;">বর্তমান ক্যাশিয়ার:</span>
                            <strong style="color:#1A0A0C;">{{ $activeShift->user->name ?? 'N/A' }}</strong>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span style="color:#9B7A7E;">শিফট শুরুর সময়:</span>
                            <span class="pos-nums font-bold" style="color:#1A0A0C;">{{ $activeShift->opened_at ? \Carbon\Carbon::parse($activeShift->opened_at)->format('d M, h:i A') : 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span style="color:#9B7A7E;">শুরুর ক্যাশ ড্রয়ার ফ্লট:</span>
                            <span class="pos-nums font-black price-maroon">৳{{ number_format($activeShift->opening_float, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span style="color:#9B7A7E;">আজকের নগদ বিক্রয়:</span>
                            <span class="pos-nums font-black" style="color:#2E7D52;">৳{{ number_format($activeShift->cash_sales, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs pt-1 border-t" style="border-color:rgba(139,26,44,0.15);">
                            <span class="font-bold" style="color:#1A0A0C;">ড্রয়ারে প্রত্যাশিত মোট টাকা:</span>
                            <span class="pos-nums font-black text-sm price-maroon">৳{{ number_format($activeShift->opening_float + $activeShift->cash_sales, 2) }}</span>
                        </div>
                    </div>

                    <!-- Close Shift Form -->
                    <div class="space-y-3">
                        <label class="section-heading">শিফট ক্লোজিং — ড্রয়ারে গুনে পাওয়া ক্যাশ (৳):</label>
                        <input type="number" step="0.01" x-model.number="closeActualCash" placeholder="0.00"
                               class="pos-input w-full px-3 py-2.5 text-base pos-nums font-black rounded-xl" style="color:#1A0A0C;">
                        <textarea x-model="closeShiftNote" rows="2" placeholder="ক্লোজিং নোট বা মন্তব্য (ঐচ্ছিক)..."
                                  class="pos-input w-full p-2.5 text-xs rounded-xl resize-none"></textarea>
                    </div>

                    <button @click="submitCloseShift({{ $activeShift->id }})"
                            class="btn-maroon w-full py-3 rounded-2xl text-xs font-bold flex items-center justify-center gap-2">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        <span>শিফট ক্লোজ করুন ও Z-Report জেনারেট করুন</span>
                    </button>
                @else
                    <!-- Open New Shift Form -->
                    <div class="space-y-4">
                        <div class="p-3 rounded-2xl text-center" style="background:#FEF3C7; border:1px solid #FCD34D;">
                            <p class="text-xs font-bold" style="color:#92400E;">বর্তমানে কোনো ক্যাশিয়ার শিফট চালু নেই।</p>
                        </div>

                        <div>
                            <label class="section-heading">ক্যাশ ড্রয়ার ওপেনিং ফ্লট (Opening Cash ৳):</label>
                            <input type="number" step="1" x-model.number="openFloatAmount" placeholder="2500"
                                   class="pos-input w-full px-3 py-2.5 text-base pos-nums font-black rounded-xl">
                            <p class="text-[11px] mt-1" style="color:#9B7A7E;">ক্যাশ ড্রয়ারে থাকা শুরু ভাংতি টাকা লিখুন।</p>
                        </div>

                        <button @click="submitOpenShift()"
                                class="btn-maroon w-full py-3 rounded-2xl text-xs font-bold flex items-center justify-center gap-2">
                            <i data-lucide="key" class="w-4 h-4"></i>
                            <span>নতুন ক্যাশিয়ার শিফট শুরু করুন</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function globalLayout() {
            return {
                collapsed: false,
                mobileSidebarOpen: false,
                openShiftModal: false,
                openFloatAmount: 2000,
                closeActualCash: null,
                closeShiftNote: '',

                async submitOpenShift() {
                    if (this.openFloatAmount === null || this.openFloatAmount < 0) {
                        alert('অনুগ্রহ করে সঠিক ওপেনিং ফ্লট এমাউন্ট দিন!'); return;
                    }
                    try {
                        const res = await fetch('{{ route('pos.shift.open') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ opening_float: this.openFloatAmount })
                        });
                        const data = await res.json();
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message || 'শিফট ওপেন করতে সমস্যা হয়েছে!');
                        }
                    } catch (e) {
                        alert('ত্রুটি: ' + e.message);
                    }
                },

                async submitCloseShift(shiftId) {
                    if (this.closeActualCash === null || this.closeActualCash < 0) {
                        alert('অনুগ্রহ করে ড্রয়ারে গুনে পাওয়া টাকার সঠিক পরিমাণ দিন!'); return;
                    }
                    if (!confirm('আপনি কি নিশ্চিতভাবে এই শিফট ক্লোজ করতে চান?')) return;

                    try {
                        const res = await fetch(`/pos/shift/${shiftId}/close`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({
                                actual_cash_counted: this.closeActualCash,
                                closing_note: this.closeShiftNote
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert(data.message || 'শিফট ক্লোজ করতে সমস্যা হয়েছে!');
                        }
                    } catch (e) {
                        alert('ত্রুটি: ' + e.message);
                    }
                },

                logoutConfirm() {
                    if (confirm('আপনি কি নিশ্চিতভাবে সিস্টেম থেকে লগআউট করতে চান?')) {
                        document.getElementById('logoutForm').submit();
                    }
                }
            };
        }

        window.playBeep = (freq=880, duration=120) => {
            try {
                const ctx = new (window.AudioContext||window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type='sine'; osc.frequency.value=freq;
                osc.connect(gain); gain.connect(ctx.destination);
                osc.start();
                gain.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + duration/1000);
                setTimeout(()=>{ osc.stop(); ctx.close(); }, duration);
            } catch(e){}
        };
    </script>

    <!-- Hidden Form for Safe POST Logout -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    @stack('scripts')
</body>
</html>
