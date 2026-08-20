@extends('layouts.app')
@section('title', 'SaaS মাল্টি-টেন্যান্ট সুপার-অ্যাডমিন')
@section('content')
<div x-data="saasSuperAdmin()" class="min-h-full p-5 lg:p-6 space-y-6 pb-24" style="background:#F5F0EC;">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(184,146,42,0.15); border:1.5px solid rgba(184,146,42,0.35);">
                    <i data-lucide="building-2" class="w-5 h-5" style="color:#B8922A;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">SaaS মাল্টি-টেন্যান্ট ক্লাউড কমান্ড সেন্টার</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">সকল সাবস্ক্রাইবড রেস্টুরেন্ট টেন্যান্ট, মাসিক রিকারিং রেভিনিউ (MRR) ও লাইসেন্স নিয়ন্ত্রণ</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('saas.plans') }}" class="btn-outline px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
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
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">প্ল্যাটফর্ম মোট ট্রানজেকশন</p>
            <p class="text-2xl font-black pos-nums" style="color:#7C3AED;">৳{{ number_format($totalSystemSales, 0) }}</p>
            <p class="text-[10px] text-gray-500 font-bold mt-1">মোট অর্ডার প্রসেসড: {{ number_format($totalOrdersCount) }}</p>
        </div>
    </div>

    <!-- Tenants Management Table -->
    <div class="bg-white rounded-3xl border shadow-xs overflow-hidden" style="border-color:#E8DDD9;">
        <div class="p-4 border-b flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3" style="border-color:#E8DDD9; background:#FDFBF9;">
            <div>
                <h3 class="text-sm font-black" style="color:#1A0A0C;">রেজিস্টার্ড রেস্টুরেন্ট টেন্যান্ট তালিকা</h3>
                <p class="text-xs" style="color:#9B7A7E;">ক্লাউড গ্রাহকদের প্ল্যান, স্ট্যাটাস ও শাখা সীমা নিয়ন্ত্রণ করুন</p>
            </div>
            <input type="text" x-model="searchQuery" placeholder="রেস্টুরেন্ট বা মালিকের নাম খুঁজুন..."
                   class="pos-input text-xs px-3 py-2 rounded-xl w-full sm:w-64">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="text-[10px] uppercase font-bold border-b" style="background:#F8F5F2; color:#9B7A7E; border-color:#E8DDD9;">
                    <tr>
                        <th class="p-3.5">রেস্টুরেন্ট নাম</th>
                        <th class="p-3.5">মালিক ও যোগাযোগ</th>
                        <th class="p-3.5">সাবস্ক্রিপশন প্ল্যান</th>
                        <th class="p-3.5">মাসিক ফি (BDT)</th>
                        <th class="p-3.5">শাখা সীমা</th>
                        <th class="p-3.5">স্ট্যাটাস</th>
                        <th class="p-3.5 text-right">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="divide-color:#F0E8E5;">
                    @foreach($tenants as $tenant)
                    <tr class="hover:bg-[#FAF6F4] transition-colors">
                        <td class="p-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-xs text-white shrink-0"
                                     style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                                    {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $tenant->name }}</p>
                                    <p class="text-[10px] font-mono text-gray-400">{{ $tenant->subdomain ?? $tenant->slug }}.posbd.cloud</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-3.5">
                            <p class="font-bold text-gray-800">{{ $tenant->owner_name }}</p>
                            <p class="text-[10px] text-gray-500">{{ $tenant->phone }} · {{ $tenant->email }}</p>
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
                        <td class="p-3.5 text-right">
                            <button @click="editTenant({{ $tenant }})"
                                    class="px-2.5 py-1.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-white hover:border-gray-400 font-bold transition-all">
                                ম্যানেজ
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
        searchQuery: '',
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
