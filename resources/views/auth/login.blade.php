<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Staff Login — Lezzatos Luxury Dining & POS</title>
    
    <!-- Google Fonts for Luxury Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.cdnfonts.com/css/google-sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@600;700;800;900&family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600;1,700&family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            light: '#E5C07B',
                            DEFAULT: '#C5A880',
                            bright: '#D4AF37',
                            sand: '#D1A568',
                            dark: '#8C6D37',
                        },
                        dark: {
                            base: '#0B0B0B',
                            card: '#141414',
                            cardAlt: '#181818',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'Cinzel', '"Times New Roman"', 'serif'],
                        script: ['"Great Vibes"', '"Alex Brush"', 'cursive'],
                        sans: ['"Google Sans"', '"Product Sans"', 'Inter', 'sans-serif'],
                        classic: ['"Times New Roman"', 'Times', '"Cormorant Garamond"', '"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js & Lucide Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body {
            background-color: #0B0B0B;
            color: #C2B5A8;
            font-family: 'Google Sans', 'Product Sans', 'Inter', sans-serif;
            min-height: 100vh;
        }

        body, p, a, button, input, select, textarea, label, span {
            font-family: 'Google Sans', 'Product Sans', 'Inter', sans-serif;
        }

        .font-script {
            font-family: 'Great Vibes', 'Alex Brush', cursive;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .font-classic {
            font-family: "Times New Roman", Times, "Cormorant Garamond", "Playfair Display", serif;
        }

        .chamfer-top-right {
            clip-path: polygon(0 0, calc(100% - 24px) 0, 100% 24px, 100% 100%, 0 100%);
        }

        .gold-diagonal-lines {
            background-image: repeating-linear-gradient(45deg, rgba(197,168,128,0.15) 0, rgba(197,168,128,0.15) 1.5px, transparent 0, transparent 10px);
        }
    </style>
</head>
<body class="flex flex-col min-h-screen relative overflow-x-hidden selection:bg-[#C5A880]/30 selection:text-white"
      x-data="authApp()" x-init="init()">

    <!-- Top Navigation Bar with Back to Home Link -->
    <header class="w-full py-5 px-6 sm:px-12 flex items-center justify-between border-b border-[#C5A880]/15 relative z-20 bg-[#0B0B0B]/80 backdrop-blur-md">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="group flex items-center gap-2">
            <span class="font-script text-3xl sm:text-4xl text-[#C5A880] tracking-wide group-hover:brightness-125 transition-all">
                Lezzatos.
            </span>
        </a>

        <!-- Back to Website / Home Button -->
        <a href="{{ route('home') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-[#C5A880]/40 text-[#C5A880] hover:bg-[#C5A880] hover:text-black font-classic text-xs font-bold uppercase tracking-wider transition-all shadow-md">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Home</span>
        </a>
    </header>

    <!-- Main Container -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 relative z-10">
        
        <div class="w-full max-w-md my-auto space-y-6">
            
            <!-- Header Brand Emblem & Title -->
            <div class="text-center space-y-2">
                <a href="{{ route('home') }}" class="inline-block hover:scale-105 transition-transform">
                    <div class="w-16 h-16 rounded-2xl bg-[#141414] border border-[#C5A880]/40 flex items-center justify-center shadow-xl mx-auto">
                        <i data-lucide="utensils-crossed" class="w-7 h-7 text-[#C5A880]"></i>
                    </div>
                </a>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-white tracking-tight">Staff & POS Login</h1>
                <p class="text-xs text-[#8C7D73]">Sign in to access POS Terminal, Kitchen Display & Management</p>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-3 bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 text-xs font-semibold text-center rounded-xl flex items-center justify-center gap-2 shadow">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3 bg-rose-950/80 border border-rose-500/40 text-rose-300 text-xs font-semibold text-center rounded-xl flex items-center justify-center gap-2 shadow">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="p-3 bg-rose-950/80 border border-rose-500/40 text-rose-300 text-xs font-semibold text-center rounded-xl flex items-center justify-center gap-2 shadow">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Tab Switcher -->
            <div class="flex items-center justify-center gap-2 bg-[#141414] p-1 rounded-full border border-[#C5A880]/20 max-w-xs mx-auto">
                <button type="button" @click="activeTab = 'login'"
                        class="flex-1 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wider transition-all font-classic"
                        :class="activeTab === 'login' ? 'bg-[#D1A568] text-black shadow' : 'text-gray-400 hover:text-white'">
                    Sign In
                </button>
                <button type="button" @click="activeTab = 'register'"
                        class="flex-1 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wider transition-all font-classic"
                        :class="activeTab === 'register' ? 'bg-[#D1A568] text-black shadow' : 'text-gray-400 hover:text-white'">
                    New Restaurant
                </button>
            </div>

            <!-- TAB 1: LOGIN FORM -->
            <div x-show="activeTab === 'login'" x-transition 
                 class="bg-[#141414] p-6 sm:p-8 rounded-3xl border border-[#C5A880]/30 shadow-2xl space-y-4 relative">
                
                <div class="absolute top-0 right-0 w-16 h-16 gold-diagonal-lines opacity-20 pointer-events-none"></div>

                <form action="{{ route('login') }}" method="POST" class="space-y-4 relative z-10">
                    @csrf

                    <!-- Staff ID / Phone / Email -->
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-[#A8988D] mb-1">
                            Staff ID / Phone / Email
                        </label>
                        <div class="relative">
                            <input type="text" name="login_id" x-model="loginId" required
                                   placeholder="e.g. 01700000000 or superadmin@pos.com"
                                   class="w-full px-4 py-3 rounded-xl bg-[#1C1C1C] border border-[#C5A880]/30 text-xs font-semibold text-white placeholder-gray-500 focus:outline-none focus:border-[#C5A880]">
                        </div>
                    </div>

                    <!-- Password / PIN -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#A8988D]">
                                Password or 4-Digit PIN
                            </label>
                            <button type="button" @click="showPassword = !showPassword"
                                    class="text-[10px] font-bold text-[#C5A880] hover:underline">
                                <span x-text="showPassword ? 'Hide' : 'Show'"></span>
                            </button>
                        </div>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" x-model="password" required
                                   placeholder="Password or PIN"
                                   class="w-full px-4 py-3 pr-10 rounded-xl bg-[#1C1C1C] border border-[#C5A880]/30 text-xs font-semibold text-white placeholder-gray-500 focus:outline-none focus:border-[#C5A880]">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[#C5A880] p-1">
                                <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Opening Cash Drawer Float -->
                    <div class="p-3 bg-[#1C1C1C] rounded-xl border border-[#C5A880]/20 space-y-1">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] font-bold text-gray-300">Opening Cash Drawer Float</label>
                            <span class="text-[9px] font-bold text-[#C5A880] uppercase tracking-wider">Shift Start</span>
                        </div>
                        <div class="relative">
                            <span class="w-6 h-6 absolute left-2 top-2 flex items-center justify-center text-xs font-bold text-[#C5A880]">$</span>
                            <input type="number" name="opening_float" value="50" min="0"
                                   class="w-full pl-8 pr-3 py-1.5 rounded-lg bg-[#141414] border border-[#C5A880]/30 text-xs font-bold text-white focus:outline-none focus:border-[#C5A880]">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full py-3.5 rounded-xl bg-[#D1A568] hover:bg-[#C5A880] text-black font-bold text-xs uppercase tracking-widest transition-all shadow-xl active:scale-98 cursor-pointer">
                        SIGN IN TO POS
                    </button>
                </form>

                <!-- Demo Credentials Helper -->
                <div class="pt-3 border-t border-[#C5A880]/15 space-y-2">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 text-center">Demo Quick Fill</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="loginId='01700000000'; password='password';"
                                class="py-1 px-2 rounded bg-[#1C1C1C] border border-[#C5A880]/20 text-[10px] text-[#C5A880] font-bold hover:bg-[#C5A880] hover:text-black transition-all">
                            Admin Login
                        </button>
                        <button type="button" @click="loginId='01800000000'; password='password';"
                                class="py-1 px-2 rounded bg-[#1C1C1C] border border-[#C5A880]/20 text-[10px] text-[#C5A880] font-bold hover:bg-[#C5A880] hover:text-black transition-all">
                            Cashier Login
                        </button>
                    </div>
                </div>

            </div>

            <!-- TAB 2: REGISTER FORM -->
            <div x-show="activeTab === 'register'" x-cloak x-transition 
                 class="bg-[#141414] p-6 sm:p-8 rounded-3xl border border-[#C5A880]/30 shadow-2xl space-y-4">
                
                <form action="{{ route('register') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#A8988D] block mb-1">Restaurant Name *</label>
                        <input type="text" name="restaurant_name" required placeholder="e.g. Sultan's Dine"
                               class="w-full px-3.5 py-2 rounded-xl bg-[#1C1C1C] border border-[#C5A880]/30 text-xs font-semibold text-white focus:outline-none focus:border-[#C5A880]">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#A8988D] block mb-1">Owner Name *</label>
                            <input type="text" name="owner_name" required placeholder="Full Name"
                                   class="w-full px-3.5 py-2 rounded-xl bg-[#1C1C1C] border border-[#C5A880]/30 text-xs font-semibold text-white focus:outline-none focus:border-[#C5A880]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#A8988D] block mb-1">Phone Number *</label>
                            <input type="tel" name="phone" required placeholder="01XXXXXXXXX" maxlength="11"
                                   class="w-full px-3.5 py-2 rounded-xl bg-[#1C1C1C] border border-[#C5A880]/30 text-xs font-semibold text-white focus:outline-none focus:border-[#C5A880]">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#A8988D] block mb-1">Email Address *</label>
                            <input type="email" name="email" required placeholder="owner@restaurant.com"
                                   class="w-full px-3.5 py-2 rounded-xl bg-[#1C1C1C] border border-[#C5A880]/30 text-xs font-semibold text-white focus:outline-none focus:border-[#C5A880]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-[#A8988D] block mb-1">Password *</label>
                            <input type="password" name="password" minlength="6" required placeholder="••••••••"
                                   class="w-full px-3.5 py-2 rounded-xl bg-[#1C1C1C] border border-[#C5A880]/30 text-xs font-semibold text-white focus:outline-none focus:border-[#C5A880]">
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-[#A8988D] block mb-1">Select Plan</label>
                        <select name="package_plan" class="w-full px-3.5 py-2 rounded-xl bg-[#1C1C1C] border border-[#C5A880]/30 text-xs font-semibold text-white focus:outline-none focus:border-[#C5A880]">
                            <option value="starter">Starter Plan ($29/mo)</option>
                            <option value="growth" selected>Growth Plan ($59/mo)</option>
                            <option value="enterprise">Enterprise Plan ($99/mo)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-3.5 rounded-xl bg-[#D1A568] hover:bg-[#C5A880] text-black font-bold text-xs uppercase tracking-widest transition-all shadow-xl active:scale-98 cursor-pointer mt-2">
                        CREATE ACCOUNT & START 14-DAY TRIAL
                    </button>
                </form>
            </div>

            <!-- Footer Return Link -->
            <div class="text-center pt-2">
                <a href="{{ route('home') }}" class="text-xs font-bold text-[#C5A880] hover:underline inline-flex items-center gap-1.5">
                    <i data-lucide="home" class="w-3.5 h-3.5"></i>
                    <span>Return to Website Homepage</span>
                </a>
            </div>

        </div>

    </main>

    <script>
        function authApp() {
            return {
                activeTab: '{{ (isset($errors) && ($errors->has('restaurant_name') || $errors->has('owner_name') || $errors->has('phone') && old('restaurant_name'))) ? 'register' : 'login' }}',
                loginId: '',
                password: '',
                showPassword: false,

                init() {
                    this.$nextTick(() => {
                        if (window.lucide) window.lucide.createIcons();
                    });
                }
            };
        }
    </script>
</body>
</html>
