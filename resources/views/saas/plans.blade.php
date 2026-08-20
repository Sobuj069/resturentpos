@extends('layouts.app')
@section('title', 'SaaS সাবস্ক্রিপশন প্ল্যান ও মূল্য তালিকা')
@section('content')
<div class="min-h-full p-5 lg:p-8 space-y-6 pb-24" style="background:#F5F0EC;">

    <div class="text-center max-w-2xl mx-auto space-y-2">
        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider" style="background:rgba(184,146,42,0.15); color:#B8922A; border:1px solid rgba(184,146,42,0.3);">
            রেস্টুরেন্ট ক্লাউড সাবস্ক্রিপশন
        </span>
        <h1 class="text-2xl sm:text-3xl font-black" style="color:#1A0A0C;">আপনার ব্যবসার সাইজ অনুযায়ী সেরা প্ল্যানটি বেছে নিন</h1>
        <p class="text-xs sm:text-sm text-gray-500">সিঙ্গেল ক্যাফে থেকে শুরু করে মাল্টি-ব্রাঞ্চ চেইন রেস্টুরেন্টের সম্পূর্ণ অটোমেশন</p>
    </div>

    <!-- Pricing Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
        <!-- Starter Plan -->
        <div class="pos-card p-6 flex flex-col justify-between rounded-3xl bg-white border border-gray-200 hover:shadow-lg transition-all">
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Starter Plan</h3>
                    <p class="text-xs text-gray-500">ছোট ক্যাফে বা ফাস্ট ফুড আউটলেটের জন্য</p>
                </div>
                <div>
                    <span class="text-3xl font-black pos-nums price-maroon">৳১,২০০</span>
                    <span class="text-xs text-gray-500 font-bold">/ মাস</span>
                </div>
                <ul class="space-y-2 text-xs text-gray-700">
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> ১টি ব্রাঞ্চ / আউটলেট</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> আনলিমিটেড POS বিলিং ও চালান</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> কিচেন ডিসপ্লে সিস্টেম (KDS)</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> ৫ জন স্টাফ রোল</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> টেবিল QR সেলফ-অর্ডারিং</li>
                </ul>
            </div>
            <a href="{{ route('saas.register') }}" class="btn-outline w-full py-2.5 rounded-xl text-xs font-bold text-center mt-6">
                শুরু করুন
            </a>
        </div>

        <!-- Growth Plan (Featured) -->
        <div class="pos-card p-6 flex flex-col justify-between rounded-3xl bg-white border-2 border-rose-800 shadow-xl relative scale-105">
            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full text-[10px] font-black uppercase text-white shadow-md"
                 style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                সবচেয়ে জনপ্রিয়
            </div>
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-black text-rose-950">Growth Plan</h3>
                    <p class="text-xs text-gray-500">ডাইন-ইন রেস্টুরেন্ট ও কিচেন কন্ট্রোল</p>
                </div>
                <div>
                    <span class="text-3xl font-black pos-nums price-maroon">৳২,৫০০</span>
                    <span class="text-xs text-gray-500 font-bold">/ মাস</span>
                </div>
                <ul class="space-y-2 text-xs text-gray-700">
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> ৩টি ব্রাঞ্চ / আউটলেট</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> ইনগ্রেডিয়েন্ট স্টক ও রেসিপি BOM</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> ফুডপান্ডা ও পাঠাও লাইভ ডেলিভারি হাব</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> কাস্টমার CRM ও লয়্যালটি রিওয়ার্ডস</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> দৈনিক খরচ ও রিয়েল-টাইম P&L লাভ-ক্ষতি</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> ১৫ জন স্টাফ অ্যাকাউন্ট</li>
                </ul>
            </div>
            <a href="{{ route('saas.register') }}" class="btn-maroon w-full py-2.5 rounded-xl text-xs font-bold text-center mt-6 shadow-md">
                ১৪ দিনের ফ্রি ট্রায়াল নিন
            </a>
        </div>

        <!-- Enterprise VIP Plan -->
        <div class="pos-card p-6 flex flex-col justify-between rounded-3xl bg-white border border-gray-200 hover:shadow-lg transition-all">
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-black text-gray-900">Enterprise VIP</h3>
                    <p class="text-xs text-gray-500">লার্জ মাল্টি-ব্রাঞ্চ রেস্টুরেন্ট চেইনের জন্য</p>
                </div>
                <div>
                    <span class="text-3xl font-black pos-nums price-maroon">৳৫,০০০</span>
                    <span class="text-xs text-gray-500 font-bold">/ মাস</span>
                </div>
                <ul class="space-y-2 text-xs text-gray-700">
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> আনলিমিটেড ব্রাঞ্চ ও সেন্ট্রাল ওয়্যারহাউজ</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> ইন্টার-ব্রাঞ্চ স্টক রিকুইজিশন ও ট্রান্সফার</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> NBR মূসক ৬.৩ কমপ্লায়েন্স ও QR চালান</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> Gemini AI ভয়েস অর্ডার ও সেলস ফোরকাস্ট</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-600"></i> আনলিমিটেড স্টাফ ও ২৪/৭ ডেডিকেটেড সাপোর্ট</li>
                </ul>
            </div>
            <a href="{{ route('saas.register') }}" class="btn-outline w-full py-2.5 rounded-xl text-xs font-bold text-center mt-6">
                যোগাযোগ করুন
            </a>
        </div>
    </div>

</div>
@endsection
