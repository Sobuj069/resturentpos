@extends('layouts.app')
@section('title', 'দৈনিক খরচ ও লাভ-ক্ষতি (P&L)')
@section('content')
<div x-data="expenseManager()" x-init="init()" class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(139,26,44,0.1); border:1.5px solid rgba(139,26,44,0.25);">
                    <i data-lucide="calculator" class="w-5 h-5" style="color:#8B1A2C;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">দৈনিক খরচ ও লাভ-ক্ষতি (P&L)</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">দোকানের পেটি ক্যাশ, কাঁচাবাজার খরচ ও রিয়েলটাইম নেট প্রফিট হিসাব</p>
        </div>

        <div class="flex items-center gap-2">
            <button @click="openExpenseModal = true; resetExpenseForm();"
                    class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ নতুন খরচ এন্ট্রি</span>
            </button>
        </div>
    </div>

    <!-- P&L Financial Cards (Gross Sales, COGS, Expenses, Net Profit) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#8B1A2C;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">মোট বিক্রয় রাজস্ব</p>
            <p class="text-xl font-black pos-nums price-maroon">৳{{ number_format($totalSales, 2) }}</p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#B8922A;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">খাবার প্রস্তুত খরচ (COGS)</p>
            <p class="text-xl font-black pos-nums" style="color:#B8922A;">- ৳{{ number_format($totalCogs, 2) }}</p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#C02020;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">দৈনিক দোকান খরচ</p>
            <p class="text-xl font-black pos-nums" style="color:#C02020;">- ৳{{ number_format($totalExpenses, 2) }}</p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:{{ $netProfit >= 0 ? '#2E7D52' : '#C02020' }};"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">রিয়েলটাইম নেট লাভ (Net Profit)</p>
            <p class="text-xl font-black pos-nums" style="color:{{ $netProfit >= 0 ? '#2E7D52' : '#C02020' }};">
                ৳{{ number_format($netProfit, 2) }}
            </p>
            <p class="text-[10px] font-bold mt-1" style="color:#9B7A7E;">মার্জিন: {{ number_format($netProfitMargin, 1) }}%</p>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-2xl p-3 border flex flex-col sm:flex-row items-center justify-between gap-3" style="border-color:#E8DDD9;">
        <form method="GET" action="{{ route('expenses.index') }}" class="flex items-center gap-2">
            <input type="date" name="start_date" value="{{ $startDate }}" class="pos-input text-xs rounded-xl px-3 py-1.5">
            <span class="text-xs" style="color:#9B7A7E;">→</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="pos-input text-xs rounded-xl px-3 py-1.5">
            <button type="submit" class="btn-maroon px-4 py-1.5 rounded-xl text-xs font-bold">ফিল্টার</button>
        </form>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                    <tr>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">তারিখ</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">খরচের বিবরণ</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">ক্যাটাগরি</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">পদ্ধতি</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">পরিমাণ (৳)</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">এন্ট্রি করেছে</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px] text-right" style="color:#9B7A7E;">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $e)
                    <tr class="data-row border-b" style="border-color:#F0E8E5;">
                        <td class="px-4 py-3.5 pos-nums text-[11px]" style="color:#9B7A7E;">{{ $e->expense_date->format('d M, Y') }}</td>
                        <td class="px-4 py-3.5 font-bold" style="color:#1A0A0C;">{{ $e->title }}</td>
                        <td class="px-4 py-3.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:#FEF3C7; color:#92400E;">
                                {{ $e->category->bangla_name ?? $e->category->name ?? 'সাধারণ' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 uppercase font-bold text-[10px]" style="color:#5C3840;">{{ $e->payment_method }}</td>
                        <td class="px-4 py-3.5 pos-nums font-black price-maroon">৳{{ number_format($e->amount, 2) }}</td>
                        <td class="px-4 py-3.5" style="color:#5C3840;">{{ $e->user->name ?? 'Staff' }}</td>
                        <td class="px-4 py-3.5 text-right">
                            <button @click="deleteExpense({{ $e->id }}, '{{ $e->title }}')" class="p-1 text-gray-400 hover:text-rose-600">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center" style="color:#9B7A7E;">কোনো খরচের রেকর্ড পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t" style="border-color:#E8DDD9; background:#FBF8F5;">
            {{ $expenses->links() }}
        </div>
    </div>

    <!-- ════ MODAL: ADD EXPENSE ════ -->
    <div x-show="openExpenseModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openExpenseModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white">দৈনিক খরচ এন্ট্রি</h3>
                <button @click="openExpenseModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <label class="section-heading">খরচের বিবরণ / টাইটেল *</label>
                    <input type="text" x-model="expenseForm.title" placeholder="উদাঃ সকালের কাঁচাবাজার ও মুরগি" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">খরচের ক্যাটাগরি *</label>
                        <select x-model="expenseForm.category_id" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                            <option value="">বেছে নিন...</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->bangla_name ?? $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="section-heading">টাকার পরিমাণ (৳) *</label>
                        <input type="number" step="0.01" x-model.number="expenseForm.amount" placeholder="1500" class="pos-input w-full px-3 py-2 text-xs pos-nums font-black price-maroon rounded-xl">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">পেমেন্ট মেথড</label>
                        <select x-model="expenseForm.payment_method" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                            <option value="cash">ক্যাশ (Cash)</option>
                            <option value="bkash">বিকাশ (bKash)</option>
                            <option value="nagad">নগদ (Nagad)</option>
                            <option value="bank">ব্যাংক ট্রান্সফার</option>
                        </select>
                    </div>
                    <div>
                        <label class="section-heading">তারিখ *</label>
                        <input type="date" x-model="expenseForm.expense_date" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                    </div>
                </div>
                <div>
                    <label class="section-heading">মন্তব্য / নোট (ঐচ্ছিক)</label>
                    <input type="text" x-model="expenseForm.notes" placeholder="উদাঃ রশিদ নম্বর #৯৯৪" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
            </div>
            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openExpenseModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveExpense()" class="btn-maroon px-6 py-2.5 text-xs font-bold">খরচ সংরক্ষণ</button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function expenseManager() {
    return {
        openExpenseModal: false,
        expenseForm: { title: '', category_id: '', amount: 0, payment_method: 'cash', expense_date: '{{ now()->toDateString() }}', notes: '' },

        init() { this.$nextTick(() => window.initLucideIcons()); },

        resetExpenseForm() {
            this.expenseForm = { title: '', category_id: '', amount: 0, payment_method: 'cash', expense_date: '{{ now()->toDateString() }}', notes: '' };
        },

        async saveExpense() {
            if (!this.expenseForm.title || !this.expenseForm.category_id || this.expenseForm.amount <= 0) {
                alert('অনুগ্রহ করে বিবরণ, ক্যাটাগরি এবং সঠিক পরিমাণ দিন!'); return;
            }
            try {
                const res = await fetch('{{ route('expenses.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.expenseForm)
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async deleteExpense(id, title) {
            if (!confirm(`আপনি কি "${title}" খরচের রেকর্ডটি মুছে ফেলতে চান?`)) return;
            try {
                const res = await fetch(`/expenses/${id}`, {
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
