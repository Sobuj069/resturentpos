@extends('layouts.app')
@section('title', 'SaaS মাল্টি-টেন্যান্ট সুপার-অ্যাডমিন')
@section('content')
<div x-data="saasSuperAdmin()" class="min-h-full p-5 lg:p-6 space-y-6 pb-24" style="background:#F5F0EC;">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(184,146,42,0.15); border:1.5px solid rgba(184,146,42,0.35);">
                    <i data-lucide="shield-check" class="w-5 h-5" style="color:#B8922A;"></i>
                </div>
                <h1 class="text-lg font-black" style="color:#1A0A0C;">SaaS মাল্টি-টেন্যান্ট সুপার-অ্যাডমিন কমান্ড সেন্টার</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">সকল রেস্টুরেন্ট টেন্যান্ট, ইউজার অ্যাক্টিভিটি মনিটরিং, লাইভ অডিট ও ইমপারসোনেশন কন্ট্রোল</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('saas.plans') }}" class="btn-outline px-3.5 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="layers" class="w-4 h-4"></i>
                <span>প্যাকেজ ও ফিচারস</span>
            </a>
            <a href="{{ route('saas.register') }}" class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-md">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>+ নতুন রেস্টুরেন্ট অনবোর্ডিং</span>
            </a>
        </div>
    </div>

    <!-- SaaS High-Level KPI Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#8B1A2C;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">মোট টেন্যান্ট রেস্টুরেন্ট</p>
            <p class="text-2xl font-black pos-nums price-maroon">{{ $totalTenants }} <span class="text-xs font-normal text-gray-400">টি</span></p>
            <p class="text-[10px] text-emerald-700 font-bold mt-1">সক্রিয়: {{ $activeTenants }} · ট্রায়াল: {{ $trialTenants }}</p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#2E7D52;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">মাসিক রিকারিং রেভিনিউ (MRR)</p>
            <p class="text-2xl font-black pos-nums" style="color:#2E7D52;">৳{{ number_format($totalMrr, 2) }}</p>
            <p class="text-[10px] text-gray-500 font-bold mt-1">বার্ষিক রানরেট (ARR): ৳{{ number_format($totalMrr * 12, 2) }}</p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#B8922A;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">মোট শাখা / আউটলেট</p>
            <p class="text-2xl font-black pos-nums" style="color:#B8922A;">{{ $totalBranches }} <span class="text-xs font-normal text-gray-400">শাখা</span></p>
            <p class="text-[10px] text-gray-500 font-bold mt-1">গড় শাখা/টেন্যান্ট: {{ $totalTenants > 0 ? round($totalBranches / $totalTenants, 1) : 0 }}</p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#7C3AED;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">প্ল্যাটফর্ম মোট সেলস</p>
            <p class="text-2xl font-black pos-nums" style="color:#7C3AED;">৳{{ number_format($totalSystemSales, 0) }}</p>
            <p class="text-[10px] text-gray-500 font-bold mt-1">মোট অর্ডার: {{ number_format($totalOrdersCount) }}</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 border-b pb-2" style="border-color:#E0D4CF;">
        <button @click="currentTab = 'tenants'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                :style="currentTab === 'tenants' ? 'background:#8B1A2C; color:#fff; box-shadow:0 2px 8px rgba(139,26,44,0.3);' : 'background:#fff; color:#5C3840; border:1px solid #D8C4BF;'">
            <i data-lucide="store" class="w-4 h-4"></i>
            <span>রেজিস্টার্ড রেস্টুরেন্ট তালিকা ({{ $tenants->count() }})</span>
        </button>
        <button @click="currentTab = 'users'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                :style="currentTab === 'users' ? 'background:#8B1A2C; color:#fff; box-shadow:0 2px 8px rgba(139,26,44,0.3);' : 'background:#fff; color:#5C3840; border:1px solid #D8C4BF;'">
            <i data-lucide="users" class="w-4 h-4"></i>
            <span>সকল স্টাফ ও ইউজার অ্যাকাউন্ট ({{ $allUsers->count() }})</span>
        </button>
        <button @click="currentTab = 'activity'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                :style="currentTab === 'activity' ? 'background:#8B1A2C; color:#fff; box-shadow:0 2px 8px rgba(139,26,44,0.3);' : 'background:#fff; color:#5C3840; border:1px solid #D8C4BF;'">
            <i data-lucide="activity" class="w-4 h-4"></i>
            <span>সিস্টেম লাইভ অডিট ও অ্যাক্টিভিটি স্ট্রিম</span>
        </button>
    </div>

    <!-- TAB 1: TENANTS MANAGEMENT TABLE -->
    <div x-show="currentTab === 'tenants'" x-transition class="bg-white rounded-3xl border shadow-xs overflow-hidden" style="border-color:#E8DDD9;">
        <div class="p-4 border-b flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3" style="border-color:#E8DDD9; background:#FDFBF9;">
            <div>
                <h3 class="text-sm font-black" style="color:#1A0A0C;">রেজিস্টার্ড রেস্টুরেন্ট টেন্যান্ট তালিকা</h3>
                <p class="text-xs" style="color:#9B7A7E;">সুপার-অ্যাডমিন হিসেবে যেকোনো রেস্টুরেন্ট অ্যাকাউন্টে এক ক্লিকে প্রবেশ (Impersonate) করুন</p>
            </div>
            <input type="text" x-model="searchQuery" placeholder="রেস্টুরেন্ট বা ফোন নম্বর খুঁজুন..."
                   class="pos-input text-xs px-3 py-2 rounded-xl w-full sm:w-64">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="text-[10px] uppercase font-bold border-b" style="background:#F8F5F2; color:#9B7A7E; border-color:#E8DDD9;">
                    <tr>
                        <th class="p-3.5">রেস্টুরেন্ট নাম</th>
                        <th class="p-3.5">মালিক ও ১১-ডিজিট মোবাইল</th>
                        <th class="p-3.5">প্ল্যান</th>
                        <th class="p-3.5">মাসিক ফি</th>
                        <th class="p-3.5">শাখা</th>
                        <th class="p-3.5">স্ট্যাটাস</th>
                        <th class="p-3.5 text-right">ইমপারসোনেশন ও ম্যানেজমেন্ট</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:#F0E8E5;">
                    @forelse($tenants as $tenant)
                    @php $firstAdminUser = $tenant->users->first(); @endphp
                    <tr class="hover:bg-[#FAF6F4] transition-colors">
                        <td class="p-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-xs text-white shrink-0"
                                     style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                                    {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $tenant->name }}</p>
                                    <p class="text-[10px] font-mono text-gray-400">{{ $tenant->subdomain ?? $tenant->slug }}.smartpos.com</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-3.5">
                            <p class="font-bold text-gray-800">{{ $tenant->owner_name }}</p>
                            <p class="text-[10px] pos-nums font-bold text-rose-900">{{ $tenant->phone }}</p>
                            <p class="text-[10px] text-gray-400">{{ $tenant->email }}</p>
                        </td>
                        <td class="p-3.5">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase"
                                  style="background: {{ $tenant->package_plan === 'enterprise' ? '#FDF2F8; color:#BE185D; border:1px solid #FBCFE8;' : ($tenant->package_plan === 'growth' ? '#FEF3C7; color:#B45309; border:1px solid #FDE68A;' : '#F1F5F9; color:#475569; border:1px solid #CBD5E1;') }}">
                                {{ $tenant->package_plan }} VIP
                            </span>
                        </td>
                        <td class="p-3.5 font-bold pos-nums price-maroon">
                            ৳{{ number_format($tenant->monthly_fee, 2) }}
                        </td>
                        <td class="p-3.5 text-gray-700 font-bold pos-nums">
                            {{ $tenant->branches->count() }} / {{ $tenant->max_branches }} শাখা
                        </td>
                        <td class="p-3.5">
                            @if($tenant->subscription_status === 'active')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    সক্রিয় (Active)
                                </span>
                            @elseif($tenant->subscription_status === 'trial')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                    ট্রায়াল (Trial)
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300">
                                    স্থগিত (Suspended)
                                </span>
                            @endif
                        </td>
                        <td class="p-3.5 text-right space-x-1">
                            @if($firstAdminUser)
                                <form action="{{ route('saas.impersonate', $firstAdminUser->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            title="এই রেস্টুরেন্টের অ্যাডমিন প্যানেলে ঢুকুন"
                                            class="px-2.5 py-1.5 rounded-lg text-white font-bold text-[11px] inline-flex items-center gap-1 shadow-xs hover:opacity-90 transition-all"
                                            style="background:#8B1A2C;">
                                        <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                                        <span>এই অ্যাকাউন্টে ঢুকুন</span>
                                    </button>
                                </form>
                            @endif
                            <button @click="editTenant({{ $tenant }})"
                                    class="px-2.5 py-1.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-white hover:border-gray-400 font-bold transition-all text-[11px]">
                                ম্যানেজ
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-400 font-bold">
                            কোনো কাস্টম রেস্টুরেন্ট টেন্যান্ট পাওয়া যায়নি। "+ নতুন রেস্টুরেন্ট অনবোর্ডিং" এ ক্লিক করে যুক্ত করুন।
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 2: ALL USERS & DIRECT IMPERSONATION TABLE -->
    <div x-show="currentTab === 'users'" x-cloak x-transition class="bg-white rounded-3xl border shadow-xs overflow-hidden" style="border-color:#E8DDD9;">
        <div class="p-4 border-b flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3" style="border-color:#E8DDD9; background:#FDFBF9;">
            <div>
                <h3 class="text-sm font-black" style="color:#1A0A0C;">সকল স্টাফ ও ইউজার অ্যাকাউন্ট</h3>
                <p class="text-xs" style="color:#9B7A7E;">সুপার-অ্যাডমিন যেকোনো ক্যাশিয়ার, ওয়েটার, ম্যানেজার বা শেফ অ্যাকাউন্টে লগইন করে টেস্ট করতে পারবেন</p>
            </div>
            <input type="text" x-model="userSearchQuery" placeholder="নাম, ফোন বা রোল দিয়ে খুঁজুন..."
                   class="pos-input text-xs px-3 py-2 rounded-xl w-full sm:w-64">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="text-[10px] uppercase font-bold border-b" style="background:#F8F5F2; color:#9B7A7E; border-color:#E8DDD9;">
                    <tr>
                        <th class="p-3.5">ইউজার নাম</th>
                        <th class="p-3.5">১১-ডিজিট মোবাইল</th>
                        <th class="p-3.5">ইমেইল</th>
                        <th class="p-3.5">রোল (Role)</th>
                        <th class="p-3.5">রেস্টুরেন্ট / ব্রাঞ্চ</th>
                        <th class="p-3.5">স্ট্যাটাস</th>
                        <th class="p-3.5 text-right">ইমপারসোনেশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:#F0E8E5;">
                    @foreach($allUsers as $u)
                    <tr class="hover:bg-[#FAF6F4] transition-colors">
                        <td class="p-3.5 font-bold text-gray-900 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-xs text-white"
                                 style="background: {{ $u->role === 'superadmin' ? '#B8922A' : ($u->role === 'admin' ? '#8B1A2C' : ($u->role === 'cashier' ? '#2E7D52' : '#7C3AED')) }};">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <span>{{ $u->name }}</span>
                        </td>
                        <td class="p-3.5 font-bold pos-nums text-rose-900">
                            {{ $u->phone ?? '—' }}
                        </td>
                        <td class="p-3.5 text-gray-500 font-mono text-[11px]">
                            {{ $u->email }}
                        </td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase"
                                  style="background: {{ $u->role === 'superadmin' ? '#FEF3C7; color:#B45309;' : ($u->role === 'admin' ? '#FEE2E2; color:#991B1B;' : ($u->role === 'cashier' ? '#DCFCE7; color:#166534;' : '#EDE9FE; color:#5B21B6;')) }}">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="p-3.5 text-gray-700">
                            <p class="font-bold">{{ $u->tenant->name ?? ($u->branch->restaurant_name ?? 'System Core') }}</p>
                            <p class="text-[10px] text-gray-400">{{ $u->branch->name ?? 'Main Outlet' }}</p>
                        </td>
                        <td class="p-3.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $u->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $u->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-3.5 text-right">
                            @if(auth()->id() !== $u->id)
                                <form action="{{ route('saas.impersonate', $u->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="px-2.5 py-1 rounded-lg text-white font-bold text-[10px] inline-flex items-center gap-1 shadow-xs hover:opacity-90"
                                            style="background: #2E7D52;">
                                        <i data-lucide="user-check" class="w-3 h-3"></i>
                                        <span>এই ইউজার হিসেবে ঢুকুন</span>
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] font-bold text-gray-400 italic">বর্তমান অ্যাকাউন্ট</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: SYSTEM LIVE AUDIT & ACTIVITY MONITOR ("কে কি করছে দেখা") -->
    <div x-show="currentTab === 'activity'" x-cloak x-transition class="space-y-6">
        <!-- Live Orders Stream -->
        <div class="bg-white rounded-3xl border shadow-xs overflow-hidden" style="border-color:#E8DDD9;">
            <div class="p-4 border-b flex items-center justify-between" style="border-color:#E8DDD9; background:#FDFBF9;">
                <div>
                    <h3 class="text-sm font-black" style="color:#1A0A0C;">সিস্টেম রিয়েল-টাইম সেলস ও অর্ডার অডিট লগ</h3>
                    <p class="text-xs" style="color:#9B7A7E;">সকল রেস্টুরেন্ট এবং ক্যাশিয়ারদের সাম্প্রতিক ইনভয়েস, বিল ও কালেকশন মনিটরিং</p>
                </div>
                <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full animate-pulse">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>লাইভ স্ট্রীম সক্রিয়</span>
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-[10px] uppercase font-bold border-b" style="background:#F8F5F2; color:#9B7A7E; border-color:#E8DDD9;">
                        <tr>
                            <th class="p-3.5">অর্ডার / চালান নং</th>
                            <th class="p-3.5">রেস্টুরেন্ট ব্রাঞ্চ</th>
                            <th class="p-3.5">ক্যাশিয়ার / স্টাফ</th>
                            <th class="p-3.5">টেবিল / টোকেন</th>
                            <th class="p-3.5">কাস্টমার</th>
                            <th class="p-3.5">মোট বিল</th>
                            <th class="p-3.5">পেমেন্ট স্ট্যাটাস</th>
                            <th class="p-3.5 text-right">সময়</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:#F0E8E5;">
                        @forelse($recentOrders as $ord)
                        <tr class="hover:bg-[#FAF6F4] transition-colors">
                            <td class="p-3.5">
                                <p class="font-bold pos-nums text-gray-900">{{ $ord->order_number }}</p>
                                <p class="text-[10px] font-mono text-gray-400">{{ $ord->mushak_number ?? $ord->invoice_number }}</p>
                            </td>
                            <td class="p-3.5 font-bold text-gray-800">
                                {{ $ord->branch->restaurant_name ?? "Sultan's Dine" }}
                            </td>
                            <td class="p-3.5 text-gray-700 font-bold">
                                {{ $ord->user->name ?? 'Cashier' }}
                            </td>
                            <td class="p-3.5 font-bold pos-nums text-gray-800">
                                {{ $ord->table->name ?? 'Takeaway' }} (Token #{{ $ord->token_number }})
                            </td>
                            <td class="p-3.5">
                                <p class="font-bold text-gray-800">{{ $ord->customer_name ?? 'Guest' }}</p>
                                <p class="text-[10px] pos-nums text-gray-400">{{ $ord->customer_phone ?? '—' }}</p>
                            </td>
                            <td class="p-3.5 font-black pos-nums price-maroon">
                                ৳{{ number_format($ord->grand_total, 2) }}
                            </td>
                            <td class="p-3.5">
                                @if($ord->payment_status === 'paid')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Paid ({{ strtoupper($ord->payment_method) }})</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Cooking / Unpaid</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-right text-[10px] text-gray-500 pos-nums">
                                {{ $ord->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-gray-400 font-bold">কোনো সাম্প্রতিক অর্ডার রেকর্ড পাওয়া যায়নি।</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Shift Starts -->
        <div class="bg-white rounded-3xl border shadow-xs overflow-hidden" style="border-color:#E8DDD9;">
            <div class="p-4 border-b" style="border-color:#E8DDD9; background:#FDFBF9;">
                <h3 class="text-sm font-black" style="color:#1A0A0C;">ক্যাশিয়ার শিফট ওপেন/ক্লোজ ইতিহাস</h3>
                <p class="text-xs" style="color:#9B7A7E;">কে কখন শিফট চালু করেছে ও ক্যাশ ড্রয়ারে কত টাকা দিয়ে শুরু করেছে</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="text-[10px] uppercase font-bold border-b" style="background:#F8F5F2; color:#9B7A7E; border-color:#E8DDD9;">
                        <tr>
                            <th class="p-3.5">ক্যাশিয়ার নাম</th>
                            <th class="p-3.5">রেস্টুরেন্ট শাখা</th>
                            <th class="p-3.5">ওপেনিং ফ্লট</th>
                            <th class="p-3.5">মোট সেলস</th>
                            <th class="p-3.5">প্রত্যাশিত ক্যাশ</th>
                            <th class="p-3.5">শিফট স্ট্যাটাস</th>
                            <th class="p-3.5 text-right">চালুর সময়</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="divide-color:#F0E8E5;">
                        @foreach($recentShifts as $sh)
                        <tr class="hover:bg-[#FAF6F4]">
                            <td class="p-3.5 font-bold text-gray-900">{{ $sh->user->name ?? 'Cashier' }}</td>
                            <td class="p-3.5 text-gray-700 font-bold">{{ $sh->branch->restaurant_name ?? 'Main Outlet' }}</td>
                            <td class="p-3.5 font-bold pos-nums text-rose-900">৳{{ number_format($sh->opening_float, 2) }}</td>
                            <td class="p-3.5 font-bold pos-nums text-emerald-800">৳{{ number_format($sh->total_sales, 2) }}</td>
                            <td class="p-3.5 font-bold pos-nums">৳{{ number_format($sh->expected_cash, 2) }}</td>
                            <td class="p-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $sh->status === 'open' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $sh->status === 'open' ? 'Open (চালু)' : 'Closed (বন্ধ)' }}
                                </span>
                            </td>
                            <td class="p-3.5 text-right text-[10px] text-gray-500 pos-nums">{{ $sh->opened_at ? $sh->opened_at->diffForHumans() : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Tenant Modal -->
    <div x-show="openEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openEditModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white">টেন্যান্ট লাইসেন্স ও সাবস্ক্রিপশন নিয়ন্ত্রণ</h3>
                <button @click="openEditModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 space-y-3">
                <div>
                    <label class="section-heading">রেস্টুরেন্ট নাম</label>
                    <p class="text-xs font-bold text-gray-900" x-text="activeTenant?.name"></p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">প্যাকেজ প্ল্যান</label>
                        <select x-model="tenantForm.package_plan" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                            <option value="starter">Starter (৳১,২০০)</option>
                            <option value="growth">Growth (৳২,৫০০)</option>
                            <option value="enterprise">Enterprise (৳৫,০০০)</option>
                        </select>
                    </div>
                    <div>
                        <label class="section-heading">সাবস্ক্রিপশন স্ট্যাটাস</label>
                        <select x-model="tenantForm.subscription_status" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                            <option value="active">Active (সক্রিয়)</option>
                            <option value="trial">Trial (ট্রায়াল)</option>
                            <option value="suspended">Suspended (স্থগিত)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">সর্বোচ্চ শাখা (Max Branches)</label>
                        <input type="number" min="1" max="100" x-model.number="tenantForm.max_branches" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold rounded-xl">
                    </div>
                    <div>
                        <label class="section-heading">সর্বোচ্চ স্টাফ (Max Staff)</label>
                        <input type="number" min="1" max="500" x-model.number="tenantForm.max_staff" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold rounded-xl">
                    </div>
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openEditModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveTenantChanges()" class="btn-maroon px-6 py-2 text-xs font-bold">সংরক্ষণ করুন</button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function saasSuperAdmin() {
    return {
        currentTab: 'tenants',
        searchQuery: '',
        userSearchQuery: '',
        openEditModal: false,
        activeTenant: null,
        tenantForm: { package_plan: 'growth', subscription_status: 'active', max_branches: 3, max_staff: 15 },

        editTenant(t) {
            this.activeTenant = t;
            this.tenantForm = {
                package_plan: t.package_plan,
                subscription_status: t.subscription_status,
                max_branches: t.max_branches,
                max_staff: t.max_staff,
            };
            this.openEditModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        async saveTenantChanges() {
            if (!this.activeTenant) return;
            try {
                const res = await fetch(`/saas/tenant/${this.activeTenant.id}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.tenantForm)
                });
                const data = await res.json();
                if (data.success) {
                    alert(data.message);
                    location.reload();
                }
            } catch(e) {
                alert('ত্রুটি: ' + e.message);
            }
        }
    };
}
</script>
@endpush
@endsection
