@extends('layouts.app')
@section('title', 'KDS - কিচেন ডিসপ্লে সিস্টেম')
@section('content')
<div x-data="kdsBoard()" x-init="init()" class="h-full flex flex-col" style="background:#F5F0EC;">

    <!-- KDS Top Bar -->
    <div class="h-[56px] px-4 flex items-center justify-between shrink-0 bg-white border-b" style="border-color:#E0D4CF;">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(184,146,42,0.12); border:1.5px solid rgba(184,146,42,0.3);">
                <i data-lucide="flame" class="w-4 h-4" style="color:#B8922A;"></i>
            </div>
            <div>
                <h2 class="text-sm font-extrabold" style="color:#1A0A0C;">কিচেন ডিসপ্লে সিস্টেম (KDS)</h2>
                <p class="text-[10px]" style="color:#9B7A7E;">লাইভ অর্ডার অটো-রিফ্রেশ: প্রতি ৪ সেকেন্ড</p>
            </div>
        </div>

        <!-- Station Filters -->
        <div class="flex items-center gap-1.5 overflow-x-auto">
            @foreach([['id'=>'all','label'=>'সকল স্টেশন'],['id'=>'main_kitchen','label'=>'মেইন কিচেন'],['id'=>'grill','label'=>'গ্রিল ও নান'],['id'=>'drinks_bar','label'=>'ড্রিংকস বার'],['id'=>'dessert','label'=>'ডেজার্ট']] as $stn)
            <button @click="station = '{{ $stn['id'] }}'; fetchTickets();"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap border"
                    :style="station === '{{ $stn['id'] }}' ? 'background:#8B1A2C; color:#fff; border-color:#8B1A2C;' : 'background:#F8F5F2; color:#5C3840; border-color:#E8DDD9;'">
                {{ $stn['label'] }}
                <template x-if="station === '{{ $stn['id'] }}'">
                    <span class="ml-1 px-1.5 py-0.2 rounded-full text-[10px] pos-nums font-black" style="background:rgba(255,255,255,0.25);" x-text="tickets.length"></span>
                </template>
            </button>
            @endforeach
        </div>

        <!-- Sound Toggle -->
        <button @click="soundEnabled = !soundEnabled"
                class="w-8 h-8 rounded-xl flex items-center justify-center transition-all border"
                :style="soundEnabled ? 'background:#D1FAE5; border-color:#A7F3D0; color:#065F46;' : 'background:#F8F5F2; color:#9B7A7E; border-color:#E8DDD9;'">
            <i :data-lucide="soundEnabled ? 'volume-2' : 'volume-x'" class="w-4 h-4"></i>
        </button>
    </div>

    <!-- KOT Cards Grid -->
    <div class="flex-1 p-4 overflow-y-auto" style="background:#F5F0EC;">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3.5">
            <template x-for="ticket in tickets" :key="ticket.id">
                <div class="rounded-2xl overflow-hidden flex flex-col bg-white border shadow-sm transition-all duration-200"
                     :class="ticket.urgency === 'critical' ? 'kot-critical' : ticket.urgency === 'warning' ? 'kot-warning' : 'kot-fresh'">

                    <!-- Card Header -->
                    <div class="px-3.5 py-2.5 flex items-start justify-between border-b"
                         :style="ticket.urgency === 'critical' ? 'background:#FEE2E2; border-color:#FCA5A5;' : ticket.urgency === 'warning' ? 'background:#FEF3C7; border-color:#FCD34D;' : 'background:#FBF8F5; border-color:#E8DDD9;'">
                        <div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs pos-nums font-black" style="color:#1A0A0C;" x-text="ticket.order_number"></span>
                                <span x-show="ticket.token_number" class="text-[10px] pos-nums font-bold px-1.5 rounded"
                                      style="background:rgba(0,0,0,0.06); color:#5C3840;" x-text="'#'+ticket.token_number"></span>
                            </div>
                            <p class="text-[11px] font-bold mt-0.5" style="color:#8B1A2C;" x-text="ticket.table_name ? 'টেবিল: '+ticket.table_name : 'পার্সেল / ডেলিভারি'"></p>
                        </div>
                        <!-- Timer Badge -->
                        <div class="flex items-center gap-1 px-2 py-1 rounded-lg text-[11px] pos-nums font-black"
                             :style="ticket.urgency === 'critical' ? 'background:#DC2626; color:#fff;' : ticket.urgency === 'warning' ? 'background:#D97706; color:#fff;' : 'background:#D1FAE5; color:#065F46;'">
                            <i data-lucide="timer" class="w-3 h-3"></i>
                            <span x-text="ticket.elapsed_formatted"></span>
                        </div>
                    </div>

                    <!-- Items -->
                    <div class="flex-1 p-3 space-y-1.5 overflow-y-auto max-h-64" style="background:#FFFFFF;">
                        <template x-for="item in ticket.items" :key="item.id">
                            <div class="p-2 rounded-xl flex items-start justify-between gap-2 border transition-all"
                                 :style="item.kitchen_status === 'ready' ? 'background:#F0FDF4; border-color:#BBF7D0; opacity:0.75;' : item.kitchen_status === 'cooking' ? 'background:#FFFBEB; border-color:#FDE68A;' : 'background:#F8F5F2; border-color:#E8DDD9;'">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black pos-nums shrink-0 text-white"
                                              style="background:#8B1A2C;" x-text="item.quantity+'x'"></span>
                                        <span class="text-xs font-bold truncate" style="color:#1A0A0C;" x-text="item.name"></span>
                                    </div>
                                    <p x-show="item.variant" class="text-[10px] pl-6 mt-0.5 font-bold" style="color:#B8922A;" x-text="item.variant"></p>
                                    <p x-show="item.notes" class="text-[10px] pl-6 italic" style="color:#C02020;" x-text="'📝 '+item.notes"></p>
                                </div>
                                <button @click="bumpItem(item)"
                                        class="px-2 py-1 rounded-lg text-[10px] font-bold shrink-0 transition-all active:scale-95 border"
                                        :style="item.kitchen_status === 'pending' ? 'background:#FFFFFF; color:#5C3840; border-color:#D0BDB8;' : item.kitchen_status === 'cooking' ? 'background:#F59E0B; color:#fff; border-color:#D97706;' : 'background:#10B981; color:#fff; border-color:#059669;'"
                                        x-text="item.kitchen_status === 'pending' ? 'শুরু' : item.kitchen_status === 'cooking' ? 'রান্না হচ্ছে' : 'রেডি ✓'">
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Footer: Ready / Serve Action Button -->
                    <div class="p-2.5 border-t" style="background:#FBF8F5; border-color:#E8DDD9;">
                        <button x-show="ticket.status !== 'ready'"
                                @click="bumpEntireOrder(ticket, 'ready')"
                                class="btn-maroon w-full py-2 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 shadow-xs">
                            <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                            <span>সম্পূর্ণ অর্ডার রেডি ✓</span>
                        </button>
                        <button x-show="ticket.status === 'ready'"
                                @click="bumpEntireOrder(ticket, 'served')"
                                class="w-full py-2 rounded-xl text-xs font-black flex items-center justify-center gap-1.5 transition-all shadow-xs"
                                style="background:#10B981; color:#ffffff; border:1px solid #059669;">
                            <i data-lucide="check-check" class="w-4 h-4 stroke-[3]"></i>
                            <span>খাবার সার্ভড / স্ক্রিন ক্লিয়ার করুন ✓</span>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Empty State -->
        <div x-show="tickets.length === 0" class="h-64 flex flex-col items-center justify-center">
            <div class="w-14 h-14 rounded-3xl flex items-center justify-center mb-3" style="background:#FFFFFF; border:1px solid #E8DDD9;">
                <i data-lucide="utensils" class="w-7 h-7" style="color:#C0A0A4;"></i>
            </div>
            <p class="text-sm font-bold" style="color:#5C3840;">কিচেনে কোনো পেন্ডিং অর্ডার নেই</p>
            <p class="text-xs mt-1" style="color:#9B7A7E;">POS থেকে নতুন KOT আসলে এখানে স্বয়ংক্রিয়ভাবে দেখাবে</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function kdsBoard() {
    return {
        station: 'all',
        tickets: [],
        previousCount: 0,
        soundEnabled: true,
        init() {
            this.fetchTickets();
            setInterval(() => this.fetchTickets(), 4000);
        },
        async fetchTickets() {
            try {
                const res = await fetch(`{{ route('kds.tickets') }}?station=${this.station}`);
                const data = await res.json();
                if (data.success) {
                    if (data.tickets.length > this.previousCount && this.previousCount > 0 && this.soundEnabled) window.playBeep(880, 200);
                    this.previousCount = data.tickets.length;
                    this.tickets = data.tickets;
                    this.$nextTick(() => window.initLucideIcons());
                }
            } catch (e) {}
        },
        async bumpItem(item) {
            const next = item.kitchen_status === 'pending' ? 'cooking' : item.kitchen_status === 'cooking' ? 'ready' : 'served';
            const res = await fetch(`/kds/item/${item.id}/status`, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify({status: next}) });
            const data = await res.json();
            if (data.success) { item.kitchen_status = next; window.playBeep(990, 80); }
        },
        async bumpEntireOrder(ticket, status) {
            const res = await fetch(`/kds/order/${ticket.id}/bump`, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: JSON.stringify({status}) });
            const data = await res.json();
            if (data.success) { window.playBeep(1200, 150); this.fetchTickets(); }
        }
    };
}
</script>
@endpush
@endsection
