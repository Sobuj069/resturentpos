@extends('layouts.app')
@section('title', 'ক্যাপ্টেন ও ওয়েটার হ্যান্ডহেল্ড টার্মিনাল')
@section('content')
<div x-data="waiterApp()" x-init="init()" class="h-full flex flex-col overflow-hidden" style="background:#F5F0EC;">

    <!-- Top Bar -->
    <div class="h-[56px] px-4 flex items-center justify-between shrink-0 bg-white border-b shadow-xs" style="border-color:#E0D4CF;">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(184,146,42,0.15); border:1.5px solid rgba(184,146,42,0.3);">
                <i data-lucide="chef-hat" class="w-4 h-4" style="color:#B8922A;"></i>
            </div>
            <div>
                <h2 class="text-sm font-extrabold" style="color:#1A0A0C;">ক্যাপ্টেন ও ওয়েটার টার্মিনাল</h2>
                <p class="text-[10px]" style="color:#9B7A7E;">টেবিল অর্ডার ও কিচেন KOT ফায়ারিং</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button @click="openTransferModal = true" class="px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1 border"
                    style="background:#FBF1F3; color:#8B1A2C; border-color:rgba(139,26,44,0.25);">
                <i data-lucide="arrow-right-left" class="w-3.5 h-3.5"></i>
                <span>টেবিল ট্রান্সফার</span>
            </button>
        </div>
    </div>

    <!-- Layout Area -->
    <div class="flex-1 flex flex-col md:flex-row overflow-hidden">
        <!-- Tables Selector Bar / Grid -->
        <div class="w-full md:w-80 border-r flex flex-col shrink-0 bg-white overflow-hidden" style="border-color:#E0D4CF;">
            <div class="p-3 border-b" style="background:#FBF8F5; border-color:#E0D4CF;">
                <p class="section-heading">টেবিল নির্বাচন করুন</p>
            </div>
            <div class="p-3 overflow-y-auto flex-1 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-2 gap-2" style="background:#F8F5F2;">
                <template x-for="t in tables" :key="t.id">
                    <button @click="activeTable = t"
                            class="p-2.5 rounded-2xl border flex flex-col items-center justify-center text-center transition-all relative"
                            :style="activeTable?.id === t.id ? 'background:#8B1A2C; color:#fff; border-color:#8B1A2C; box-shadow:0 2px 8px rgba(139,26,44,0.3);' : (t.status === 'occupied' ? 'background:#FEE2E2; color:#991B1B; border-color:#FCA5A5;' : 'background:#FFFFFF; color:#1A0A0C; border-color:#E8DDD9;')">
                        <i data-lucide="utensils" class="w-4 h-4 mb-1"></i>
                        <span class="text-xs font-black pos-nums" x-text="t.name"></span>
                        <span class="text-[9px]" :style="activeTable?.id === t.id ? 'color:rgba(255,255,255,0.8);' : 'color:#9B7A7E;'" x-text="t.status === 'occupied' ? 'খাচ্ছে' : 'খালি'"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Menu Catalog & Instant KOT Cart -->
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden" style="background:#F5F0EC;">
            <!-- Category Chips -->
            <div class="p-2.5 bg-white border-b flex items-center gap-1.5 overflow-x-auto scrollbar-none shrink-0" style="border-color:#E0D4CF;">
                <button @click="selectedCategory = null"
                        class="cat-chip px-3 py-1.5 text-xs font-bold whitespace-nowrap"
                        :class="selectedCategory === null ? 'active' : ''">সকল মেনু</button>
                <template x-for="cat in categories" :key="cat.id">
                    <button @click="selectedCategory = cat.id"
                            class="cat-chip px-3 py-1.5 text-xs font-bold whitespace-nowrap"
                            :class="selectedCategory === cat.id ? 'active' : ''"
                            x-text="cat.bangla_name || cat.name"></button>
                </template>
            </div>

            <!-- Items -->
            <div class="flex-1 p-3 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2.5 pb-24">
                <template x-for="item in filteredItems" :key="item.id">
                    <button @click="addToCart(item)"
                            class="pos-card p-2 rounded-2xl bg-white border text-left flex flex-col justify-between active:scale-95 shadow-xs transition-all hover:shadow-md h-full"
                            style="border-color:#E8DDD9; min-height: 190px;">
                        <!-- Food Image (Strict Uniform Height) -->
                        <div class="relative w-full rounded-xl overflow-hidden mb-1.5 bg-[#F8F5F2] flex items-center justify-center border border-black/5 shrink-0"
                             style="height: 105px;">
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.name" style="width:100%; height:105px; object-fit:cover;" class="block">
                            </template>
                            <template x-if="!item.image">
                                <i data-lucide="utensils" class="w-5 h-5 opacity-35" style="color:#8B1A2C;"></i>
                            </template>
                        </div>
                        <div class="flex-1 flex flex-col justify-between w-full">
                            <div class="mb-1">
                                <h4 class="text-xs font-bold line-clamp-1" style="color:#1A0A0C;" x-text="item.name"></h4>
                                <p class="text-[10px] line-clamp-1" style="color:#9B7A7E;" x-text="item.bangla_name"></p>
                            </div>
                            <div class="mt-auto pt-1.5 border-t flex items-center justify-between" style="border-color:#F0E8E5;">
                                <span class="text-xs font-black pos-nums price-maroon">৳<span x-text="formatNumber(item.selling_price)"></span></span>
                                <div class="w-6 h-6 rounded-lg flex items-center justify-center" style="background:#8B1A2C; color:#fff;">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                </div>
                            </div>
                        </div>
                    </button>
                </template>
            </div>

            <!-- Floating KOT Bar -->
            <div x-show="cart.length > 0" x-cloak class="p-3 bg-white border-t shrink-0 flex items-center justify-between shadow-lg" style="border-color:#E0D4CF;">
                <div>
                    <p class="text-xs font-bold" style="color:#1A0A0C;">টেবিল: <span class="price-maroon" x-text="activeTable ? activeTable.name : 'টেবিল নির্বাচন করুন'"></span></p>
                    <p class="text-[10px]" style="color:#9B7A7E;"><span x-text="cart.length"></span> টি আইটেম · মোট ৳<span x-text="formatNumber(totalCart)"></span></p>
                </div>
                <button @click="sendWaiterKOT()" :disabled="!activeTable || isSending"
                        class="btn-maroon px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span x-text="isSending ? 'পাঠানো হচ্ছে...' : 'কিচেনে KOT পাঠান'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- ════ MODAL: TABLE TRANSFER ════ -->
    <div x-show="openTransferModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openTransferModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white">টেবিল শিফট ও ট্রান্সফার</h3>
                <button @click="openTransferModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="section-heading">বর্তমান টেবিল (যেখান থেকে শিফট হবে) *</label>
                    <select x-model="transferSourceId" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                        <option value="">বেছে নিন...</option>
                        @foreach($tables as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->status === 'occupied' ? 'খাচ্ছে' : 'খালি' }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="section-heading">নতুন গন্তব্য টেবিল *</label>
                    <select x-model="transferTargetId" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                        <option value="">বেছে নিন...</option>
                        @foreach($tables as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->floor_name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openTransferModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="submitTableTransfer()" class="btn-maroon px-5 py-2 text-xs font-bold">টেবিল ট্রান্সফার নিশ্চিত করুন</button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function waiterApp() {
    return {
        tables: @json($tables),
        categories: @json($categories),
        allItems: @json($categories->flatMap->items),
        selectedCategory: null,
        activeTable: null,
        cart: [],
        isSending: false,
        openTransferModal: false,
        transferSourceId: '',
        transferTargetId: '',

        init() { this.$nextTick(() => window.initLucideIcons()); },

        get filteredItems() {
            if (this.selectedCategory === null) return this.allItems;
            return this.allItems.filter(i => i.category_id === this.selectedCategory);
        },

        addToCart(item) {
            const existing = this.cart.find(c => c.item_id === item.id);
            if (existing) existing.quantity++;
            else this.cart.push({ item_id: item.id, name: item.name, unit_price: parseFloat(item.selling_price), quantity: 1 });
            window.playBeep(920, 80);
            this.$nextTick(() => window.initLucideIcons());
        },

        get totalCart() { return this.cart.reduce((s, i) => s + (i.unit_price * i.quantity), 0); },

        async sendWaiterKOT() {
            if (!this.activeTable || this.cart.length === 0) return;
            this.isSending = true;
            try {
                const res = await fetch('{{ route('pos.order.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        order_type: 'dine_in',
                        table_id: this.activeTable.id,
                        token_number: Math.floor(10 + Math.random() * 90).toString(),
                        payment_status: 'unpaid',
                        items: this.cart.map(c => ({ item_id: c.item_id, quantity: c.quantity, unit_price: c.unit_price }))
                    })
                });
                const data = await res.json();
                if (data.success) {
                    window.playBeep(1200, 150);
                    alert('KOT কিচেনে পাঠানো হয়েছে! অর্ডার নং: ' + data.order.order_number);
                    this.cart = [];
                    location.reload();
                }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
            finally { this.isSending = false; }
        },

        async submitTableTransfer() {
            if (!this.transferSourceId || !this.transferTargetId) { alert('উভয় টেবিল সিলেক্ট করুন!'); return; }
            try {
                const res = await fetch('{{ route('waiter.transfer') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ source_table_id: this.transferSourceId, target_table_id: this.transferTargetId })
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else { alert(data.message); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        formatNumber(n) { return (parseFloat(n) || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
    };
}
</script>
@endpush
@endsection
