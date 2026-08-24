@extends('layouts.app')
@section('title', 'ক্যাশিয়ার শিফট ওপেনিং ও ক্লোজিং অডিট রিপোর্ট')
@section('content')
<div x-data="shiftReportViewer()" x-init="init()" class="min-h-full p-4 sm:p-5 lg:p-6 space-y-4 sm:space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(139,26,44,0.1); border:1.5px solid rgba(139,26,44,0.25);">
                    <i data-lucide="history" class="w-5 h-5" style="color:#8B1A2C;"></i>
                </div>
                <h1 class="text-base sm:text-lg font-extrabold text-gray-900">ক্যাশিয়ার শিফট অডিট ও ক্যাশ রিপোর্ট</h1>
            </div>
            <p class="text-xs text-gray-500">কোন ক্যাশিয়ার কোন তারিখে কখন শিফট শুরু/শেষ করেছে, কত টাকা ক্যাশ ছিল ও ড্রয়ার গরমিল হিসাব</p>
        </div>
    </div>

    <!-- KPI Summary Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="stat-card rounded-2xl p-4 bg-white border border-gray-200 shadow-2xs">
            <div class="card-accent" style="background:#8B1A2C;"></div>
            <p class="text-[11px] font-bold text-gray-500 mb-1">মোট শিফট সংখ্যা</p>
            <p class="text-2xl font-black pos-nums price-maroon">{{ $totalShifts }} টি</p>
        </div>
        <div class="stat-card rounded-2xl p-4 bg-white border border-gray-200 shadow-2xs">
            <div class="card-accent" style="background:#B8922A;"></div>
            <p class="text-[11px] font-bold text-gray-500 mb-1">মোট ওপেনিং ক্যাশ</p>
            <p class="text-2xl font-black pos-nums" style="color:#B8922A;">৳{{ number_format($totalOpeningFloat, 0) }}</p>
        </div>
        <div class="stat-card rounded-2xl p-4 bg-white border border-gray-200 shadow-2xs">
            <div class="card-accent" style="background:#2E7D52;"></div>
            <p class="text-[11px] font-bold text-gray-500 mb-1">শিফটের ক্যাশ বিক্রয়</p>
            <p class="text-2xl font-black pos-nums" style="color:#2E7D52;">৳{{ number_format($totalCashSales, 0) }}</p>
        </div>
        <div class="stat-card rounded-2xl p-4 bg-white border border-gray-200 shadow-2xs">
            <div class="card-accent" style="background:{{ $totalVariance < 0 ? '#C02020' : '#2E7D52' }};"></div>
            <p class="text-[11px] font-bold text-gray-500 mb-1">মোট ড্রয়ার গরমিল (Variance)</p>
            <p class="text-2xl font-black pos-nums" style="color:{{ $totalVariance < 0 ? '#C02020' : '#2E7D52' }};">
                ৳{{ number_format($totalVariance, 0) }}
            </p>
        </div>
    </div>

    <!-- Date & Cashier Filter Bar -->
    <div class="bg-white rounded-2xl p-3 border border-gray-200 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="GET" action="{{ route('reports.shifts') }}" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <input type="date" name="start_date" value="{{ $startDate }}" class="pos-input text-xs rounded-xl px-3 py-1.5 font-bold">
            <span class="text-xs text-gray-400">→</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="pos-input text-xs rounded-xl px-3 py-1.5 font-bold">

            <!-- Cashier Select -->
            <select name="user_id" class="pos-input text-xs rounded-xl px-3 py-1.5 font-bold">
                <option value="">-- সকল ক্যাশিয়ার (All Staff) --</option>
                @foreach($cashiers as $c)
                    <option value="{{ $c->id }}" {{ ($userId ?? '') == $c->id ? 'selected' : '' }}>
                        👤 {{ $c->name }} ({{ ucfirst($c->role) }})
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn-maroon px-4 py-1.5 rounded-xl text-xs font-bold">ফিল্টার করুন</button>
            @if(($userId ?? null))
                <a href="{{ route('reports.shifts') }}" class="px-3 py-1.5 text-xs font-bold text-gray-500 hover:text-gray-900">ফিল্টার সরান</a>
            @endif
        </form>
    </div>

    <!-- Shift Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-extrabold uppercase text-[10px] text-gray-500">শিফট ID</th>
                        <th class="px-4 py-3 font-extrabold uppercase text-[10px] text-gray-500">ক্যাশিয়ার</th>
                        <th class="px-4 py-3 font-extrabold uppercase text-[10px] text-gray-500">শুরুর সময় (Opening)</th>
                        <th class="px-4 py-3 font-extrabold uppercase text-[10px] text-gray-500">সমাপ্তির সময় (Closing)</th>
                        <th class="px-4 py-3 font-extrabold uppercase text-[10px] text-gray-500">ওপেনিং ক্যাশ</th>
                        <th class="px-4 py-3 font-extrabold uppercase text-[10px] text-gray-500">শিফট ক্যাশ সেলস</th>
                        <th class="px-4 py-3 font-extrabold uppercase text-[10px] text-gray-500">ড্রয়ারে গুনে পাওয়া ক্যাশ</th>
                        <th class="px-4 py-3 font-extrabold uppercase text-[10px] text-gray-500">ড্রয়ার ভ্যারিয়েন্স (গরমিল)</th>
                        <th class="px-4 py-3 font-extrabold uppercase text-[10px] text-gray-500">স্ট্যাটাস</th>
                        <th class="px-4 py-3 font-extrabold uppercase text-[10px] text-gray-500 text-right">অডিট স্লিপ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($shifts as $s)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3.5 pos-nums font-extrabold text-gray-900">#SH-{{ $s->id }}</td>
                        <td class="px-4 py-3.5">
                            <p class="font-bold text-gray-900">{{ $s->user->name ?? 'N/A' }}</p>
                            <p class="text-[10px] text-gray-400 capitalize">{{ $s->user->role ?? 'cashier' }}</p>
                        </td>
                        <td class="px-4 py-3.5 pos-nums text-gray-600">
                            {{ $s->opened_at ? \Carbon\Carbon::parse($s->opened_at)->format('d M, Y — h:i A') : '—' }}
                        </td>
                        <td class="px-4 py-3.5 pos-nums text-gray-600">
                            @if($s->closed_at)
                                {{ \Carbon\Carbon::parse($s->closed_at)->format('d M, Y — h:i A') }}
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 animate-pulse border border-emerald-200">
                                    ● চলমান শিফট
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 pos-nums font-bold text-gray-900">৳{{ number_format($s->opening_float, 2) }}</td>
                        <td class="px-4 py-3.5 pos-nums font-black text-emerald-700">৳{{ number_format($s->cash_sales, 2) }}</td>
                        <td class="px-4 py-3.5 pos-nums font-bold text-gray-900">
                            {{ $s->actual_cash_counted !== null ? '৳'.number_format($s->actual_cash_counted, 2) : '—' }}
                        </td>
                        <td class="px-4 py-3.5 pos-nums font-black" style="color:{{ $s->cash_difference < 0 ? '#C02020' : ($s->cash_difference > 0 ? '#16A34A' : '#4B5563') }};">
                            @if($s->cash_difference !== null)
                                {{ $s->cash_difference > 0 ? '+' : '' }}৳{{ number_format($s->cash_difference, 2) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            @if($s->status === 'open')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">ওপেন</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-gray-100 text-gray-700">ক্লোজড</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button @click="printShiftTicket({{ json_encode($s) }})"
                                    class="px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300 flex items-center gap-1 ml-auto">
                                <i data-lucide="printer" class="w-3.5 h-3.5 text-gray-600"></i>
                                <span>প্রিন্ট অডিট স্লিপ</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="px-4 py-12 text-center text-xs text-gray-400 font-bold">কোনো শিফটের রেকর্ড পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3.5 border-t border-gray-200 bg-gray-50">
            {{ $shifts->links() }}
        </div>
    </div>

</div>

@push('scripts')
<script>
function shiftReportViewer() {
    return {
        init() { this.$nextTick(() => window.initLucideIcons()); },

        printShiftTicket(s) {
            const openTime = s.opened_at ? new Date(s.opened_at).toLocaleString('en-GB') : 'N/A';
            const closeTime = s.closed_at ? new Date(s.closed_at).toLocaleString('en-GB') : 'RUNNING';
            const html = `
                <html>
                <head>
                    <title>Shift Audit Ticket #SH-${s.id}</title>
                    <style>
                        body { font-family: monospace; padding: 15px; max-width: 320px; margin: 0 auto; color: #000; font-size: 11px; }
                        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 8px; margin-bottom: 10px; }
                        .title { font-size: 16px; font-weight: bold; }
                        .row { display: flex; justify-content: space-between; margin-bottom: 5px; }
                        .line { border-bottom: 1px dashed #000; margin: 8px 0; }
                        .total-box { background: #f0f0f0; padding: 8px; font-weight: bold; margin: 10px 0; border: 1px solid #ccc; }
                        .sign { display: flex; justify-content: space-between; margin-top: 40px; font-size: 10px; }
                        .sign-line { border-top: 1px solid #000; width: 100px; text-align: center; padding-top: 3px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="title">SHIFT AUDIT REPORT</div>
                        <div>Shift Token: #SH-${s.id}</div>
                    </div>
                    <div class="row"><span>Cashier:</span><b>${s.user ? s.user.name : 'N/A'}</b></div>
                    <div class="row"><span>Opened At:</span><b>${openTime}</b></div>
                    <div class="row"><span>Closed At:</span><b>${closeTime}</b></div>
                    <div class="line"></div>
                    <div class="row"><span>Opening Float Cash:</span><b>৳ ${parseFloat(s.opening_float || 0).toFixed(2)}</b></div>
                    <div class="row"><span>Shift Cash Sales:</span><b>৳ ${parseFloat(s.cash_sales || 0).toFixed(2)}</b></div>
                    <div class="row"><span>Expected Drawer Cash:</span><b>৳ ${parseFloat(s.expected_cash || 0).toFixed(2)}</b></div>
                    <div class="line"></div>
                    <div class="row"><span>Actual Cash Counted:</span><b>৳ ${parseFloat(s.actual_cash_counted || 0).toFixed(2)}</b></div>
                    <div class="total-box">
                        <div class="row"><span>Cash Difference / Variance:</span><b>৳ ${parseFloat(s.cash_difference || 0).toFixed(2)}</b></div>
                    </div>
                    <div class="row"><span>Notes:</span><b>${s.closing_note || 'None'}</b></div>
                    <div class="sign">
                        <div class="sign-line">Cashier Signature</div>
                        <div class="sign-line">Manager Sign</div>
                    </div>
                    <script>window.onload = function() { window.print(); window.close(); }<\/script>
                </body>
                </html>
            `;
            const win = window.open('', '_blank', 'width=380,height=550');
            win.document.write(html);
            win.document.close();
        }
    };
}
</script>
@endpush
@endsection
