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
            <button @click="openStaffSalaryModal()"
                    class="px-4 py-2 rounded-xl text-xs font-extrabold flex items-center gap-1.5 border shadow-xs"
                    style="background:#FEF3C7; border-color:#FDE68A; color:#92400E;">
                <i data-lucide="banknote" class="w-4 h-4 text-amber-700"></i>
                <span>+ স্টাফ বেতন / দৈনিক মজুরি প্রদান</span>
            </button>
            <button @click="openExpenseModal = true; resetExpenseForm();"
                    class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ নতুন সাধারণ খরচ এন্ট্রি</span>
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

    <!-- Date & Staff Filter -->
    <div class="bg-white rounded-2xl p-3 border flex flex-col sm:flex-row items-center justify-between gap-3" style="border-color:#E8DDD9;">
        <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
            <input type="date" name="start_date" value="{{ $startDate }}" class="pos-input text-xs rounded-xl px-3 py-1.5">
            <span class="text-xs" style="color:#9B7A7E;">→</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="pos-input text-xs rounded-xl px-3 py-1.5">

            <!-- Staff Filter -->
            <select name="staff_id" class="pos-input text-xs rounded-xl px-3 py-1.5 font-bold">
                <option value="">-- সকল স্টাফ (All Staff) --</option>
                @foreach($staffList as $s)
                    <option value="{{ $s->id }}" {{ ($selectedStaffId ?? '') == $s->id ? 'selected' : '' }}>
                        👤 {{ $s->name }} ({{ ucfirst($s->role) }})
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn-maroon px-4 py-1.5 rounded-xl text-xs font-bold">ফিল্টার</button>
            @if(($selectedStaffId ?? null) || ($selectedCategoryId ?? null))
                <a href="{{ route('expenses.index') }}" class="px-3 py-1.5 text-xs font-bold text-gray-500 hover:text-gray-900">ফিল্টার রিমুভ</a>
            @endif
        </form>

        <button @click="openStaffLedger(selectedStaffId || (staffList[0] ? staffList[0].id : null))"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 border border-gray-300">
            <i data-lucide="file-text" class="w-4 h-4 text-maroon"></i>
            <span>📋 স্টাফ বেতন লেজার ও ভাউচার</span>
        </button>
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
                        <td class="px-4 py-3.5 font-bold" style="color:#1A0A0C;">
                            <div>{{ $e->title }}</div>
                            @if($e->staffUser)
                                <div class="inline-flex items-center gap-1 text-[10px] font-bold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-md mt-1 border border-amber-200">
                                    <i data-lucide="user" class="w-3 h-3 text-amber-600"></i>
                                    <span>{{ $e->staffUser->name }} ({{ ucfirst($e->staffUser->role) }})</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:#FEF3C7; color:#92400E;">
                                {{ $e->category->bangla_name ?? $e->category->name ?? 'সাধারণ' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 uppercase font-bold text-[10px]" style="color:#5C3840;">{{ $e->payment_method }}</td>
                        <td class="px-4 py-3.5 pos-nums font-black price-maroon">৳{{ number_format($e->amount, 2) }}</td>
                        <td class="px-4 py-3.5" style="color:#5C3840;">{{ $e->user->name ?? 'Staff' }}</td>
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                @if($e->staff_user_id)
                                    <button @click="openStaffLedger({{ $e->staff_user_id }})"
                                            title="স্টাফ বেতন স্টেটমেন্ট"
                                            class="px-2 py-1 rounded text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100">
                                        📋 লেজার
                                    </button>
                                @endif
                                <button @click="deleteExpense({{ $e->id }}, '{{ $e->title }}')" class="p-1 text-gray-400 hover:text-rose-600">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
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

    <!-- ════ MODAL 2: STAFF SALARY & WAGE PAYOUT ════ -->
    <div x-show="openSalaryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openSalaryModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #70101E, #92400E);">
                <div class="flex items-center gap-2 text-white">
                    <i data-lucide="banknote" class="w-5 h-5" style="color:#FDE68A;"></i>
                    <h3 class="text-sm font-bold">স্টাফ বেতন ও মজুরি পরিশোধ</h3>
                </div>
                <button @click="openSalaryModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 space-y-4">
                <!-- Select Staff Member -->
                <div>
                    <label class="section-heading">স্টাফ সদস্য নির্বাচন করুন *</label>
                    <select x-model="selectedStaffId" @change="onStaffSelect()" class="pos-input w-full px-3 py-2.5 text-xs font-bold rounded-xl">
                        <option value="">-- স্টাফ নির্বাচন করুন --</option>
                        @foreach($staffList as $st)
                            <option value="{{ $st->id }}" data-type="{{ $st->salary_type }}" data-salary="{{ $st->base_salary }}">
                                {{ $st->name }} ({{ ucfirst($st->role) }}) — {{ $st->salary_type === 'daily' ? 'দৈনিক ৳'.$st->base_salary : ($st->salary_type === 'weekly' ? 'সাপ্তাহিক ৳'.$st->base_salary : 'মাসিক ৳'.$st->base_salary) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Salary Period -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">পরিশোধের ধরন *</label>
                        <select x-model="expenseForm.salary_period" @change="updateSalaryTitle()" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                            <option value="daily">দৈনিক মজুরি (Daily Wage)</option>
                            <option value="weekly">সাপ্তাহিক বেতন (Weekly Salary)</option>
                            <option value="monthly">মাসিক বেতন (Monthly Salary)</option>
                            <option value="advance">অ্যাডভান্স / অগ্রিম (Advance)</option>
                        </select>
                    </div>
                    <div>
                        <label class="section-heading">টাকার পরিমাণ (৳) *</label>
                        <input type="number" step="0.01" x-model.number="expenseForm.amount" placeholder="500" class="pos-input w-full px-3 py-2 text-xs pos-nums font-black price-maroon rounded-xl">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">পেমেন্ট মাধ্যম</label>
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
                    <label class="section-heading">খরচের বিবরণ / টাইটেল *</label>
                    <input type="text" x-model="expenseForm.title" class="pos-input w-full px-3 py-2 text-xs font-bold rounded-xl">
                </div>

                <div>
                    <label class="section-heading">নোট (ঐচ্ছিক)</label>
                    <input type="text" x-model="expenseForm.notes" placeholder="উদাঃ ২৪শে আগস্টের দৈনিক হাজিরা মজুরি" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openSalaryModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveExpense()" class="btn-maroon px-6 py-2.5 text-xs font-bold">বেতন পরিশোধ সংরক্ষণ</button>
            </div>
        </div>
    </div>

    <!-- ════ MODAL 3: STAFF SALARY STATEMENT & PAYSLIP LEDGER ════ -->
    <div x-show="openLedgerModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-backdrop">
        <div @click.outside="openLedgerModal = false"
             class="w-full max-w-2xl bg-white rounded-3xl overflow-hidden shadow-2xl border flex flex-col max-h-[90vh]"
             style="border-color:#E0D4CF;">

            <!-- Header -->
            <div class="p-4 border-b flex items-center justify-between shrink-0" style="background: linear-gradient(135deg, #1A0A0C, #5C0F1B);">
                <div class="flex items-center gap-2.5 text-white">
                    <i data-lucide="file-spreadsheet" class="w-5 h-5" style="color:#FCD34D;"></i>
                    <h3 class="text-sm font-bold">স্টাফ বেতন ও মজুরি হিস্টোরি লেজার</h3>
                </div>
                <button @click="openLedgerModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <!-- Modal Content Body -->
            <div class="p-4 sm:p-5 space-y-4 overflow-y-auto flex-1">

                <!-- Select Staff Switcher -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-gray-50 p-3 rounded-2xl border border-gray-200">
                    <label class="text-xs font-bold text-gray-700">স্টাফ সদস্য নির্বাচন করুন:</label>
                    <select x-model="ledgerStaffId" @change="fetchStaffLedger(ledgerStaffId)" class="pos-input text-xs font-bold px-3 py-2 rounded-xl w-full sm:w-64">
                        @foreach($staffList as $s)
                            <option value="{{ $s->id }}">👤 {{ $s->name }} ({{ ucfirst($s->role) }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Loading State -->
                <div x-show="loadingLedger" class="py-12 text-center text-xs text-gray-500 font-bold">
                    <i data-lucide="loader-2" class="w-6 h-6 animate-spin mx-auto mb-2 text-maroon"></i>
                    স্টাফ বেতন হিস্টোরি লোড হচ্ছে...
                </div>

                <template x-if="!loadingLedger && ledgerData">
                    <div class="space-y-4">
                        <!-- Profile Card & Summary KPIs -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            <div class="bg-[#FBF8F5] p-3 rounded-xl border border-gray-200">
                                <p class="text-[10px] font-bold text-gray-500 uppercase">স্টাফ নাম & রোল</p>
                                <p class="text-xs font-black text-gray-900 truncate" x-text="ledgerData.user.name"></p>
                                <span class="text-[10px] font-bold text-amber-800 capitalize" x-text="ledgerData.user.role"></span>
                            </div>
                            <div class="bg-[#FBF8F5] p-3 rounded-xl border border-gray-200">
                                <p class="text-[10px] font-bold text-gray-500 uppercase">বেতন স্কেল</p>
                                <p class="text-xs font-black price-maroon pos-nums">৳ <span x-text="ledgerData.user.base_salary"></span></p>
                                <span class="text-[10px] font-bold text-gray-500 uppercase" x-text="ledgerData.user.salary_type"></span>
                            </div>
                            <div class="bg-[#FBF8F5] p-3 rounded-xl border border-gray-200">
                                <p class="text-[10px] font-bold text-gray-500 uppercase">চলতি মাসে প্রদান</p>
                                <p class="text-xs font-black text-emerald-700 pos-nums">৳ <span x-text="ledgerData.summary.this_month_paid"></span></p>
                                <span class="text-[10px] text-gray-500">চলতি মাস</span>
                            </div>
                            <div class="bg-[#FBF8F5] p-3 rounded-xl border border-gray-200">
                                <p class="text-[10px] font-bold text-gray-500 uppercase">সর্বমোট পরিশোধ</p>
                                <p class="text-xs font-black text-gray-900 pos-nums">৳ <span x-text="ledgerData.summary.total_paid"></span></p>
                                <span class="text-[10px] text-gray-500" x-text="ledgerData.summary.total_payout_count + ' বার প্রদান'"></span>
                            </div>
                        </div>

                        <!-- Statement Table -->
                        <div class="border rounded-2xl overflow-hidden shadow-2xs">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-100 border-b text-[10px] uppercase font-bold text-gray-600">
                                    <tr>
                                        <th class="px-3.5 py-2.5">তারিখ</th>
                                        <th class="px-3.5 py-2.5">বিবরণ / রসিদ #</th>
                                        <th class="px-3.5 py-2.5">ধরন</th>
                                        <th class="px-3.5 py-2.5">পদ্ধতি</th>
                                        <th class="px-3.5 py-2.5">পরিমাণ (৳)</th>
                                        <th class="px-3.5 py-2.5 text-right">পে-স্লিপ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="p in ledgerData.payouts" :key="p.id">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3.5 py-3 font-semibold text-gray-600 pos-nums" x-text="p.expense_date"></td>
                                            <td class="px-3.5 py-3">
                                                <div class="font-bold text-gray-900" x-text="p.title"></div>
                                                <span class="text-[10px] text-gray-400 pos-nums" x-text="p.receipt_number"></span>
                                            </td>
                                            <td class="px-3.5 py-3">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase"
                                                      :class="p.salary_period === 'advance' ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800'"
                                                      x-text="p.salary_period"></span>
                                            </td>
                                            <td class="px-3.5 py-3 font-bold text-gray-700" x-text="p.payment_method"></td>
                                            <td class="px-3.5 py-3 font-black text-rose-800 pos-nums">৳ <span x-text="p.amount"></span></td>
                                            <td class="px-3.5 py-3 text-right">
                                                <button @click="printPaySlip(p)"
                                                        class="px-2 py-1 rounded text-[11px] font-bold bg-gray-100 hover:bg-gray-200 text-gray-800 border flex items-center gap-1 ml-auto">
                                                    <i data-lucide="printer" class="w-3 h-3 text-gray-600"></i>
                                                    <span>পে-স্লিপ</span>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <template x-if="ledgerData.payouts.length === 0">
                                        <tr><td colspan="6" class="px-4 py-8 text-center text-xs text-gray-400">এই স্টাফের কোনো অতীত বেতন পরিশোধের ইতিহাস পাওয়া যায়নি।</td></tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <div class="p-3.5 bg-gray-50 border-t flex justify-end">
                <button @click="openLedgerModal = false" class="btn-outline px-4 py-1.5 rounded-xl text-xs font-bold">বন্ধ করুন</button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function expenseManager() {
    return {
        openExpenseModal: false,
        openSalaryModal: false,
        openLedgerModal: false,
        loadingLedger: false,
        ledgerStaffId: null,
        ledgerData: null,

        selectedStaffId: '{{ $selectedStaffId ?? '' }}',
        staffList: @json($staffList),
        salaryCategoryId: '{{ $categories->firstWhere('name', 'Staff Salary & Wages')?->id ?? ($categories->first()?->id ?? 1) }}',

        expenseForm: {
            id: null,
            title: '',
            category_id: '',
            staff_user_id: null,
            salary_period: 'daily',
            amount: 0,
            payment_method: 'cash',
            expense_date: '{{ now()->toDateString() }}',
            notes: ''
        },

        init() { this.$nextTick(() => window.initLucideIcons()); },

        resetExpenseForm() {
            this.selectedStaffId = '';
            this.expenseForm = {
                id: null,
                title: '',
                category_id: this.categories && this.categories.length > 0 ? this.categories[0].id : '',
                staff_user_id: null,
                salary_period: null,
                amount: 0,
                payment_method: 'cash',
                expense_date: '{{ now()->toDateString() }}',
                notes: ''
            };
        },

        openStaffSalaryModal() {
            this.resetExpenseForm();
            this.expenseForm.category_id = this.salaryCategoryId;
            this.openSalaryModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        async openStaffLedger(staffId) {
            if (!staffId && this.staffList.length > 0) staffId = this.staffList[0].id;
            if (!staffId) { alert('কোনো স্টাফ নির্বাচন করা হয়নি!'); return; }
            this.ledgerStaffId = staffId;
            this.openLedgerModal = true;
            await this.fetchStaffLedger(staffId);
        },

        async fetchStaffLedger(staffId) {
            this.loadingLedger = true;
            this.ledgerData = null;
            try {
                const res = await fetch(`/expenses/staff-ledger/${staffId}`);
                const data = await res.json();
                if (data.success) {
                    this.ledgerData = data;
                }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
            finally {
                this.loadingLedger = false;
                this.$nextTick(() => window.initLucideIcons());
            }
        },

        printPaySlip(p) {
            const u = this.ledgerData ? this.ledgerData.user : { name: 'Staff', role: 'Employee', phone: '' };
            const html = `
                <html>
                <head>
                    <title>Staff Salary Pay Slip - ${p.receipt_number}</title>
                    <style>
                        body { font-family: sans-serif; padding: 20px; max-width: 400px; margin: 0 auto; color: #111; }
                        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px; margin-bottom: 15px; }
                        .title { font-size: 18px; font-weight: bold; }
                        .sub { font-size: 12px; color: #555; }
                        .row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px; }
                        .box { background: #f9f9f9; border: 1px solid #ddd; padding: 12px; border-radius: 8px; margin: 15px 0; }
                        .amount { font-size: 22px; font-weight: bold; color: #000; text-align: center; margin-top: 5px; }
                        .sign { display: flex; justify-content: space-between; margin-top: 50px; font-size: 11px; text-align: center; }
                        .sign-line { border-top: 1px solid #000; width: 120px; padding-top: 5px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="title">Sultan's POS - SALARY PAY SLIP</div>
                        <div class="sub">Official Employee Payment Receipt</div>
                        <div class="sub">Receipt Ref: <b>${p.receipt_number}</b></div>
                    </div>
                    <div class="row"><span>Date:</span><b>${p.expense_date}</b></div>
                    <div class="row"><span>Staff Name:</span><b>${u.name}</b></div>
                    <div class="row"><span>Designation:</span><b>${u.role.toUpperCase()}</b></div>
                    <div class="row"><span>Mobile:</span><b>${u.phone || 'N/A'}</b></div>
                    <div class="box">
                        <div class="row"><span>Payment Description:</span><b>${p.title}</b></div>
                        <div class="row"><span>Payment Method:</span><b>${p.payment_method}</b></div>
                        <div class="row"><span>Salary Period:</span><b>${p.salary_period.toUpperCase()}</b></div>
                        <div class="amount">৳ ${p.amount.toFixed(2)}</div>
                    </div>
                    <div class="row"><span>Issued By:</span><b>${p.issued_by}</b></div>
                    <div class="sign">
                        <div class="sign-line">Employee Signature</div>
                        <div class="sign-line">Authorized Manager</div>
                    </div>
                    <script>window.onload = function() { window.print(); window.close(); }<\/script>
                </body>
                </html>
            `;
            const win = window.open('', '_blank', 'width=450,height=600');
            win.document.write(html);
            win.document.close();
        },

        onStaffSelect() {
            const st = this.staffList.find(x => x.id == this.selectedStaffId);
            if (st) {
                this.expenseForm.staff_user_id = st.id;
                this.expenseForm.salary_period = st.salary_type || 'daily';
                this.expenseForm.amount = parseFloat(st.base_salary) || 0;
                this.updateSalaryTitle();
            }
        },

        updateSalaryTitle() {
            const st = this.staffList.find(x => x.id == this.selectedStaffId);
            const periodNames = { daily: 'দৈনিক মজুরি', weekly: 'সাপ্তাহিক বেতন', monthly: 'মাসিক বেতন', advance: 'অ্যাডভান্স' };
            const p = periodNames[this.expenseForm.salary_period] || 'বেতন';
            this.expenseForm.title = st ? `${st.name} (${st.role}) - ${p}` : `স্টাফ ${p}`;
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
