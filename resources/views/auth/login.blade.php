<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>লগইন — রেস্টুরেন্ট POS & ক্লাউড SaaS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@600;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Hind Siliguri', 'Inter', sans-serif; }
        .pos-nums { font-family: 'JetBrains Mono', monospace; }
        .glass-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="min-h-full flex items-center justify-center p-3 sm:p-6 select-none"
      style="background: radial-gradient(circle at top, #8B1A2C 0%, #3D0711 50%, #150306 100%);">

    <div x-data="loginTerminal()" x-init="init()" class="w-full max-w-lg glass-card rounded-3xl sm:rounded-[32px] shadow-2xl overflow-hidden border border-white/20 my-auto">

        <!-- Top Luxury Maroon Header -->
        <div class="px-5 py-6 sm:p-7 text-center text-white relative overflow-hidden"
             style="background: linear-gradient(135deg, #4A0813 0%, #7A1424 50%, #9B1C2E 100%);">
            <div class="absolute -right-10 -top-10 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-36 h-36 rounded-full bg-black/10 pointer-events-none"></div>

            <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl mx-auto flex items-center justify-center mb-3 shadow-lg transition-transform hover:scale-105"
                 style="background: rgba(212,172,80,0.2); border: 2px solid rgba(212,172,80,0.6); box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i data-lucide="utensils-crossed" class="w-6 h-6 sm:w-7 sm:h-7 stroke-[2.5]" style="color:#D4AC50;"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">{{ $branch->restaurant_name ?? "Sultan's Dine" }}</h1>
            <div class="flex items-center justify-center gap-2 mt-1">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <p class="text-xs font-semibold text-white/90">রেস্টুরেন্ট POS ও SaaS ক্লাউড প্ল্যাটফর্ম</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border-b border-emerald-200 text-emerald-800 text-xs font-bold text-center flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-3.5 bg-rose-50 border-b border-rose-200 text-rose-800 text-xs font-bold text-center flex items-center justify-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div class="p-4 sm:p-6 space-y-4 sm:space-y-5" style="background:#FAF7F5;">

            <!-- Quick 1-Tap Role Selector for Touch Terminals / Mobile -->
            <div>
                <div class="flex items-center justify-between mb-2 px-1">
                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">দ্রুত ভূমিকা নির্বাচন (১-ট্যাপে লগইন)</span>
                    <span class="text-[10px] font-semibold text-rose-800 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">টাচ অপ্টিমাইজড</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-2.5">
                    @php
                        $roles = [
                            ['role' => 'admin',   'icon' => 'shield-check', 'name' => 'ম্যানেজার', 'desc' => 'পূর্ণ এক্সেস', 'email' => 'kamrul@sultansdine.com', 'pin' => '1234', 'color' => '#8B1A2C'],
                            ['role' => 'cashier', 'icon' => 'shopping-cart', 'name' => 'ক্যাশিয়ার', 'desc' => 'POS বিলিং',   'email' => 'cashier@sultansdine.com', 'pin' => '1234', 'color' => '#2E7D52'],
                            ['role' => 'kitchen', 'icon' => 'flame',         'name' => 'কিচেন শেফ', 'desc' => 'KDS স্ক্রিন',  'email' => 'chef@sultansdine.com',    'pin' => '1234', 'color' => '#D97706'],
                            ['role' => 'waiter',  'icon' => 'user-check',    'name' => 'ওয়েটার',   'desc' => 'অর্ডার গ্রহণ',  'email' => 'waiter@sultansdine.com',  'pin' => '1234', 'color' => '#7C3AED'],
                        ];
                    @endphp

                    @foreach($roles as $r)
                        <button type="button"
                                @click="fillQuick('{{ $r['email'] }}', '{{ $r['pin'] }}', '{{ $r['role'] }}')"
                                class="p-2.5 sm:p-3 rounded-2xl bg-white border transition-all flex flex-col items-center justify-center gap-1 active:scale-95 text-center shadow-xs"
                                :style="activeRole === '{{ $r['role'] }}' ? 'border-color:{{ $r['color'] }}; background:{{ $r['color'] }}0A; box-shadow:0 0 0 2px {{ $r['color'] }}30;' : 'border-color:#E5E7EB;'">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center transition-transform"
                                 style="background: {{ $r['color'] }}15; color: {{ $r['color'] }};">
                                <i data-lucide="{{ $r['icon'] }}" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-800 leading-none mt-1">{{ $r['name'] }}</span>
                            <span class="text-[9px] text-gray-400">{{ $r['desc'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Divider -->
            <div class="flex items-center gap-3 my-1">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-[10px] uppercase font-bold text-gray-400">অথবা তথ্য দিয়ে লগইন</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-3.5 sm:space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">ইমেইল বা ইউজারনেম</label>
                    <div class="relative">
                        <div class="w-9 h-9 absolute left-1 top-1 flex items-center justify-center text-gray-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="email" x-model="email" required
                               placeholder="user@restaurant.com"
                               class="w-full pl-10 pr-3.5 py-2.5 sm:py-3 rounded-2xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 focus:border-rose-800 focus:outline-hidden bg-white shadow-2xs transition-all">
                    </div>
                </div>

                <!-- Password Input with Show/Hide Toggle -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-gray-700">পাসওয়ার্ড বা ৪-সংখ্যার পিন</label>
                        <span class="text-[10px] font-mono text-gray-400">PIN: 1234</span>
                    </div>
                    <div class="relative">
                        <div class="w-9 h-9 absolute left-1 top-1 flex items-center justify-center text-gray-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-2.5 sm:py-3 rounded-2xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 focus:border-rose-800 focus:outline-hidden bg-white shadow-2xs transition-all">
                        <button type="button" @click="showPassword = !showPassword"
                                class="w-9 h-9 absolute right-1 top-1 flex items-center justify-center text-gray-400 hover:text-gray-600">
                            <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Cash Float Drawer (Only needed for Cashier/Shift) -->
                <div x-show="activeRole === 'cashier' || activeRole === 'admin'" x-transition class="p-3 bg-rose-50/60 rounded-2xl border border-rose-100 space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-rose-950">প্রারম্ভিক ক্যাশ ড্রয়ার ফ্লট</label>
                        <span class="text-[10px] text-rose-700 font-bold">ক্যাশিয়ার শিফট শুরু</span>
                    </div>
                    <div class="relative">
                        <span class="w-8 h-8 absolute left-1 top-1 flex items-center justify-center text-xs font-bold text-rose-800">৳</span>
                        <input type="number" name="opening_float" value="2000" min="0"
                               class="w-full pl-8 pr-3.5 py-2 rounded-xl border border-rose-200 text-xs font-bold pos-nums text-rose-950 focus:ring-2 focus:ring-rose-800 bg-white">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full py-3.5 sm:py-4 rounded-2xl text-xs sm:text-sm font-black text-white flex items-center justify-center gap-2 shadow-lg transition-all active:scale-[0.98] cursor-pointer"
                        style="background: linear-gradient(135deg, #5C0F1B 0%, #8B1A2C 100%); box-shadow: 0 6px 20px rgba(139,26,44,0.35);">
                    <i data-lucide="log-in" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    <span>সফটওয়্যারে প্রবেশ করুন</span>
                </button>
            </form>

            <!-- SaaS Register & Plan Links -->
            <div class="pt-3 border-t border-gray-200 text-center space-y-2">
                <p class="text-xs text-gray-600">
                    নতুন রেস্টুরেন্ট চালু করতে চান? 
                    <a href="{{ route('saas.register') }}" class="font-black text-rose-800 hover:underline inline-flex items-center gap-1">
                        <span>১৪ দিনের ফ্রি ট্রায়াল নিন</span>
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </p>
                <div class="flex items-center justify-center gap-3 text-[11px] text-gray-500 pt-1">
                    <a href="{{ route('saas.plans') }}" class="hover:text-rose-800 font-semibold">প্যাকেজ ও প্রাইসিং</a>
                    <span>•</span>
                    <a href="{{ route('saas.dashboard') }}" class="hover:text-rose-800 font-bold">সুপার-অ্যাডমিন</a>
                </div>
            </div>

        </div>

    </div>

    <script>
        function loginTerminal() {
            return {
                email: 'kamrul@sultansdine.com',
                password: 'password',
                activeRole: 'admin',
                showPassword: false,

                init() {
                    this.$nextTick(() => window.initLucideIcons());
                },
                fillQuick(e, p, r) {
                    this.email = e;
                    this.password = p;
                    this.activeRole = r;
                    this.$nextTick(() => window.initLucideIcons());
                }
            };
        }
    </script>
</body>
</html>
