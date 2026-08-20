@extends('layouts.app')
@section('title', 'NBR মূসক-৬.৩ চালান রেজিস্টার')
@section('content')
<div x-data="mushakViewer()" x-init="init()" class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(139,26,44,0.1); border:1.5px solid rgba(139,26,44,0.25);">
                    <i data-lucide="file-badge-2" class="w-5 h-5" style="color:#8B1A2C;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">মূসক-৬.৩ কর চালানপত্র রেজিস্টার</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">জাতীয় রাজস্ব বোর্ড (NBR) বিধিমালা অনুযায়ী বিক্রয় কর চালানপত্র তালিকা · BIN: {{ $currentBranch->bin_number ?? '001928374-0102' }}</p>
        </div>

        <form method="GET" action="{{ route('reports.mushak') }}" class="flex items-center gap-2">
            <input type="date" name="start_date" value="{{ $startDate }}" class="pos-input rounded-xl px-3 py-2 text-xs">
            <span class="text-xs" style="color:#9B7A7E;">→</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="pos-input rounded-xl px-3 py-2 text-xs">
            <button type="submit" class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold">ফিল্টার</button>
        </form>
    </div>

    <!-- Summary KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#8B1A2C;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">মোট করযোগ্য বিক্রয়</p>
            <p class="text-2xl font-black pos-nums price-maroon">৳{{ number_format($totalMushakSales, 2) }}</p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#B8922A;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">সংগৃহীত মূসক ভ্যাট ({{ $currentBranch->default_vat_rate ?? 5 }}%)</p>
            <p class="text-2xl font-black pos-nums" style="color:#B8922A;">৳{{ number_format($totalMushakVat, 2) }}</p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#2E7D52;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">মোট চালানপত্র সংখ্যা</p>
            <p class="text-2xl font-black pos-nums" style="color:#2E7D52;">{{ $orders->total() }} <span class="text-xs font-normal" style="color:#9B7A7E;">টি</span></p>
        </div>
    </div>

    <!-- Mushak Table -->
    <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                    <tr>
                        @foreach(['চালান নং (মূসক-৬.৩)','অর্ডার নং','তারিখ ও সময়','গ্রাহক','মূল্য (কর ছাড়া)','মূসক ভ্যাট','Grand Total','পদ্ধতি','অ্যাকশন'] as $th)
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">{{ $th }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr class="data-row border-b" style="border-color:#F0E8E5;">
                        <td class="px-4 py-3.5 pos-nums font-black price-maroon">{{ $order->mushak_number }}</td>
                        <td class="px-4 py-3.5 pos-nums font-bold" style="color:#1A0A0C;">{{ $order->order_number }}</td>
                        <td class="px-4 py-3.5 pos-nums text-[11px]" style="color:#9B7A7E;">{{ $order->created_at->format('d/m/Y h:i A') }}</td>
                        <td class="px-4 py-3.5">
                            <p class="font-bold" style="color:#1A0A0C;">{{ $order->customer_name ?? 'Walk-in Guest' }}</p>
                            @if($order->customer_phone)<p class="text-[10px]" style="color:#9B7A7E;">{{ $order->customer_phone }}</p>@endif
                        </td>
                        <td class="px-4 py-3.5 pos-nums" style="color:#5C3840;">৳{{ number_format($order->subtotal - $order->discount_amount, 2) }}</td>
                        <td class="px-4 py-3.5 pos-nums font-bold" style="color:#B8922A;">৳{{ number_format($order->vat_amount, 2) }}</td>
                        <td class="px-4 py-3.5 pos-nums font-black price-maroon">৳{{ number_format($order->grand_total, 2) }}</td>
                        <td class="px-4 py-3.5">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase" style="background:#F0E8E5; color:#5C3840;">
                                {{ $order->payment_method ?? 'CASH' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <button @click="viewInvoice({{ $order->id }})"
                                    class="px-3 py-1.5 rounded-lg text-[11px] font-bold transition-all"
                                    style="background:#FBF1F3; color:#8B1A2C; border:1px solid rgba(139,26,44,0.25);"
                                    onmouseover="this.style.background='rgba(139,26,44,0.15)'"
                                    onmouseout="this.style.background='#FBF1F3'">
                                চালান দেখুন
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="px-4 py-10 text-center" style="color:#9B7A7E;">কোনো চালানপত্র পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t" style="border-color:#E8DDD9; background:#FBF8F5;">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Print Modal -->
    <div x-show="openReprintModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openReprintModal = false"
             class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border"
             style="border-color:#E8DDD9;">
            <div class="px-4 py-3 flex items-center justify-between border-b" style="background:#FBF8F5; border-color:#E0D4CF;">
                <span class="text-xs font-bold" style="color:#1A0A0C;">মূসক-৬.৩ কর চালানপত্র</span>
                <div class="flex items-center gap-2">
                    <button @click="window.print()" class="btn-maroon px-3 py-1.5 rounded-lg text-xs font-bold">প্রিন্ট</button>
                    <button @click="openReprintModal = false" style="color:#9B7A7E;">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <div class="p-4 overflow-y-auto font-mono text-[11px] leading-snug space-y-2">
                <div class="text-center pb-2 border-b border-dashed border-gray-300">
                    <p class="text-[9px] font-bold">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার · জাতীয় রাজস্ব বোর্ড</p>
                    <p class="text-xs font-black uppercase mt-1">কর চালানপত্র (মূসক-৬.৩)</p>
                </div>
                <div class="text-center pb-2 border-b border-dashed border-gray-300">
                    <p class="font-black text-sm" x-text="mushakPayload?.branch?.name"></p>
                    <p class="text-[9px]" x-text="mushakPayload?.branch?.address"></p>
                    <p class="text-[10px] font-bold mt-0.5">BIN: <span x-text="mushakPayload?.branch?.bin"></span></p>
                </div>
                <div class="pb-2 border-b border-dashed border-gray-300 space-y-0.5 text-[10px]">
                    <div class="flex justify-between"><span>চালান নং:</span><span class="font-bold" x-text="mushakPayload?.invoice?.mushak_no"></span></div>
                    <div class="flex justify-between"><span>অর্ডার নং:</span><span x-text="mushakPayload?.invoice?.order_no"></span></div>
                    <div class="flex justify-between"><span>তারিখ:</span><span x-text="mushakPayload?.invoice?.date"></span></div>
                </div>
                <div class="pb-2 border-b border-dashed border-gray-300 space-y-0.5 text-[10px]">
                    <div class="flex justify-between font-bold"><span>মূসক ভ্যাট (৫%):</span><span>৳<span x-text="mushakPayload?.summary?.vat_amount"></span></span></div>
                    <div class="flex justify-between text-sm font-black pt-1 border-t border-gray-300"><span>সর্বমোট:</span><span>৳<span x-text="mushakPayload?.summary?.grand_total"></span></span></div>
                </div>
                <div class="flex flex-col items-center pt-2">
                    <div id="reprintQr" class="p-1 bg-white border border-gray-200 inline-block"></div>
                    <p class="text-[9px] text-gray-500 mt-1">NBR QR ভেরিফিকেশন</p>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function mushakViewer() {
    return {
        openReprintModal: false,
        mushakPayload: null,
        init() { this.$nextTick(() => window.initLucideIcons()); },
        async viewInvoice(orderId) {
            const res = await fetch(`/pos/order/${orderId}/mushak`);
            const data = await res.json();
            if (data.success) {
                this.mushakPayload = data.mushak;
                this.openReprintModal = true;
                this.$nextTick(() => {
                    const qrDiv = document.getElementById('reprintQr');
                    if (qrDiv) { qrDiv.innerHTML = ''; new QRCode(qrDiv, { text: data.mushak.qr_string, width: 80, height: 80 }); }
                });
            }
        }
    };
}
</script>
@endpush
@endsection
