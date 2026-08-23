<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>লগইন ও রেজিস্ট্রেশন — রেস্টুরেন্ট POS & SaaS প্ল্যাটফর্ম</title>
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

    <div x-data="authApp()" x-init="init()" class="w-full max-w-lg glass-card rounded-3xl sm:rounded-[32px] shadow-2xl overflow-hidden border border-white/20 my-auto">

        <!-- Top Luxury Maroon Header -->
        <div class="px-5 py-6 sm:p-7 text-center text-white relative overflow-hidden"
             style="background: linear-gradient(135deg, #4A0813 0%, #7A1424 50%, #9B1C2E 100%);">
            <div class="absolute -right-10 -top-10 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute -left-10 -bottom-10 w-36 h-36 rounded-full bg-black/10 pointer-events-none"></div>

            <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl mx-auto flex items-center justify-center mb-3 shadow-lg transition-transform hover:scale-105"
                 style="background: rgba(212,172,80,0.2); border: 2px solid rgba(212,172,80,0.6); box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i data-lucide="utensils-crossed" class="w-6 h-6 sm:w-7 sm:h-7 stroke-[2.5]" style="color:#D4AC50;"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">{{ $branch->restaurant_name ?? "SmartPOS Enterprise" }}</h1>
            <div class="flex items-center justify-center gap-2 mt-1">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <p class="text-xs font-semibold text-white/90">রেস্টুরেন্ট POS ও ক্লাউড SaaS প্ল্যাটফর্ম</p>
            </div>
        </div>

        <!-- Feedback Alerts -->
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border-b border-emerald-200 text-emerald-800 text-xs font-bold text-center flex items-center justify-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 bg-rose-50 border-b border-rose-200 text-rose-800 text-xs font-bold text-center flex items-center justify-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-3.5 bg-rose-50 border-b border-rose-200 text-rose-800 text-xs font-bold text-center flex items-center justify-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Main Body -->
        <div class="p-4 sm:p-6 space-y-4 sm:space-y-5" style="background:#FAF7F5;">

            <!-- Tabs: Sign In / Sign Up -->
            <div class="grid grid-cols-2 p-1 rounded-2xl border" style="background:#EDE5E2; border-color:#E0D4CF;">
                <button type="button" @click="activeTab = 'login'"
                        class="py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-xs"
                        :style="activeTab === 'login' ? 'background:#8B1A2C; color:#fff; box-shadow:0 2px 8px rgba(139,26,44,0.3);' : 'color:#5C3840; background:transparent;'">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>লগইন (Sign In)</span>
                </button>
                <button type="button" @click="activeTab = 'register'"
                        class="py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5"
                        :style="activeTab === 'register' ? 'background:#8B1A2C; color:#fff; box-shadow:0 2px 8px rgba(139,26,44,0.3);' : 'color:#5C3840; background:transparent;'">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>নতুন রেস্টুরেন্ট রেজিস্ট্রেশন</span>
                </button>
            </div>

            <!-- TAB 1: LOGIN FORM -->
            <div x-show="activeTab === 'login'" x-transition>
                <form action="{{ route('login') }}" method="POST" class="space-y-3.5 sm:space-y-4">
                    @csrf

                    <!-- 11-digit Phone or Email Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-xs font-bold text-gray-700">১১ ডিজিটের মোবাইল নম্বর অথবা ইমেইল</label>
                            <span x-show="isCleanPhone11" x-cloak class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.2 rounded">
                                ✓ ১১ ডিজিট সঠিক
                            </span>
                        </div>
                        <div class="relative">
                            <div class="w-9 h-9 absolute left-1 top-1 flex items-center justify-center text-gray-400">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="login_id" x-model="loginId" required
                                   placeholder="01XXXXXXXXX অথবা email@domain.com"
                                   maxlength="50"
                                   class="w-full pl-10 pr-3.5 py-2.5 sm:py-3 rounded-2xl border text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 focus:border-rose-800 focus:outline-hidden bg-white shadow-2xs transition-all pos-nums"
                                   :style="isCleanPhone11 ? 'border-color:#10B981;' : 'border-color:#D1D5DB;'">
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1">ক্যাশিয়ার, ওয়েটার ও ম্যানেজাররা তাদের ১১ ডিজিট নম্বর বা পিন কোড দিয়ে লগইন করতে পারবেন।</p>
                    </div>

                    <!-- Password / PIN Input -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-xs font-bold text-gray-700">পাসওয়ার্ড অথবা ৪-সংখ্যার পিন</label>
                        </div>
                        <div class="relative">
                            <div class="w-9 h-9 absolute left-1 top-1 flex items-center justify-center text-gray-400">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required
                                   placeholder="•••••••• অথবা ৪-সংখ্যার পিন"
                                   class="w-full pl-10 pr-10 py-2.5 sm:py-3 rounded-2xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 focus:border-rose-800 focus:outline-hidden bg-white shadow-2xs transition-all pos-nums">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="w-9 h-9 absolute right-1 top-1 flex items-center justify-center text-gray-400 hover:text-gray-600">
                                <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Cash Float Drawer (Shift start) -->
                    <div class="p-3 bg-rose-50/60 rounded-2xl border border-rose-100 space-y-1">
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
            </div>

            <!-- TAB 2: RESTAURANT / SAAS REGISTRATION FORM -->
            <div x-show="activeTab === 'register'" x-cloak x-transition>
                <form action="{{ route('register') }}" method="POST" class="space-y-3 sm:space-y-3.5">
                    @csrf

                    <!-- Restaurant Name -->
                    <div>
                        <label class="text-xs font-bold text-gray-700 block mb-1">রেস্টুরেন্ট / ক্যাফে নাম <span class="text-rose-600">*</span></label>
                        <div class="relative">
                            <div class="w-8 h-8 absolute left-1 top-1 flex items-center justify-center text-gray-400">
                                <i data-lucide="store" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="restaurant_name" required placeholder="যেমনঃ সুলতানস ডাইন, চিলক্স"
                                   class="w-full pl-9 pr-3 py-2 rounded-xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 bg-white shadow-2xs">
                        </div>
                    </div>

                    <!-- Owner Name & 11-digit Phone -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-700 block mb-1">মালিকের নাম <span class="text-rose-600">*</span></label>
                            <input type="text" name="owner_name" required placeholder="আপনার পূর্ণ নাম"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 bg-white shadow-2xs">
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs font-bold text-gray-700">মোবাইল নম্বর (১১ ডিজিট) <span class="text-rose-600">*</span></label>
                                <span x-show="isRegPhone11" class="text-[9px] font-bold text-emerald-700 bg-emerald-100 px-1 rounded">✓ ১১ ডিজিট</span>
                            </div>
                            <input type="tel" name="phone" x-model="regPhone" required placeholder="01XXXXXXXXX"
                                   maxlength="11"
                                   pattern="01[3-9][0-9]{8}"
                                   class="w-full px-3 py-2 rounded-xl border text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 bg-white shadow-2xs pos-nums"
                                   :style="isRegPhone11 ? 'border-color:#10B981;' : 'border-color:#D1D5DB;'">
                        </div>
                    </div>

                    <!-- Email & Password -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-700 block mb-1">ইমেইল অ্যাড্রেস <span class="text-rose-600">*</span></label>
                            <input type="email" name="email" required placeholder="owner@restaurant.com"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 bg-white shadow-2xs">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-700 block mb-1">পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) <span class="text-rose-600">*</span></label>
                            <input type="password" name="password" minlength="6" required placeholder="••••••••"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 bg-white shadow-2xs pos-nums">
                        </div>
                    </div>

                    <!-- Package Plan Picker -->
                    <div>
                        <label class="text-xs font-bold text-gray-700 block mb-1.5">প্যাকেজ প্ল্যান নির্বাচন করুন</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="p-2.5 rounded-xl border cursor-pointer transition-all text-center block"
                                   :style="selectedPlan === 'starter' ? 'border-color:#8B1A2C; background:rgba(139,26,44,0.06);' : 'border-color:#E5E7EB; background:#fff;'">
                                <input type="radio" name="package_plan" value="starter" x-model="selectedPlan" class="hidden">
                                <span class="block text-xs font-black text-gray-900">Starter</span>
                                <span class="block text-[10px] text-rose-800 font-bold pos-nums">৳১,২০০/মাস</span>
                                <span class="block text-[9px] text-gray-400">১টি শাখা</span>
                            </label>
                            <label class="p-2.5 rounded-xl border cursor-pointer transition-all text-center block relative"
                                   :style="selectedPlan === 'growth' ? 'border-color:#8B1A2C; background:rgba(139,26,44,0.06);' : 'border-color:#E5E7EB; background:#fff;'">
                                <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-amber-500 text-white text-[8px] font-black px-1.5 rounded-full">জনপ্রিয়</span>
                                <input type="radio" name="package_plan" value="growth" x-model="selectedPlan" class="hidden">
                                <span class="block text-xs font-black text-gray-900">Growth</span>
                                <span class="block text-[10px] text-rose-800 font-bold pos-nums">৳২,৫০০/মাস</span>
                                <span class="block text-[9px] text-gray-400">৩টি শাখা</span>
                            </label>
                            <label class="p-2.5 rounded-xl border cursor-pointer transition-all text-center block"
                                   :style="selectedPlan === 'enterprise' ? 'border-color:#8B1A2C; background:rgba(139,26,44,0.06);' : 'border-color:#E5E7EB; background:#fff;'">
                                <input type="radio" name="package_plan" value="enterprise" x-model="selectedPlan" class="hidden">
                                <span class="block text-xs font-black text-gray-900">Enterprise</span>
                                <span class="block text-[10px] text-rose-800 font-bold pos-nums">৳৫,০০০/মাস</span>
                                <span class="block text-[9px] text-gray-400">আনলিমিটেড</span>
                            </label>
                        </div>
                    </div>

                    <!-- Register Submit Button -->
                    <button type="submit"
                            class="w-full py-3 sm:py-3.5 rounded-2xl text-xs sm:text-sm font-black text-white flex items-center justify-center gap-2 shadow-lg transition-all active:scale-[0.98] cursor-pointer mt-2"
                            style="background: linear-gradient(135deg, #5C0F1B 0%, #8B1A2C 100%); box-shadow: 0 6px 20px rgba(139,26,44,0.35);">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        <span>রেজিস্ট্রেশন করুন ও ১৪ দিনের ফ্রি ট্রায়াল নিন</span>
                    </button>
                </form>
            </div>

            <!-- Footer Links -->
            <div class="pt-2 text-center">
                <p class="text-[11px] text-gray-500">
                    SmartPOS Enterprise • ক্লাউড রেস্টুরেন্ট ম্যানেজমেন্ট সল্যুশন v2.0
                </p>
            </div>

        </div>

    </div>

    <script>
        function authApp() {
            return {
                activeTab: '{{ (isset($errors) && ($errors->has('restaurant_name') || $errors->has('owner_name') || $errors->has('phone') && old('restaurant_name'))) ? 'register' : 'login' }}',
                loginId: '',
                password: '',
                showPassword: false,
                regPhone: '',
                selectedPlan: 'growth',

                get isCleanPhone11() {
                    const clean = (this.loginId || '').replace(/[^0-9]/g, '');
                    return clean.length === 11 && clean.startsWith('01');
                },

                get isRegPhone11() {
                    const clean = (this.regPhone || '').replace(/[^0-9]/g, '');
                    return clean.length === 11 && clean.startsWith('01');
                },

                init() {
                    this.$nextTick(() => window.initLucideIcons());
                }
            };
        }
    </script>
</body>
</html>
