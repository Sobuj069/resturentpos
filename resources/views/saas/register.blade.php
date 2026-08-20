<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>রেস্টুরেন্ট রেজিস্ট্রেশন — SaaS ক্লাউড POS</title>
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

    <div x-data="registerTerminal()" x-init="init()" class="w-full max-w-xl glass-card rounded-3xl sm:rounded-[32px] shadow-2xl overflow-hidden border border-white/20 my-4 sm:my-auto">

        <!-- Top Header -->
        <div class="px-5 py-6 sm:p-7 text-center text-white relative overflow-hidden"
             style="background: linear-gradient(135deg, #4A0813 0%, #7A1424 50%, #9B1C2E 100%);">
            <div class="absolute -right-10 -top-10 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>

            <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-2xl mx-auto flex items-center justify-center mb-3 shadow-lg"
                 style="background: rgba(212,172,80,0.2); border: 2px solid rgba(212,172,80,0.6); box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                <i data-lucide="sparkles" class="w-6 h-6 sm:w-7 sm:h-7" style="color:#D4AC50;"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white">রেস্টুরেন্ট ক্লাউড POS চালু করুন</h1>
            <p class="text-xs text-white/90 mt-1">১৪ দিনের সম্পূর্ণ ফ্রি ট্রায়াল · ক্রেডিট কার্ড ছাড়াই ইনস্ট্যান্ট সেটআপ</p>
        </div>

        @if($errors->any())
            <div class="p-3.5 bg-rose-50 border-b border-rose-200 text-rose-800 text-xs font-bold text-center flex items-center justify-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('saas.register.submit') }}" method="POST" class="p-4 sm:p-7 space-y-4" style="background:#FAF7F5;">
            @csrf

            <!-- Section: Restaurant & Subdomain -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">রেস্টুরেন্ট বা ব্র্যান্ড নাম *</label>
                    <div class="relative">
                        <div class="w-9 h-9 absolute left-1 top-1 flex items-center justify-center text-gray-400">
                            <i data-lucide="utensils" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="restaurant_name" x-model="restaurantName" required placeholder="উদাঃ Kacchi Bhai, Chillox"
                               @input="updateSubdomain()"
                               value="{{ old('restaurant_name') }}"
                               class="w-full pl-10 pr-3.5 py-2.5 sm:py-3 rounded-2xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 focus:border-rose-800 focus:outline-hidden bg-white shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">ক্লাউড সাবডোমেন (Subdomain)</label>
                    <div class="flex items-center">
                        <input type="text" name="subdomain" x-model="subdomain" placeholder="myrestaurant"
                               value="{{ old('subdomain') }}"
                               class="w-full px-3 py-2.5 sm:py-3 rounded-l-2xl border border-r-0 border-gray-300 text-xs font-mono font-bold lowercase text-gray-800 focus:ring-2 focus:ring-rose-800 focus:outline-hidden bg-white">
                        <span class="px-2.5 py-2.5 sm:py-3 bg-gray-100 border border-gray-300 rounded-r-2xl text-[11px] text-gray-500 font-mono font-bold whitespace-nowrap">.posbd.cloud</span>
                    </div>
                </div>
            </div>

            <!-- Section: Owner Name & Phone -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">মালিকের পূর্ণ নাম *</label>
                    <div class="relative">
                        <div class="w-9 h-9 absolute left-1 top-1 flex items-center justify-center text-gray-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <input type="text" name="owner_name" required placeholder="MD. Kamrul Hasan"
                               value="{{ old('owner_name') }}"
                               class="w-full pl-10 pr-3.5 py-2.5 sm:py-3 rounded-2xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 focus:border-rose-800 focus:outline-hidden bg-white shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">মোবাইল নম্বর (SMS নোটিফিকেশন) *</label>
                    <div class="relative">
                        <div class="w-9 h-9 absolute left-1 top-1 flex items-center justify-center text-gray-400">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <input type="tel" name="phone" required placeholder="017xxxxxxxx"
                               value="{{ old('phone') }}"
                               class="w-full pl-10 pr-3.5 py-2.5 sm:py-3 rounded-2xl border border-gray-300 text-xs font-bold text-gray-800 pos-nums focus:ring-2 focus:ring-rose-800 focus:border-rose-800 focus:outline-hidden bg-white shadow-2xs">
                    </div>
                </div>
            </div>

            <!-- Section: Email & Password -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">অফিসিয়াল ইমেইল *</label>
                    <div class="relative">
                        <div class="w-9 h-9 absolute left-1 top-1 flex items-center justify-center text-gray-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input type="email" name="email" required placeholder="owner@restaurant.com"
                               value="{{ old('email') }}"
                               class="w-full pl-10 pr-3.5 py-2.5 sm:py-3 rounded-2xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 focus:border-rose-800 focus:outline-hidden bg-white shadow-2xs">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">অ্যাডমিন পাসওয়ার্ড *</label>
                    <div class="relative">
                        <div class="w-9 h-9 absolute left-1 top-1 flex items-center justify-center text-gray-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" name="password" required minlength="6" placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-2.5 sm:py-3 rounded-2xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 focus:border-rose-800 focus:outline-hidden bg-white shadow-2xs">
                        <button type="button" @click="showPassword = !showPassword"
                                class="w-9 h-9 absolute right-1 top-1 flex items-center justify-center text-gray-400 hover:text-gray-600">
                            <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Subscription Plan Selector -->
            <div>
                <label class="text-xs font-bold text-gray-700 block mb-2">সাবস্ক্রিপশন প্ল্যান বেছে নিন</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    <!-- Starter Plan -->
                    <label @click="selectedPlan = 'starter'"
                           class="p-3 sm:p-3.5 rounded-2xl border cursor-pointer transition-all flex sm:flex-col items-center justify-between sm:justify-center text-left sm:text-center group"
                           :style="selectedPlan === 'starter' ? 'border-color:#8B1A2C; background:#FBF1F3; box-shadow:0 0 0 2px rgba(139,26,44,0.2);' : 'border-color:#E5E7EB; background:#FFF;'">
                        <div class="flex items-center sm:flex-col gap-2 sm:gap-0">
                            <input type="radio" name="package_plan" value="starter" x-model="selectedPlan" class="text-rose-800">
                            <div>
                                <p class="text-xs font-black text-gray-900 sm:mt-1">Starter</p>
                                <p class="text-[10px] text-gray-500">১টি শাখা · ৫ স্টাফ</p>
                            </div>
                        </div>
                        <span class="text-xs font-black price-maroon pos-nums">৳১,২০০<span class="text-[9px] text-gray-400 font-normal">/মাস</span></span>
                    </label>

                    <!-- Growth Plan -->
                    <label @click="selectedPlan = 'growth'"
                           class="p-3 sm:p-3.5 rounded-2xl border-2 cursor-pointer transition-all flex sm:flex-col items-center justify-between sm:justify-center text-left sm:text-center relative group"
                           :style="selectedPlan === 'growth' ? 'border-color:#8B1A2C; background:#FBF1F3; box-shadow:0 0 0 2px rgba(139,26,44,0.2);' : 'border-color:#D4AC50; background:#FFF;'">
                        <span class="hidden sm:block absolute -top-2.5 left-1/2 -translate-x-1/2 px-2 py-0.2 rounded-full text-[9px] font-black text-white bg-rose-800">জনপ্রিয়</span>
                        <div class="flex items-center sm:flex-col gap-2 sm:gap-0">
                            <input type="radio" name="package_plan" value="growth" x-model="selectedPlan" class="text-rose-800">
                            <div>
                                <p class="text-xs font-black text-rose-950 sm:mt-1">Growth (সেরা)</p>
                                <p class="text-[10px] text-gray-500">৩টি শাখা · রেসিপি BOM</p>
                            </div>
                        </div>
                        <span class="text-xs font-black price-maroon pos-nums">৳২,৫০০<span class="text-[9px] text-gray-400 font-normal">/মাস</span></span>
                    </label>

                    <!-- Enterprise VIP Plan -->
                    <label @click="selectedPlan = 'enterprise'"
                           class="p-3 sm:p-3.5 rounded-2xl border cursor-pointer transition-all flex sm:flex-col items-center justify-between sm:justify-center text-left sm:text-center group"
                           :style="selectedPlan === 'enterprise' ? 'border-color:#8B1A2C; background:#FBF1F3; box-shadow:0 0 0 2px rgba(139,26,44,0.2);' : 'border-color:#E5E7EB; background:#FFF;'">
                        <div class="flex items-center sm:flex-col gap-2 sm:gap-0">
                            <input type="radio" name="package_plan" value="enterprise" x-model="selectedPlan" class="text-rose-800">
                            <div>
                                <p class="text-xs font-black text-gray-900 sm:mt-1">Enterprise VIP</p>
                                <p class="text-[10px] text-gray-500">আনলিমিটেড শাখা · AI</p>
                            </div>
                        </div>
                        <span class="text-xs font-black price-maroon pos-nums">৳৫,০০০<span class="text-[9px] text-gray-400 font-normal">/মাস</span></span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    class="w-full py-3.5 sm:py-4 rounded-2xl text-xs sm:text-sm font-black text-white flex items-center justify-center gap-2 shadow-xl transition-all active:scale-[0.98] cursor-pointer mt-2"
                    style="background: linear-gradient(135deg, #5C0F1B 0%, #8B1A2C 100%); box-shadow: 0 6px 20px rgba(139,26,44,0.35);">
                <i data-lucide="check-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                <span>১৪ দিনের ফ্রি ট্রায়ালে অ্যাকাউন্ট তৈরি করুন</span>
            </button>

            <!-- Login Link -->
            <p class="text-center text-xs text-gray-600 pt-2">
                ইতিমধ্যে একাউন্ট আছে? 
                <a href="{{ route('login') }}" class="font-black text-rose-800 hover:underline inline-flex items-center gap-1">
                    <span>লগইন করুন</span>
                    <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </p>
        </form>

    </div>

    <script>
        function registerTerminal() {
            return {
                restaurantName: '',
                subdomain: '',
                selectedPlan: 'growth',
                showPassword: false,

                init() {
                    this.$nextTick(() => window.initLucideIcons());
                },
                updateSubdomain() {
                    this.subdomain = this.restaurantName.toLowerCase()
                        .replace(/[^a-z0-9]/g, '')
                        .substring(0, 20);
                }
            };
        }
    </script>
</body>
</html>
