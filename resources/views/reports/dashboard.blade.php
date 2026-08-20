@extends('layouts.app')
@section('title', 'সেলস ড্যাশবোর্ড ও অ্যানালিটিক্স')
@section('content')
<div x-data="dashboardAnalytics()" x-init="init()" class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(139,26,44,0.1); border:1.5px solid rgba(139,26,44,0.25);">
                    <i data-lucide="bar-chart-3" class="w-5 h-5" style="color:#8B1A2C;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">এক্সিকিউটিভ সেলস ড্যাশবোর্ড</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">{{ now()->format('d F, Y — l') }} · {{ $currentBranch->name ?? 'Main Branch' }}</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('reports.mushak') }}"
               class="btn-outline px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2">
                <i data-lucide="file-badge-2" class="w-4 h-4"></i>
                <span>মূসক ৬.৩ রেজিস্টার</span>
            </a>
            <button @click="fetchAiSummary()" :disabled="loadingAi"
                    class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2">
                <i data-lucide="sparkles" class="w-4 h-4"></i>
                <span x-text="loadingAi ? 'AI বিশ্লেষণ করছে...' : 'Gemini AI সামারি'"></span>
            </button>
        </div>
    </div>

    <!-- AI Insight Card -->
    <div class="bg-white rounded-2xl p-5 border relative overflow-hidden" style="border-color:#E8DDD9;">
        <div class="flex items-start gap-4 relative">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(184,146,42,0.15); border:1px solid rgba(184,146,42,0.3);">
                <i data-lucide="brain" class="w-5 h-5" style="color:#B8922A;"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between mb-1.5">
                    <h3 class="text-sm font-extrabold" style="color:#1A0A0C;">Gemini AI Business Briefing</h3>
                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase" style="background:#FEF3C7; color:#92400E; border:1px solid #FCD34D;">LIVE AI</span>
                </div>
                <p class="text-xs leading-relaxed" style="color:#5C3840;"
                   x-html="aiInsightText || 'Gemini AI থেকে আজকের বিক্রয়, জনপ্রিয় আইটেম ও ইনভেন্টরি বিশ্লেষণ পেতে উপরের বাটনে ক্লিক করুন।'"></p>
            </div>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @php
            $kpis2 = [
                ['label'=>'আজকের মোট বিক্রয়','value'=>'৳'.number_format($todaySales,2),'icon'=>'banknote','color'=>'#8B1A2C','accent'=>'#8B1A2C'],
                ['label'=>'সংগৃহীত NBR ভ্যাট','value'=>'৳'.number_format($todayVat,2),'icon'=>'receipt','color'=>'#B8922A','accent'=>'#B8922A'],
                ['label'=>'মোট অর্ডার সংখ্যা','value'=>$todayOrderCount.' টি','icon'=>'shopping-bag','color'=>'#2E7D52','accent'=>'#2E7D52'],
                ['label'=>'গড় অর্ডার মূল্য (AOV)','value'=>'৳'.($todayOrderCount>0?number_format($todaySales/$todayOrderCount,2):'0.00'),'icon'=>'trending-up','color'=>'#1E40AF','accent'=>'#1E40AF'],
            ];
        @endphp
        @foreach($kpis2 as $k)
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:{{ $k['accent'] }};"></div>
            <div class="flex items-start justify-between mb-2">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#F8F5F2; border:1px solid #E8DDD9;">
                    <i data-lucide="{{ $k['icon'] }}" class="w-4 h-4" style="color:{{ $k['color'] }};"></i>
                </div>
            </div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">{{ $k['label'] }}</p>
            <p class="text-xl font-black pos-nums leading-none" style="color:{{ $k['color'] }};">{{ $k['value'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Payment Breakdown & Top Sellers -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
        <!-- Payment Methods -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border" style="border-color:#E8DDD9;">
            <h3 class="text-sm font-extrabold mb-4 flex items-center gap-2" style="color:#1A0A0C;">
                <i data-lucide="wallet" class="w-4 h-4" style="color:#8B1A2C;"></i>
                পেমেন্ট পদ্ধতি অনুপাত
            </h3>
            @php
                $paymentMethods = [
                    ['label'=>'ক্যাশ (Cash)','key'=>'cash','color'=>'#2E7D52'],
                    ['label'=>'বিকাশ (bKash)','key'=>'bkash','color'=>'#e2136e'],
                    ['label'=>'নগদ (Nagad)','key'=>'nagad','color'=>'#f7931e'],
                    ['label'=>'কার্ড POS','key'=>'card','color'=>'#8B1A2C'],
                ];
            @endphp
            <div class="space-y-4">
                @foreach($paymentMethods as $pm)
                @php $val = $payments[$pm['key']] ?? 0; $pct = $todaySales > 0 ? ($val/$todaySales)*100 : 0; @endphp
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span style="color:#5C3840;">{{ $pm['label'] }}</span>
                        <span class="pos-nums font-bold" style="color:{{ $pm['color'] }};">৳{{ number_format($val, 2) }} ({{ number_format($pct, 0) }}%)</span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill" style="width: {{ number_format($pct, 1) }}%; background: {{ $pm['color'] }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Top Sellers -->
        <div class="lg:col-span-3 bg-white rounded-2xl p-5 border" style="border-color:#E8DDD9;">
            <h3 class="text-sm font-extrabold mb-4 flex items-center gap-2" style="color:#1A0A0C;">
                <i data-lucide="flame" class="w-4 h-4" style="color:#B8922A;"></i>
                আজকের সেরা বিক্রিত মেনু আইটেম
            </h3>
            <div class="space-y-2">
                @forelse($topItems as $top)
                <div class="flex items-center gap-3 p-2.5 rounded-xl border data-row" style="border-color:#F0E8E5;">
                    <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black pos-nums shrink-0"
                          style="background: {{ $loop->first ? 'rgba(184,146,42,0.15)' : '#F8F5F2' }}; color: {{ $loop->first ? '#B8922A' : '#5C3840' }}; border: 1px solid {{ $loop->first ? '#FCD34D' : '#E8DDD9' }};">
                        {{ $loop->iteration }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold truncate" style="color:#1A0A0C;">{{ $top->item_name }}</p>
                        <p class="text-[10px]" style="color:#9B7A7E;">{{ $top->total_qty }} পরিবেশন</p>
                    </div>
                    <span class="pos-nums font-black text-sm price-maroon">৳{{ number_format($top->total_revenue, 2) }}</span>
                </div>
                @empty
                <div class="py-8 text-center text-xs" style="color:#9B7A7E;">এখনো কোনো বিক্রয় রেকর্ড নেই।</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
        <div class="px-5 py-3.5 flex items-center justify-between border-b" style="background:#FBF8F5; border-color:#E8DDD9;">
            <h3 class="text-sm font-extrabold" style="color:#1A0A0C;">সর্বশেষ অর্ডার ও চালান</h3>
            <a href="{{ route('reports.mushak') }}" class="text-xs font-bold hover:underline" style="color:#8B1A2C;">সকল মূসক চালান →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                    <tr>
                        @foreach(['অর্ডার নং','মূসক চালান','অর্ডার টাইপ','Grand Total','পেমেন্ট','ক্যাশিয়ার','সময়'] as $th)
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">{{ $th }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $ord)
                    <tr class="data-row border-b" style="border-color:#F0E8E5;">
                        <td class="px-4 py-3.5 pos-nums font-bold" style="color:#1A0A0C;">{{ $ord->order_number }}</td>
                        <td class="px-4 py-3.5 pos-nums font-bold" style="color:#8B1A2C;">{{ $ord->mushak_number }}</td>
                        <td class="px-4 py-3.5">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase" style="background:#F0E8E5; color:#5C3840;">
                                {{ $ord->order_type }}{{ $ord->table ? ' · '.$ord->table->name : '' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 pos-nums font-black price-maroon">৳{{ number_format($ord->grand_total, 2) }}</td>
                        <td class="px-4 py-3.5">
                            @if($ord->payment_status === 'paid')
                                <span class="badge-paid px-2 py-0.5 rounded-full text-[10px] font-bold">PAID</span>
                            @else
                                <span class="badge-unpaid px-2 py-0.5 rounded-full text-[10px] font-bold">UNPAID</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5" style="color:#5C3840;">{{ $ord->user->name ?? 'Staff' }}</td>
                        <td class="px-4 py-3.5 pos-nums" style="color:#9B7A7E;">{{ $ord->created_at->format('h:i A') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-xs" style="color:#9B7A7E;">কোনো অর্ডার পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
function dashboardAnalytics() {
    return {
        aiInsightText: '',
        loadingAi: false,
        init() { this.$nextTick(() => window.initLucideIcons()); },
        async fetchAiSummary() {
            this.loadingAi = true;
            try {
                const res = await fetch('{{ route('reports.ai.insight') }}');
                const data = await res.json();
                if (data.success) this.aiInsightText = data.insight.replace(/\n/g, '<br>');
            } catch (err) {
                this.aiInsightText = 'AI সামারি লোড করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।';
            } finally { this.loadingAi = false; }
        }
    };
}
</script>
@endpush
@endsection
