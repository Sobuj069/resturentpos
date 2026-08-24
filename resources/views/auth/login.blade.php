<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Staff Login — {{ $branch->restaurant_name ?? 'SmartPOS' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=JetBrains+Mono:wght@600;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', 'Hind Siliguri', sans-serif; }
        .font-serif-brand { font-family: 'Playfair Display', serif; }
        .pos-nums { font-family: 'JetBrains Mono', monospace; }
        .gold-border { border-color: #D4AF37; }
        .gold-text { color: #C59B27; }
        .maroon-bg { background-color: #801424; }
        .maroon-btn {
            background: linear-gradient(135deg, #70101E 0%, #8A1729 100%);
            box-shadow: 0 4px 15px rgba(128, 20, 36, 0.35);
        }
        .maroon-btn:hover {
            background: linear-gradient(135deg, #5C0C18 0%, #761222 100%);
        }
    </style>
</head>
<body class="min-h-full flex items-center justify-center p-4 sm:p-6 select-none relative overflow-x-hidden"
      style="background-color: #FCFAFA;">

    <!-- Top Left Golden Mandala Corner Flourish -->
    <svg class="absolute top-0 left-0 w-36 h-36 sm:w-48 sm:h-48 text-[#D4AF37]/25 pointer-events-none" viewBox="0 0 100 100" fill="currentColor">
        <path d="M0,0 L0,100 C20,80 40,70 60,60 C70,40 80,20 100,0 Z" opacity="0.3"/>
        <path d="M0,0 C30,10 50,30 60,60 C30,50 10,30 0,0 Z" fill="none" stroke="currentColor" stroke-width="1"/>
        <circle cx="15" cy="15" r="8" fill="none" stroke="currentColor" stroke-width="1.5"/>
        <circle cx="35" cy="35" r="5" fill="none" stroke="currentColor" stroke-width="1"/>
        <path d="M0,40 C15,35 25,25 35,0" fill="none" stroke="currentColor" stroke-width="1.5"/>
        <path d="M0,70 C30,60 50,45 70,0" fill="none" stroke="currentColor" stroke-width="1"/>
    </svg>

    <!-- Top Right Golden Mandala Corner Flourish -->
    <svg class="absolute top-0 right-0 w-36 h-36 sm:w-48 sm:h-48 text-[#D4AF37]/25 pointer-events-none transform scale-x-[-1]" viewBox="0 0 100 100" fill="currentColor">
        <path d="M0,0 L0,100 C20,80 40,70 60,60 C70,40 80,20 100,0 Z" opacity="0.3"/>
        <path d="M0,0 C30,10 50,30 60,60 C30,50 10,30 0,0 Z" fill="none" stroke="currentColor" stroke-width="1"/>
        <circle cx="15" cy="15" r="8" fill="none" stroke="currentColor" stroke-width="1.5"/>
        <circle cx="35" cy="35" r="5" fill="none" stroke="currentColor" stroke-width="1"/>
        <path d="M0,40 C15,35 25,25 35,0" fill="none" stroke="currentColor" stroke-width="1.5"/>
        <path d="M0,70 C30,60 50,45 70,0" fill="none" stroke="currentColor" stroke-width="1"/>
    </svg>

    <!-- Bottom Left Golden Mandala Corner Flourish -->
    <svg class="absolute bottom-0 left-0 w-36 h-36 sm:w-48 sm:h-48 text-[#D4AF37]/25 pointer-events-none transform scale-y-[-1]" viewBox="0 0 100 100" fill="currentColor">
        <path d="M0,0 L0,100 C20,80 40,70 60,60 C70,40 80,20 100,0 Z" opacity="0.3"/>
        <path d="M0,0 C30,10 50,30 60,60 C30,50 10,30 0,0 Z" fill="none" stroke="currentColor" stroke-width="1"/>
        <circle cx="15" cy="15" r="8" fill="none" stroke="currentColor" stroke-width="1.5"/>
        <circle cx="35" cy="35" r="5" fill="none" stroke="currentColor" stroke-width="1"/>
    </svg>

    <!-- Bottom Right Golden Mandala Corner Flourish -->
    <svg class="absolute bottom-0 right-0 w-36 h-36 sm:w-48 sm:h-48 text-[#D4AF37]/25 pointer-events-none transform scale-x-[-1] scale-y-[-1]" viewBox="0 0 100 100" fill="currentColor">
        <path d="M0,0 L0,100 C20,80 40,70 60,60 C70,40 80,20 100,0 Z" opacity="0.3"/>
        <path d="M0,0 C30,10 50,30 60,60 C30,50 10,30 0,0 Z" fill="none" stroke="currentColor" stroke-width="1"/>
        <circle cx="15" cy="15" r="8" fill="none" stroke="currentColor" stroke-width="1.5"/>
        <circle cx="35" cy="35" r="5" fill="none" stroke="currentColor" stroke-width="1"/>
    </svg>

    <div x-data="authApp()" x-init="init()" class="w-full max-w-md my-auto relative z-10 space-y-6">

        <!-- Brand Emblem Crest -->
        <div class="text-center space-y-3">
            <div class="inline-flex items-center justify-center relative">
                <!-- Royal Gold & Maroon Emblem Logo -->
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-white border-2 border-[#D4AF37] flex items-center justify-center shadow-md p-2 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-radial from-[#FDF8EB] to-white opacity-80"></div>
                    <svg viewBox="0 0 100 100" class="w-14 h-14 relative z-10">
                        <path d="M50 10 C35 25, 25 40, 25 60 C25 75, 35 90, 50 90 C65 90, 75 75, 75 60 C75 40, 65 25, 50 10 Z" fill="#801424"/>
                        <path d="M50 20 C40 32, 32 45, 32 60 C32 70, 40 80, 50 80 C60 80, 68 70, 68 60 C68 45, 60 32, 50 20 Z" fill="#D4AF37"/>
                        <path d="M50 30 L50 70 M35 50 L65 50" stroke="#FFF" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="50" cy="50" r="6" fill="#801424"/>
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight font-serif-brand">Staff Login Screen</h1>
                <p class="text-xs font-semibold text-gray-500 mt-1">{{ $branch->restaurant_name ?? "SmartPOS Enterprise" }}</p>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold text-center rounded-2xl flex items-center justify-center gap-2 shadow-2xs">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold text-center rounded-2xl flex items-center justify-center gap-2 shadow-2xs">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold text-center rounded-2xl flex items-center justify-center gap-2 shadow-2xs">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Tab Switcher (Staff Login / New Tenant Signup) -->
        <div class="flex items-center justify-center gap-2">
            <button type="button" @click="activeTab = 'login'"
                    class="px-4 py-1.5 rounded-full text-xs font-bold transition-all border"
                    :class="activeTab === 'login' ? 'bg-[#801424] text-white border-[#801424]' : 'bg-white text-gray-600 border-gray-300 hover:border-[#D4AF37]'">
                Staff Sign In
            </button>
            <button type="button" @click="activeTab = 'register'"
                    class="px-4 py-1.5 rounded-full text-xs font-bold transition-all border"
                    :class="activeTab === 'register' ? 'bg-[#801424] text-white border-[#801424]' : 'bg-white text-gray-600 border-gray-300 hover:border-[#D4AF37]'">
                + New Restaurant Onboarding
            </button>
        </div>

        <!-- TAB 1: LOGIN FORM (Exact Stitch Design) -->
        <div x-show="activeTab === 'login'" x-transition class="space-y-4">
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Staff ID Field -->
                <div>
                    <div class="relative">
                        <input type="text" name="login_id" x-model="loginId" required
                               placeholder="Staff ID (Phone Number or Email)"
                               class="w-full px-4 py-3.5 rounded-2xl border-2 border-[#D4AF37] text-sm font-semibold text-gray-900 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#801424] transition-all shadow-xs pos-nums">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required
                               placeholder="Password"
                               class="w-full px-4 py-3.5 pr-12 rounded-2xl border-2 border-[#D4AF37] text-sm font-semibold text-gray-900 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#801424] transition-all shadow-xs pos-nums">
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#C59B27] hover:text-[#801424] transition-colors p-1">
                            <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-5 h-5 stroke-[2]"></i>
                        </button>
                    </div>
                    <div class="text-right mt-1.5">
                        <button type="button" @click="showPassword = !showPassword"
                                class="text-xs font-semibold text-[#C59B27] hover:underline">
                            <span x-text="showPassword ? 'Hide Password' : 'Show Password'"></span>
                        </button>
                    </div>
                </div>

                <!-- Shift Opening Float -->
                <div class="p-3 bg-white rounded-2xl border border-gray-200 shadow-2xs space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-gray-700">Opening Cash Drawer Float</label>
                        <span class="text-[10px] font-bold text-[#801424]">Shift Start</span>
                    </div>
                    <div class="relative">
                        <span class="w-8 h-8 absolute left-1 top-1 flex items-center justify-center text-xs font-bold text-[#801424]">৳</span>
                        <input type="number" name="opening_float" value="2000" min="0"
                               class="w-full pl-8 pr-3 py-1.5 rounded-xl border border-gray-300 text-xs font-bold pos-nums text-gray-900 focus:ring-2 focus:ring-[#801424]">
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit"
                        class="w-full py-4 rounded-full text-base font-bold text-white maroon-btn cursor-pointer transition-all active:scale-[0.98]">
                    Login
                </button>
            </form>
        </div>

        <!-- TAB 2: REGISTER FORM -->
        <div x-show="activeTab === 'register'" x-cloak x-transition class="bg-white p-5 rounded-3xl border border-[#D4AF37] shadow-xl space-y-3">
            <form action="{{ route('register') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">Restaurant Name *</label>
                    <input type="text" name="restaurant_name" required placeholder="e.g. Sultan's Dine"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-[#801424]">
                </div>
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="text-xs font-bold text-gray-700 block mb-1">Owner Name *</label>
                        <input type="text" name="owner_name" required placeholder="Full Name"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-[#801424]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-700 block mb-1">Mobile (11 Digits) *</label>
                        <input type="tel" name="phone" x-model="regPhone" required placeholder="01XXXXXXXXX" maxlength="11"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-[#801424] pos-nums">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="text-xs font-bold text-gray-700 block mb-1">Email *</label>
                        <input type="email" name="email" required placeholder="owner@pos.com"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-[#801424]">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-700 block mb-1">Password *</label>
                        <input type="password" name="password" minlength="6" required placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-[#801424] pos-nums">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">Select Plan</label>
                    <select name="package_plan" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-bold">
                        <option value="starter">Starter (৳1,200/mo)</option>
                        <option value="growth" selected>Growth (৳2,500/mo)</option>
                        <option value="enterprise">Enterprise (৳5,000/mo)</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-3.5 rounded-full text-sm font-bold text-white maroon-btn cursor-pointer mt-2">
                    Create Account & Start 14-Day Trial
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center pt-2 space-y-1">
            <a href="#" class="text-xs font-bold text-[#C59B27] hover:underline block">Forgot Password?</a>
            <p class="text-[11px] font-semibold text-gray-400">Version 2.0.0</p>
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

                init() {
                    this.$nextTick(() => window.initLucideIcons());
                }
            };
        }
    </script>
</body>
</html>
