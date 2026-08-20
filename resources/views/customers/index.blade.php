@extends('layouts.app')
@section('title', 'কাস্টমার CRM ও লয়্যালটি রিওয়ার্ড')
@section('content')
<div x-data="customerCrmManager()" x-init="init()" class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(139,26,44,0.1); border:1.5px solid rgba(139,26,44,0.25);">
                    <i data-lucide="users" class="w-5 h-5" style="color:#8B1A2C;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">কাস্টমার CRM ও লয়্যালটি রিওয়ার্ড</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">কাস্টমার প্রোফাইল, লয়্যালটি মেম্বারশিপ লেভেল, পয়েন্টস ও SMS ক্যাম্পেইন</p>
        </div>

        <div class="flex items-center gap-2">
            <button @click="openSmsModal = true;"
                    class="btn-outline px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="message-square" class="w-4 h-4"></i>
                <span>SMS ক্যাম্পেইন</span>
            </button>
            <button @click="openCustomerModal = true; resetCustomerForm();"
                    class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>+ নতুন কাস্টমার</span>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#8B1A2C;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">মোট নিবন্ধিত কাস্টমার</p>
            <p class="text-2xl font-black pos-nums price-maroon">{{ $totalCustomers }} <span class="text-xs font-normal" style="color:#9B7A7E;">জন</span></p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#B8922A;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">ইস্যুকৃত মোট রিওয়ার্ড পয়েন্ট</p>
            <p class="text-2xl font-black pos-nums" style="color:#B8922A;">{{ number_format($totalPointsIssued) }} <span class="text-xs font-normal" style="color:#9B7A7E;">পয়েন্ট</span></p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#2E7D52;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">Platinum VIP সদস্য</p>
            <p class="text-2xl font-black pos-nums" style="color:#2E7D52;">{{ $platinumCount }} <span class="text-xs font-normal" style="color:#9B7A7E;">জন</span></p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#0284C7;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">Gold সদস্য</p>
            <p class="text-2xl font-black pos-nums" style="color:#0284C7;">{{ $goldCount }} <span class="text-xs font-normal" style="color:#9B7A7E;">জন</span></p>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl p-3 border flex flex-col sm:flex-row items-center justify-between gap-3" style="border-color:#E8DDD9;">
        <form method="GET" action="{{ route('customers.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-64">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2" style="color:#9B7A7E;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="নাম বা মোবাইল নম্বর..."
                       class="pos-input w-full pl-9 pr-3 py-1.5 text-xs rounded-xl">
            </div>
            <select name="tier" onchange="this.form.submit()" class="pos-input text-xs rounded-xl px-2 py-1.5">
                <option value="">সকল মেম্বারশিপ</option>
                <option value="platinum" {{ request('tier') === 'platinum' ? 'selected' : '' }}>Platinum</option>
                <option value="gold" {{ request('tier') === 'gold' ? 'selected' : '' }}>Gold</option>
                <option value="silver" {{ request('tier') === 'silver' ? 'selected' : '' }}>Silver</option>
                <option value="bronze" {{ request('tier') === 'bronze' ? 'selected' : '' }}>Bronze</option>
            </select>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                    <tr>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">কাস্টমার</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">মেম্বারশিপ লেভেল</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">রিওয়ার্ড পয়েন্টস</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">মোট ভিজিট</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">লাইফটাইম স্পেন্ডিং</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px] text-right" style="color:#9B7A7E;">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                    <tr class="data-row border-b" style="border-color:#F0E8E5;">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-xs text-white"
                                     style="background: #8B1A2C;">
                                    {{ strtoupper(substr($c->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-xs" style="color:#1A0A0C;">{{ $c->name }}</p>
                                    <p class="text-[11px] pos-nums" style="color:#9B7A7E;">{{ $c->phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            @php
                                $tierStyles = [
                                    'platinum' => ['Platinum VIP', '#9333EA', '#F3E8FF'],
                                    'gold' => ['Gold Member', '#B8922A', '#FEF3C7'],
                                    'silver' => ['Silver', '#0284C7', '#E0F2FE'],
                                    'bronze' => ['Bronze', '#78716C', '#F5F5F4'],
                                ];
                                $ts = $tierStyles[$c->membership_tier] ?? ['Bronze', '#78716C', '#F5F5F4'];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold"
                                  style="background:{{ $ts[2] }}; color:{{ $ts[1] }};">
                                {{ $ts[0] }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="font-black pos-nums text-sm price-maroon">{{ $c->reward_points }}</span>
                            <span class="text-[10px] ml-0.5" style="color:#9B7A7E;">পয়েন্ট (৳{{ $c->reward_points }})</span>
                        </td>
                        <td class="px-4 py-3.5 pos-nums font-bold" style="color:#1A0A0C;">
                            {{ $c->total_visits }} বার
                        </td>
                        <td class="px-4 py-3.5 pos-nums font-black" style="color:#2E7D52;">
                            ৳{{ number_format($c->total_spent, 2) }}
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="openPointsAdjustModal({{ json_encode($c) }})"
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                        style="background:#FEF3C7; color:#92400E; border:1px solid #FCD34D;">
                                    ± পয়েন্ট
                                </button>
                                <button @click="editCustomer({{ json_encode($c) }})"
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                        style="background:#FBF1F3; color:#8B1A2C; border:1px solid rgba(139,26,44,0.25);">
                                    এডিট
                                </button>
                                <button @click="deleteCustomer({{ $c->id }}, '{{ $c->name }}')" class="p-1 text-gray-400 hover:text-rose-600">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center" style="color:#9B7A7E;">কোনো কাস্টমার পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t" style="border-color:#E8DDD9; background:#FBF8F5;">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- ════ MODAL: ADD / EDIT CUSTOMER ════ -->
    <div x-show="openCustomerModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openCustomerModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white" x-text="customerForm.id ? 'কাস্টমার প্রোফাইল এডিট' : 'নতুন কাস্টমার নিবন্ধন'"></h3>
                <button @click="openCustomerModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 space-y-3">
                <div>
                    <label class="section-heading">কাস্টমার নাম *</label>
                    <input type="text" x-model="customerForm.name" placeholder="e.g. Ashfaqul Islam" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">মোবাইল নম্বর *</label>
                        <input type="text" x-model="customerForm.phone" placeholder="01711000000" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold rounded-xl price-maroon">
                    </div>
                    <div>
                        <label class="section-heading">ইমেইল (ঐচ্ছিক)</label>
                        <input type="email" x-model="customerForm.email" placeholder="customer@mail.com" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">মেম্বারশিপ লেভেল</label>
                        <select x-model="customerForm.membership_tier" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                            <option value="bronze">Bronze (সাধারণ)</option>
                            <option value="silver">Silver Member</option>
                            <option value="gold">Gold Member</option>
                            <option value="platinum">Platinum VIP</option>
                        </select>
                    </div>
                    <div>
                        <label class="section-heading">লয়্যালটি পয়েন্টস</label>
                        <input type="number" x-model.number="customerForm.reward_points" placeholder="0" class="pos-input w-full px-3 py-2 text-xs pos-nums rounded-xl">
                    </div>
                </div>
                <div>
                    <label class="section-heading">ঠিকানা</label>
                    <input type="text" x-model="customerForm.address" placeholder="উদাঃ ধানমন্ডি, ঢাকা" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openCustomerModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveCustomer()" class="btn-maroon px-6 py-2.5 text-xs font-bold">সংরক্ষণ করুন</button>
            </div>
        </div>
    </div>

    <!-- ════ MODAL: ADJUST POINTS ════ -->
    <div x-show="openPointsModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openPointsModal = false"
             class="w-full max-w-sm bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #B8922A, #92400E);">
                <h3 class="text-sm font-bold text-white">লয়্যালটি পয়েন্টস অ্যাডজাস্ট</h3>
                <button @click="openPointsModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-xs font-bold" style="color:#1A0A0C;">কাস্টমার: <span x-text="activeCustomer?.name"></span></p>
                <div>
                    <label class="section-heading">পয়েন্ট যোগ/বিয়োগ (+/-)</label>
                    <input type="number" x-model.number="pointsAmount" placeholder="100 অথবা -50" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold rounded-xl">
                </div>
                <div>
                    <label class="section-heading">কারণ / বিবরণ</label>
                    <input type="text" x-model="pointsReason" placeholder="উদাঃ জন্মদিনের স্পেশাল বোনাস" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
            </div>
            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openPointsModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="submitPointsAdjust()" class="btn-maroon px-5 py-2 text-xs font-bold">পয়েন্ট আপডেট</button>
            </div>
        </div>
    </div>

    <!-- ════ MODAL: BULK SMS CAMPAIGN ════ -->
    <div x-show="openSmsModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openSmsModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <div class="flex items-center gap-2 text-white">
                    <i data-lucide="message-square" class="w-4 h-4" style="color:#D4AC50;"></i>
                    <h3 class="text-sm font-bold">প্রমোশনাল SMS ক্যাম্পেইন</h3>
                </div>
                <button @click="openSmsModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <label class="section-heading">প্রাপক নির্বাচন</label>
                    <select x-model="smsTarget" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                        <option value="all">সকল কাস্টমার ({{ $totalCustomers }} জন)</option>
                        <option value="platinum">Platinum VIP সদস্য</option>
                        <option value="gold">Gold ও Platinum সদস্য</option>
                    </select>
                </div>
                <div>
                    <label class="section-heading">এসএমএস বার্তা *</label>
                    <textarea x-model="smsText" rows="3" placeholder="সুলতান্স ডাইনে স্পেশাল মাটন কাচ্চিতে আজ ২০% ছাড়! কোড: KACCHI20"
                              class="pos-input w-full p-2.5 text-xs rounded-xl resize-none font-medium"></textarea>
                </div>
            </div>
            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openSmsModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="sendBulkSms()" class="btn-maroon px-5 py-2 text-xs font-bold">এসএমএস সেন্ড করুন</button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function customerCrmManager() {
    return {
        openCustomerModal: false,
        openPointsModal: false,
        openSmsModal: false,
        activeCustomer: null,
        pointsAmount: 100,
        pointsReason: 'বোনাস লয়্যালটি পয়েন্ট',
        smsTarget: 'all',
        smsText: '',

        customerForm: { id: null, name: '', phone: '', email: '', address: '', reward_points: 0, membership_tier: 'bronze' },

        init() { this.$nextTick(() => window.initLucideIcons()); },

        resetCustomerForm() {
            this.customerForm = { id: null, name: '', phone: '', email: '', address: '', reward_points: 0, membership_tier: 'bronze' };
        },

        editCustomer(c) {
            this.customerForm = { id: c.id, name: c.name, phone: c.phone, email: c.email || '', address: c.address || '', reward_points: c.reward_points, membership_tier: c.membership_tier };
            this.openCustomerModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        async saveCustomer() {
            if (!this.customerForm.name || !this.customerForm.phone) { alert('নাম ও মোবাইল নম্বর আবশ্যক!'); return; }
            try {
                const res = await fetch('{{ route('customers.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.customerForm)
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        openPointsAdjustModal(c) {
            this.activeCustomer = c;
            this.pointsAmount = 50;
            this.pointsReason = 'ম্যানুয়াল পয়েন্টস সমন্বয়';
            this.openPointsModal = true;
        },

        async submitPointsAdjust() {
            try {
                const res = await fetch(`/customers/${this.activeCustomer.id}/points`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ points: this.pointsAmount, description: this.pointsReason })
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async sendBulkSms() {
            if (!this.smsText) { alert('বার্তা লিখুন!'); return; }
            try {
                const res = await fetch('{{ route('customers.sms') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ phone: 'All Customers', message: this.smsText })
                });
                const data = await res.json();
                if (data.success) { alert('এসএমএস ক্যাম্পেইন সফলভাবে পরিচালিত হয়েছে!'); this.openSmsModal = false; }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async deleteCustomer(id, name) {
            if (!confirm(`আপনি কি "${name}" এর রেকর্ড মুছে ফেলতে চান?`)) return;
            try {
                const res = await fetch(`/customers/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        }
    };
}
</script>
@endpush
@endsection
