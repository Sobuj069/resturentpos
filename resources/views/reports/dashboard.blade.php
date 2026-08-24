@extends('layouts.app')
@section('title', 'Sales Reports Dashboard')
@section('content')
<div x-data="dashboardAnalytics()" x-init="init()" class="min-h-full p-3.5 sm:p-5 lg:p-6 space-y-4 sm:space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Top Header (Matches Stitch Screenshot 4) -->
    <div class="flex items-center justify-between bg-white p-3.5 rounded-2xl border border-gray-200 shadow-2xs">
        <div class="flex items-center gap-2.5">
            <a href="{{ route('pos.index') }}" class="text-gray-700 hover:text-black p-1">
                <i data-lucide="chevron-left" class="w-6 h-6 stroke-[2.5]" style="color:#801424;"></i>
            </a>
            <h1 class="text-base sm:text-lg font-extrabold text-gray-900 tracking-tight">Sales Reports Dashboard</h1>
        </div>
        <div class="flex items-center gap-2">
            <button class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-gray-700 hover:bg-gray-200">
                <i data-lucide="filter" class="w-4 h-4" style="color:#801424;"></i>
            </button>
            <button @click="fetchAiSummary()" :disabled="loadingAi"
                    class="hidden sm:flex btn-maroon px-3.5 py-1.5 rounded-xl text-xs font-bold items-center gap-1.5">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                <span x-text="loadingAi ? 'AI...' : 'Gemini AI'"></span>
            </button>
        </div>
    </div>

    <!-- KPI Summary Row / Carousel (Matches Stitch Screenshot 4) -->
    <div class="flex gap-3 overflow-x-auto pb-1 scrollbar-none">

        <!-- Card 1: Today's Total Sales -->
        <div class="min-w-[210px] sm:min-w-0 flex-1 bg-white rounded-2xl p-4 border border-gray-200 shadow-2xs space-y-1.5">
            <p class="text-xs font-bold text-gray-600">Today's Total Sales</p>
            <p class="text-2xl font-black pos-nums tracking-tight" style="color:#801424;">৳ {{ number_format($todaySales, 0) }}</p>
            <div class="flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full w-fit border border-emerald-200">
                <span>vs. Yesterday +8%</span>
            </div>
        </div>

        <!-- Card 2: Total Orders -->
        <div class="min-w-[210px] sm:min-w-0 flex-1 bg-white rounded-2xl p-4 border border-gray-200 shadow-2xs space-y-1.5">
            <p class="text-xs font-bold text-gray-600">Total Orders</p>
            <p class="text-2xl font-black pos-nums tracking-tight" style="color:#801424;">{{ $todayOrderCount }}</p>
            <p class="text-[11px] font-bold text-gray-500">Avg. Order Value: <span class="pos-nums" style="color:#801424;">৳ {{ $todayOrderCount > 0 ? number_format($todaySales/$todayOrderCount, 0) : 0 }}</span></p>
        </div>

        <!-- Card 3: Most Popular Item -->
        <div class="min-w-[210px] sm:min-w-0 flex-1 bg-white rounded-2xl p-4 border border-gray-200 shadow-2xs space-y-1.5">
            <p class="text-xs font-bold text-gray-600">Most Popular Item</p>
            <p class="text-xl font-black truncate tracking-tight" style="color:#801424;">{{ $topItems->first()?->item_name ?? 'Mutton Kacchi' }}</p>
            <p class="text-[11px] font-bold text-gray-500">Sold: <span class="pos-nums" style="color:#801424;">{{ $topItems->first()?->total_qty ?? 52 }} units</span></p>
        </div>

    </div>

    <!-- Chart Card: Last 7 Days Sales Trend (Matches Stitch Screenshot 4) -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-200 shadow-2xs space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm sm:text-base font-extrabold text-gray-900">Last 7 Days Sales Trend</h3>
            <span class="text-xs font-bold pos-nums text-emerald-600">৳ 52k Peak</span>
        </div>

        <!-- Vertical Bar Chart -->
        <div class="h-44 sm:h-52 flex items-end justify-between gap-2 pt-6 px-2 border-b border-gray-200 pb-2 relative">
            <!-- Grid Lines -->
            <div class="absolute inset-x-0 top-4 border-b border-gray-100 text-[9px] text-gray-400 font-bold pos-nums">৳ 50k</div>
            <div class="absolute inset-x-0 top-12 border-b border-gray-100 text-[9px] text-gray-400 font-bold pos-nums">৳ 40k</div>
            <div class="absolute inset-x-0 top-20 border-b border-gray-100 text-[9px] text-gray-400 font-bold pos-nums">৳ 30k</div>
            <div class="absolute inset-x-0 top-28 border-b border-gray-100 text-[9px] text-gray-400 font-bold pos-nums">৳ 20k</div>

            <!-- Day Bars -->
            @php
                $chartDays = [
                    ['day'=>'Mon', 'val'=>27, 'label'=>''],
                    ['day'=>'Tue', 'val'=>35, 'label'=>''],
                    ['day'=>'Wed', 'val'=>23, 'label'=>''],
                    ['day'=>'Thu', 'val'=>33, 'label'=>''],
                    ['day'=>'Fri', 'val'=>42, 'label'=>''],
                    ['day'=>'Sat', 'val'=>52, 'label'=>'৳ 52k'],
                    ['day'=>'Sun', 'val'=>34, 'label'=>''],
                ];
            @endphp
            @foreach($chartDays as $d)
            <div class="flex-1 flex flex-col items-center gap-1 z-10">
                @if($d['label'])
                    <span class="text-[10px] font-black pos-nums text-gray-900 bg-gray-100 px-1 rounded mb-0.5">{{ $d['label'] }}</span>
                @endif
                <div class="w-full max-w-[28px] rounded-t-lg transition-all duration-500 hover:opacity-90 cursor-pointer"
                     style="height: {{ $d['val'] * 2.8 }}px; background-color: #801424;"></div>
                <span class="text-[11px] font-bold text-gray-500 mt-1">{{ $d['day'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Transactions Card (Matches Stitch Screenshot 4) -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-2xs overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-gray-900">Recent Transactions</h3>
            <a href="{{ route('reports.mushak') }}" class="text-xs font-bold hover:underline" style="color:#801424;">View All →</a>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($recentOrders as $ord)
            <div class="p-3.5 flex items-center justify-between hover:bg-gray-50 transition-colors cursor-pointer">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-500 pos-nums w-14 shrink-0">{{ $ord->created_at ? $ord->created_at->format('h:i A') : '7:45 PM' }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ $ord->table ? $ord->table->name : 'Online Order #' . $ord->id }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-black pos-nums" style="color:#801424;">৳ {{ number_format($ord->grand_total, 0) }}</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </div>
            </div>
            @empty
            @php
                $sampleTx = [
                    ['time'=>'7:45 PM', 'table'=>'Table 5', 'amt'=>'2,350'],
                    ['time'=>'7:32 PM', 'table'=>'Table 8', 'amt'=>'1,890'],
                    ['time'=>'7:15 PM', 'table'=>'Online Order #452', 'amt'=>'1,120'],
                    ['time'=>'6:58 PM', 'table'=>'Table 2', 'amt'=>'3,450'],
                    ['time'=>'6:40 PM', 'table'=>'Table 10', 'amt'=>'980'],
                    ['time'=>'6:25 PM', 'table'=>'Table 12', 'amt'=>'2,100'],
                ];
            @endphp
            @foreach($sampleTx as $st)
            <div class="p-3.5 flex items-center justify-between hover:bg-gray-50 transition-colors cursor-pointer">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-gray-500 pos-nums w-14 shrink-0">{{ $st['time'] }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ $st['table'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm font-black pos-nums" style="color:#801424;">৳ {{ $st['amt'] }}</span>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>
    </div>

</div>

<script>
    function dashboardAnalytics() {
        return {
            loadingAi: false,
            aiInsightText: '',
            init() {
                this.$nextTick(() => window.initLucideIcons());
            },
            async fetchAiSummary() {
                this.loadingAi = true;
                try {
                    const res = await fetch('{{ route('reports.ai.insight') }}');
                    const d = await res.json();
                    if (d.success) this.aiInsightText = d.insight;
                } catch(e) {} finally { this.loadingAi = false; }
            }
        };
    }
</script>
@endsection
