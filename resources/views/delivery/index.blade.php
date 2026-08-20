@extends('layouts.app')
@section('title', 'ডেলিভারি কমান্ড সেন্টার (Foodpanda / Pathao)')
@section('content')
<div x-data="deliveryHub()" x-init="init()" class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(226,19,110,0.12); border:1.5px solid rgba(226,19,110,0.3);">
                    <i data-lucide="bike" class="w-5 h-5" style="color:#e2136e;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">অনলাইন ডেলিভারি কমান্ড সেন্টার</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">Foodpanda, Pathao ও ইন-হাউস ডেলিভারি অর্ডার ট্র্যাকিং ও রাইডার হ্যান্ডওভার</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5"
                  style="background:#D1FAE5; color:#065F46; border:1px solid #A7F3D0;">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background:#10B981;"></span>
                <span>Webhook লাইভ সংযুক্ত</span>
            </span>
        </div>
    </div>

    <!-- Delivery Pipeline Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#F59E0B;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">নতুন / পেন্ডিং অর্ডার</p>
            <p class="text-2xl font-black pos-nums" style="color:#D97706;">{{ $pendingCount }} <span class="text-xs font-normal" style="color:#9B7A7E;">টি</span></p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#0284C7;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">রান্না হচ্ছে (Cooking)</p>
            <p class="text-2xl font-black pos-nums" style="color:#0284C7;">{{ $cookingCount }} <span class="text-xs font-normal" style="color:#9B7A7E;">টি</span></p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#2E7D52;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">রেডি / পিকআপ প্রস্তুত</p>
            <p class="text-2xl font-black pos-nums" style="color:#2E7D52;">{{ $readyCount }} <span class="text-xs font-normal" style="color:#9B7A7E;">টি</span></p>
        </div>
    </div>

    <!-- Delivery Orders Table -->
    <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                    <tr>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">অর্ডার নং</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">প্ল্যাটফর্ম</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">কাস্টমার ও ঠিকানা</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">আইটেম তালিকা</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">বিল মোট</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">স্ট্যাটাস</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px] text-right" style="color:#9B7A7E;">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryOrders as $ord)
                    <tr class="data-row border-b" style="border-color:#F0E8E5;">
                        <td class="px-4 py-3.5 pos-nums font-black price-maroon">{{ $ord->order_number }}</td>
                        <td class="px-4 py-3.5">
                            @if(str_contains(strtolower($ord->notes ?? ''), 'foodpanda'))
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black text-white" style="background:#e2136e;">foodpanda</span>
                            @elseif(str_contains(strtolower($ord->notes ?? ''), 'pathao'))
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black text-white" style="background:#dc2626;">pathao</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black text-white" style="background:#8B1A2C;">In-House</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <p class="font-bold" style="color:#1A0A0C;">{{ $ord->customer_name ?? 'Guest' }}</p>
                            <p class="text-[10px]" style="color:#9B7A7E;">{{ $ord->customer_phone ?? 'Phone N/A' }}</p>
                            <p class="text-[10px] text-gray-500 line-clamp-1">{{ $ord->customer_address }}</p>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="space-y-0.5">
                                @foreach($ord->items as $it)
                                <p class="text-[11px]" style="color:#5C3840;">
                                    {{ $it->quantity }}x {{ $it->item->name ?? $it->item_name }}
                                </p>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3.5 pos-nums font-black price-maroon">৳{{ number_format($ord->grand_total, 2) }}</td>
                        <td class="px-4 py-3.5">
                            @if($ord->status === 'completed')
                                <span class="badge-paid px-2.5 py-1 rounded-full text-[10px] font-bold">✓ ডেলিভারি সম্পন্ন</span>
                            @elseif($ord->status === 'ready')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold" style="background:#D1FAE5; color:#065F46;">পিকআপ প্রস্তুত</span>
                            @elseif($ord->status === 'cooking')
                                <span class="badge-cooking px-2.5 py-1 rounded-full text-[10px] font-bold">রান্না হচ্ছে</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold" style="background:#FEF3C7; color:#92400E;">পেন্ডিং</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            @if($ord->status !== 'completed')
                            <button @click="updateStatus({{ $ord->id }}, 'delivered')"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold"
                                    style="background:#D1FAE5; color:#065F46; border:1px solid #A7F3D0;">
                                হ্যান্ডওভার সম্পন্ন ✓
                            </button>
                            @else
                            <span class="text-[11px] text-gray-400">ডেলিভার্ড</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center" style="color:#9B7A7E;">কোনো অনলাইন ডেলিভারি অর্ডার নেই।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t" style="border-color:#E8DDD9; background:#FBF8F5;">
            {{ $deliveryOrders->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script>
function deliveryHub() {
    return {
        init() { this.$nextTick(() => window.initLucideIcons()); },
        async updateStatus(orderId, status) {
            try {
                const res = await fetch(`/delivery-orders/${orderId}/status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ status })
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
