<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>রেস্টুরেন্ট রেজিস্ট্রেশন — SaaS ক্লাউড POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;600;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Hind Siliguri', 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-full flex items-center justify-center p-4 sm:p-6" style="background: linear-gradient(135deg, #2E050B 0%, #5C0F1B 50%, #1A0A0C 100%);">

    <div class="w-full max-w-xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20 my-auto">

        <!-- Top Banner -->
        <div class="p-6 text-center text-white relative overflow-hidden"
             style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
            <div class="w-12 h-12 rounded-2xl mx-auto flex items-center justify-center mb-2.5 shadow-lg"
                 style="background: rgba(212,172,80,0.25); border: 2px solid rgba(212,172,80,0.6);">
                <i data-lucide="sparkles" class="w-6 h-6" style="color:#D4AC50;"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-black">আপনার রেস্টুরেন্টের জন্য ক্লাউড POS চালু করুন</h1>
            <p class="text-xs text-white/80 mt-0.5">১৪ দিনের ফ্রি ট্রায়াল · কোনো ক্রেডিট কার্ডের প্রয়োজন নেই</p>
        </div>

        @if($errors->any())
            <div class="p-3 bg-rose-50 border-b border-rose-200 text-rose-800 text-xs font-bold text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('saas.register.submit') }}" method="POST" class="p-6 space-y-4" style="background:#FAF7F5;">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">রেস্টুরেন্ট বা ব্র্যান্ড নাম *</label>
                    <input type="text" name="restaurant_name" required placeholder="উদাঃ Kacchi Bhai, Chillox"
                           value="{{ old('restaurant_name') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-rose-800 focus:outline-hidden bg-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">সাবডোমেন (Subdomain)</label>
                    <div class="flex items-center">
                        <input type="text" name="subdomain" placeholder="myrestaurant"
                               value="{{ old('subdomain') }}"
                               class="w-full px-3 py-2.5 rounded-l-xl border border-r-0 border-gray-300 text-xs font-mono lowercase focus:ring-2 focus:ring-rose-800 focus:outline-hidden bg-white">
                        <span class="px-2.5 py-2.5 bg-gray-100 border border-gray-300 rounded-r-xl text-[11px] text-gray-500 font-mono">.pos.bd</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">মালিকের পূর্ণ নাম *</label>
                    <input type="text" name="owner_name" required placeholder="MD. Owner Name"
                           value="{{ old('owner_name') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-rose-800 focus:outline-hidden bg-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">মোবাইল নম্বর *</label>
                    <input type="tel" name="phone" required placeholder="017xxxxxxxx"
                           value="{{ old('phone') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-rose-800 focus:outline-hidden bg-white">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">অফিসিয়াল ইমেইল *</label>
                    <input type="email" name="email" required placeholder="owner@restaurant.com"
                           value="{{ old('email') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-medium focus:ring-2 focus:ring-rose-800 focus:outline-hidden bg-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-700 block mb-1">অ্যাডমিন পাসওয়ার্ড *</label>
                    <input type="password" name="password" required minlength="6" placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-medium focus:ring-2 focus:ring-rose-800 focus:outline-hidden bg-white">
                </div>
            </div>

            <!-- Choose Subscription Plan -->
            <div>
                <label class="text-xs font-bold text-gray-700 block mb-2">সাবস্ক্রিপশন প্ল্যান নির্বাচন করুন</label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="p-3 rounded-2xl border bg-white cursor-pointer hover:border-rose-800 transition-all flex flex-col items-center text-center">
                        <input type="radio" name="package_plan" value="starter" class="mb-1 text-rose-800">
                        <span class="text-xs font-black text-gray-900">Starter</span>
                        <span class="text-xs font-bold price-maroon mt-0.5">৳১,২০০<span class="text-[10px] text-gray-500 font-normal">/মাস</span></span>
                        <span class="text-[9px] text-gray-500 mt-1">১টি শাখা · ৫ স্টাফ</span>
                    </label>
                    <label class="p-3 rounded-2xl border-2 border-rose-800 bg-rose-50/50 cursor-pointer shadow-xs flex flex-col items-center text-center">
                        <input type="radio" name="package_plan" value="growth" checked class="mb-1 text-rose-800">
                        <span class="text-xs font-black text-rose-900">Growth (জনপ্রিয়)</span>
                        <span class="text-xs font-bold price-maroon mt-0.5">৳২,৫০০<span class="text-[10px] text-gray-500 font-normal">/মাস</span></span>
                        <span class="text-[9px] text-gray-500 mt-1">৩টি শাখা · রেসিপি BOM</span>
                    </label>
                    <label class="p-3 rounded-2xl border bg-white cursor-pointer hover:border-rose-800 transition-all flex flex-col items-center text-center">
                        <input type="radio" name="package_plan" value="enterprise" class="mb-1 text-rose-800">
                        <span class="text-xs font-black text-gray-900">Enterprise VIP</span>
                        <span class="text-xs font-bold price-maroon mt-0.5">৳৫,০০০<span class="text-[10px] text-gray-500 font-normal">/মাস</span></span>
                        <span class="text-[9px] text-gray-500 mt-1">আনলিমিটেড শাখা · AI</span>
                    </label>
                </div>
            </div>

            <button type="submit"
                    class="w-full py-3.5 rounded-2xl text-xs font-black text-white flex items-center justify-center gap-2 shadow-xl transition-all"
                    style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                <span>১৪ দিনের ফ্রি ট্রায়ালে অ্যাকাউন্ট তৈরি করুন</span>
            </button>

            <p class="text-center text-xs text-gray-600 pt-2">
                ইতিমধ্যে অ্যাকাউন্ট আছে? 
                <a href="{{ route('login') }}" class="font-bold text-rose-800 hover:underline">লগইন করুন</a>
            </p>
        </form>

    </div>

</body>
</html>
