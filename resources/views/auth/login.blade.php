<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>লগইন — রেস্টুরেন্ট POS & SaaS ক্লাউড</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Hind Siliguri', 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-full flex items-center justify-center p-4 sm:p-6" style="background: linear-gradient(135deg, #2E050B 0%, #5C0F1B 50%, #1A0A0C 100%);">

    <div x-data="loginPage()" class="w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20 my-auto">

        <!-- Top Header -->
        <div class="p-6 text-center text-white relative overflow-hidden"
             style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
            <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>

            <div class="w-14 h-14 rounded-2xl mx-auto flex items-center justify-center mb-3 shadow-lg"
                 style="background: rgba(212,172,80,0.25); border: 2px solid rgba(212,172,80,0.6);">
                <i data-lucide="utensils-crossed" class="w-7 h-7 stroke-[2.5]" style="color:#D4AC50;"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-black">{{ $branch->restaurant_name ?? "Sultan's Dine" }}</h1>
            <p class="text-xs text-white/80 mt-0.5">ক্লাউড রেস্টুরেন্ট POS ও SaaS মাল্টি-টেন্যান্ট প্ল্যাটফর্ম</p>
        </div>

        @if(session('success'))
            <div class="p-3 bg-emerald-50 border-b border-emerald-200 text-emerald-800 text-xs font-bold text-center">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-3 bg-rose-50 border-b border-rose-200 text-rose-800 text-xs font-bold text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Login Tabs: Quick Role Switch / Standard Login -->
        <div class="p-6 space-y-5" style="background:#FAF7F5;">

            <!-- Quick 1-Click Role Login for Touch POS -->
            <div>
                <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider block mb-2 text-center">
                    দ্রুত ভূমিকা নির্বাচন (১-ক্লিকে লগইন)
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @php
                        $roles = [
                            ['role' => 'admin', 'icon' => 'shield-check', 'name' => 'ম্যানেজার', 'email' => 'kamrul@sultansdine.com', 'pin' => '1234', 'color' => '#8B1A2C'],
                            ['role' => 'cashier', 'icon' => 'shopping-cart', 'name' => 'ক্যাশিয়ার', 'email' => 'cashier@sultansdine.com', 'pin' => '1234', 'color' => '#2E7D52'],
                            ['role' => 'kitchen', 'icon' => 'flame', 'name' => 'কিচেন শেফ', 'email' => 'chef@sultansdine.com', 'pin' => '1234', 'color' => '#D97706'],
                            ['role' => 'waiter', 'icon' => 'user-check', 'name' => 'ওয়েটার', 'email' => 'waiter@sultansdine.com', 'pin' => '1234', 'color' => '#7C3AED'],
                        ];
                    @endphp

                    @foreach($roles as $r)
                        <button type="button"
                                @click="fillQuick('{{ $r['email'] }}', '{{ $r['pin'] }}')"
                                class="p-2.5 rounded-2xl bg-white border border-gray-200 flex flex-col items-center justify-center gap-1 hover:shadow-md transition-all group text-center"
                                onmouseover="this.style.borderColor='{{ $r['color'] }}'"
                                onmouseout="this.style.borderColor='#E5E7EB'">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110"
                                 style="background: {{ $r['color'] }}15; color: {{ $r['color'] }};">
                                <i data-lucide="{{ $r['icon'] }}" class="w-4 h-4"></i>
                            </div>
                            <span class="text-xs font-bold text-gray-800">{{ $r['name'] }}</span>
                            <span class="text-[9px] text-gray-400 font-mono">PIN: {{ $r['pin'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-[10px] uppercase font-bold text-gray-400">অথবা পাসওয়ার্ড / পিন দিয়ে লগইন</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <!-- Standard Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">ইমেইল বা ইউজারনেম</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="email" x-model="email" required
                               placeholder="user@restaurant.com"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 text-xs font-medium focus:ring-2 focus:ring-rose-800 focus:outline-hidden bg-white">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">পাসওয়ার্ড বা ৪-সংখ্যার পিন</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="password" x-model="password" required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 text-xs font-medium focus:ring-2 focus:ring-rose-800 focus:outline-hidden bg-white">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">প্রারম্ভিক ক্যাশ ড্রয়ার ফ্লোট (টাকা)</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">৳</span>
                        <input type="number" name="opening_float" value="2000" min="0"
                               class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-800 focus:ring-2 focus:ring-rose-800 focus:outline-hidden bg-white">
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">ক্যাশিয়ার শিফট শুরুর জন্য ড্রয়ারের ক্যাশ ফ্লোট</p>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-2xl text-xs font-black text-white flex items-center justify-center gap-2 shadow-lg transition-all"
                        style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>সফটওয়্যারে প্রবেশ করুন</span>
                </button>
            </form>

            <!-- SaaS Registration & Pricing Footer Link -->
            <div class="pt-4 border-t border-gray-200 text-center space-y-2">
                <p class="text-xs text-gray-600">
                    নতুন রেস্টুরেন্ট খুলতে চান? 
                    <a href="{{ route('saas.register') }}" class="font-bold text-rose-800 hover:underline">
                        ১৪ দিনের ফ্রি ট্রায়ালে রেজিস্ট্রেশন করুন
                    </a>
                </p>
                <div class="flex items-center justify-center gap-4 text-[11px] text-gray-500">
                    <a href="{{ route('saas.plans') }}" class="hover:text-rose-800">প্যাকেজ ও মূল্য তালিকা</a>
                    <span>•</span>
                    <a href="{{ route('saas.dashboard') }}" class="hover:text-rose-800 font-bold">SaaS সুপার-অ্যাডমিন</a>
                </div>
            </div>

        </div>

    </div>

    <script>
        function loginPage() {
            return {
                email: 'kamrul@sultansdine.com',
                password: 'password',
                fillQuick(e, p) {
                    this.email = e;
                    this.password = p;
                }
            };
        }
    </script>
</body>
</html>
