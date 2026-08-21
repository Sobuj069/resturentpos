@extends('layouts.app')
@section('title', 'ইনভেন্টরি ও কাঁচামাল BOM')
@section('content')
<div x-data="inventoryManager()" x-init="init()" class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(139,26,44,0.1); border:1.5px solid rgba(139,26,44,0.25);">
                    <i data-lucide="boxes" class="w-5 h-5" style="color:#8B1A2C;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">ইনভেন্টরি ও Recipe BOM</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">কাঁচামাল স্টক ট্র্যাকিং, বিল অফ ম্যাটেরিয়েলস (BOM) ও অডিট লগ</p>
        </div>

        <div class="flex items-center gap-2">
            <button @click="openIngredientModal = true; resetIngredientForm();"
                    class="btn-outline px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ নতুন কাঁচামাল</span>
            </button>
            <button @click="openWastageModal = true; resetWastageForm();"
                    class="px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 border"
                    style="background:#FFF5F5; color:#C02020; border-color:#FCA5A5;">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                <span>ওয়েস্টেজ এন্ট্রি</span>
            </button>
            <button @click="openPurchaseModal = true; resetPurchaseForm();"
                    class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                <span>+ স্টক ক্রয় (Stock In)</span>
            </button>
        </div>
    </div>

    <!-- KPI Stats Row -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @php
            $kpis = [
                ['label' => 'মোট কাঁচামাল উপাদান', 'value' => $ingredients->count(), 'unit' => 'টি', 'icon' => 'layers', 'color' => '#8B1A2C', 'bg' => '#FBF1F3', 'border' => 'rgba(139,26,44,0.2)'],
                ['label' => 'মোট বর্তমান স্টক মূল্য', 'value' => '৳'.number_format($totalStockValue, 2), 'unit' => '', 'icon' => 'circle-dollar-sign', 'color' => '#2E7D52', 'bg' => '#D1FAE5', 'border' => '#A7F3D0'],
                ['label' => 'লো-স্টক সতর্কতা', 'value' => $lowStockCount, 'unit' => 'টি', 'icon' => 'alert-triangle', 'color' => $lowStockCount > 0 ? '#C02020' : '#2E7D52', 'bg' => $lowStockCount > 0 ? '#FEE2E2' : '#D1FAE5', 'border' => $lowStockCount > 0 ? '#FCA5A5' : '#A7F3D0'],
                ['label' => 'BOM যুক্ত মেনু', 'value' => $items->filter(fn($i) => $i->recipes && $i->recipes->count() > 0)->count(), 'unit' => 'টি', 'icon' => 'book-open', 'color' => '#B8922A', 'bg' => '#FEF3C7', 'border' => '#FCD34D'],
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="stat-card rounded-2xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                 style="background:{{ $kpi['bg'] }}; border: 1px solid {{ $kpi['border'] }};">
                <i data-lucide="{{ $kpi['icon'] }}" class="w-5 h-5" style="color:{{ $kpi['color'] }};"></i>
            </div>
            <div>
                <p class="text-[11px] mb-0.5" style="color:#9B7A7E;">{{ $kpi['label'] }}</p>
                <p class="text-xl font-black pos-nums leading-none" style="color:{{ $kpi['color'] }};">
                    {{ $kpi['value'] }}<span class="text-xs font-normal ml-1" style="color:#9B7A7E;">{{ $kpi['unit'] }}</span>
                </p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-1 p-1 rounded-2xl bg-white border w-fit" style="border-color:#E8DDD9;">
        @foreach([['key'=>'ingredients','icon'=>'layers','label'=>'কাঁচামাল স্টক তালিকা'],['key'=>'recipes','icon'=>'chef-hat','label'=>'রেসিপি BOM ম্যাট্রিক্স'],['key'=>'logs','icon'=>'history','label'=>'স্টক অডিট লগ'],['key'=>'wastage','icon'=>'trash','label'=>'ওয়েস্টেজ লগ']] as $tab)
        <button @click="activeTab = '{{ $tab['key'] }}'"
                class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all"
                :style="activeTab === '{{ $tab['key'] }}' ? 'background:#8B1A2C; color:#fff;' : 'color:#5C3840; background:transparent;'">
            <i data-lucide="{{ $tab['icon'] }}" class="w-4 h-4"></i>
            <span>{{ $tab['label'] }}</span>
        </button>
        @endforeach
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TAB 1: INGREDIENTS TABLE                            -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'ingredients'" class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                    <tr>
                        <th class="px-3 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">ছবি</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">উপাদান নাম</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">একক</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">বর্তমান স্টক</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">সতর্কতা লেভেল</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">একক ক্রয়মূল্য</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">মোট স্টক মূল্য</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">স্ট্যাটাস</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px] text-right" style="color:#9B7A7E;">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ingredients as $ing)
                    <tr class="data-row border-b" style="border-color:#F0E8E5;">
                        <td class="px-3 py-2.5" style="width: 60px; min-width: 60px;">
                            <div class="rounded-xl overflow-hidden bg-[#F8F5F2] border border-[#E0D4CF] flex items-center justify-center shrink-0 shadow-2xs"
                                 style="width: 48px; height: 48px; min-width: 48px; min-height: 48px;">
                                @if($ing->image)
                                    <img src="{{ $ing->image }}" alt="{{ $ing->name }}" style="width: 48px; height: 48px; object-fit: cover; display: block;">
                                @else
                                    <i data-lucide="package" class="w-4 h-4 opacity-35" style="color:#8B1A2C;"></i>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <p class="font-bold" style="color:#1A0A0C;">{{ $ing->name }}</p>
                            @if($ing->bangla_name)
                            <p class="text-[11px]" style="color:#9B7A7E;">{{ $ing->bangla_name }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase pos-nums" style="background:#F0E8E5; color:#5C3840;">
                                {{ $ing->unit }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="font-black pos-nums text-sm" style="color:#1A0A0C;">{{ number_format($ing->current_stock, 2) }}</span>
                            <span class="text-[11px] ml-1" style="color:#9B7A7E;">{{ $ing->unit }}</span>
                        </td>
                        <td class="px-4 py-3.5 pos-nums" style="color:#9B7A7E;">{{ number_format($ing->alert_stock, 2) }} {{ $ing->unit }}</td>
                        <td class="px-4 py-3.5 pos-nums font-bold" style="color:#1A0A0C;">৳{{ number_format($ing->cost_per_unit, 2) }}</td>
                        <td class="px-4 py-3.5 pos-nums font-black price-maroon">৳{{ number_format($ing->current_stock * $ing->cost_per_unit, 2) }}</td>
                        <td class="px-4 py-3.5">
                            @if($ing->isLowStock())
                                <span class="badge-unpaid px-2 py-0.5 rounded-full text-[10px] font-bold">⚠ লো স্টক</span>
                            @else
                                <span class="badge-paid px-2 py-0.5 rounded-full text-[10px] font-bold">✓ পর্যাপ্ত</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="quickPurchaseStock({{ $ing->id }}, '{{ $ing->name }}', '{{ $ing->unit }}', {{ $ing->cost_per_unit }})"
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                        style="background:#D1FAE5; color:#065F46; border:1px solid #A7F3D0;">
                                    + ক্রয়
                                </button>
                                <button @click="editIngredient({{ json_encode($ing) }})"
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                        style="background:#FBF1F3; color:#8B1A2C; border:1px solid rgba(139,26,44,0.25);">
                                    এডিট
                                </button>
                                <button @click="deleteIngredient({{ $ing->id }}, '{{ $ing->name }}')"
                                        class="p-1 text-gray-400 hover:text-rose-600">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TAB 2: RECIPES BOM                                  -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'recipes'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($items as $item)
        <div class="pos-card p-4 flex flex-col justify-between">
            <div>
                <div class="flex items-start gap-3 mb-3">
                    <div class="rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-black/5 flex items-center justify-center"
                         style="width: 48px; height: 48px; min-width: 48px; min-height: 48px;">
                        @if($item->image)
                            <img src="{{ $item->image }}" alt="{{ $item->name }}" style="width: 48px; height: 48px; object-fit: cover; display: block;">
                        @else
                            <i data-lucide="utensils" class="w-5 h-5 opacity-35" style="color:#8B1A2C;"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <h4 class="font-bold text-sm truncate" style="color:#1A0A0C;">{{ $item->name }}</h4>
                            <span class="pos-nums font-black text-sm price-maroon shrink-0 ml-1">৳{{ number_format($item->selling_price, 2) }}</span>
                        </div>
                        <p class="text-[11px] truncate" style="color:#9B7A7E;">{{ $item->bangla_name }}</p>
                    </div>
                </div>

                <div class="border-t pt-3 space-y-1.5" style="border-color:#F0E8E5;">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-bold uppercase tracking-wider" style="color:#9B7A7E;">কাঁচামাল উপাদান (BOM)</p>
                        <button @click="openRecipeModalFor({{ json_encode($item) }})" class="text-xs font-bold hover:underline" style="color:#8B1A2C;">
                            ম্যানেজ রেসিপি
                        </button>
                    </div>
                    @forelse($item->recipes as $rec)
                    <div class="flex items-center justify-between text-xs py-1">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded-md overflow-hidden bg-gray-100 shrink-0 border border-black/5 flex items-center justify-center">
                                @if($rec->ingredient && $rec->ingredient->image)
                                    <img src="{{ $rec->ingredient->image }}" alt="{{ $rec->ingredient->name }}" class="w-full h-full object-cover">
                                @else
                                    <i data-lucide="package" class="w-3 h-3 opacity-40 text-[#8B1A2C]"></i>
                                @endif
                            </div>
                            <span style="color:#5C3840;">{{ $rec->ingredient->name ?? '—' }}</span>
                        </div>
                        <span class="pos-nums font-bold" style="color:#2E7D52;">{{ $rec->quantity_required }} {{ $rec->ingredient->unit ?? '' }}</span>
                    </div>
                    @empty
                    <p class="text-xs italic py-2" style="color:#C0A0A4;">রেসিপি BOM যুক্ত হয়নি (অটো-ডিডাকশন বন্ধ)</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TAB 3: AUDIT LOGS                                   -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'logs'" class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
        <table class="w-full text-left text-xs">
            <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                <tr>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">সময়</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">উপাদান</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">ধরণ</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">পরিবর্তন</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">ব্যালেন্স</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">বিবরণ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockLogs as $log)
                <tr class="data-row border-b" style="border-color:#F0E8E5;">
                    <td class="px-4 py-3 pos-nums text-[11px]" style="color:#9B7A7E;">{{ $log->created_at->format('d/m/y h:i A') }}</td>
                    <td class="px-4 py-3 font-bold" style="color:#1A0A0C;">{{ $log->ingredient->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
                        @if($log->type === 'order_deduction')
                            <span class="badge-cooking px-2 py-0.5 rounded-full text-[10px] font-bold">অর্ডার কর্তন</span>
                        @elseif($log->type === 'purchase')
                            <span class="badge-paid px-2 py-0.5 rounded-full text-[10px] font-bold">+ ক্রয়</span>
                        @else
                            <span class="badge-unpaid px-2 py-0.5 rounded-full text-[10px] font-bold">{{ $log->type }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 pos-nums font-black" style="color:{{ $log->quantity_change < 0 ? '#C02020' : '#2E7D52' }};">
                        {{ $log->quantity_change > 0 ? '+' : '' }}{{ $log->quantity_change }}
                    </td>
                    <td class="px-4 py-3 pos-nums font-black" style="color:#1A0A0C;">{{ $log->balance_after }}</td>
                    <td class="px-4 py-3" style="color:#5C3840;">{{ $log->notes }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center" style="color:#9B7A7E;">কোনো স্টক অডিট লগ পাওয়া যায়নি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TAB 4: WASTAGE LOGS                                 -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'wastage'" class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
        <table class="w-full text-left text-xs">
            <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                <tr>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">সময়</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">উপাদান / আইটেম</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">পরিমাণ</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">কারণ</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">আর্থিক ক্ষতি</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">এন্ট্রি করেছে</th>
                </tr>
            </thead>
            <tbody>
                @forelse($wastages as $w)
                <tr class="data-row border-b" style="border-color:#F0E8E5;">
                    <td class="px-4 py-3 pos-nums text-[11px]" style="color:#9B7A7E;">{{ $w->created_at->format('d/m/y h:i A') }}</td>
                    <td class="px-4 py-3 font-bold" style="color:#1A0A0C;">{{ $w->ingredient->name ?? $w->item->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 pos-nums font-bold" style="color:#C02020;">{{ $w->quantity }} {{ $w->unit }}</td>
                    <td class="px-4 py-3">
                        <span class="badge-unpaid px-2 py-0.5 rounded-full text-[10px] font-bold">{{ $w->reason }}</span>
                    </td>
                    <td class="px-4 py-3 pos-nums font-black price-maroon">৳{{ number_format($w->cost_impact, 2) }}</td>
                    <td class="px-4 py-3" style="color:#5C3840;">{{ $w->user->name ?? 'Staff' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center" style="color:#9B7A7E;">কোনো ওয়েস্টেজ রেকর্ড নেই।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: ADD / EDIT INGREDIENT                        -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openIngredientModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openIngredientModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white" x-text="ingredientForm.id ? 'কাঁচামাল উপাদান এডিট' : 'নতুন কাঁচামাল যুক্ত করুন'"></h3>
                <button @click="openIngredientModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 space-y-3">
                <div>
                    <label class="section-heading">উপাদান নাম (English) *</label>
                    <input type="text" x-model="ingredientForm.name" placeholder="e.g. Basmati Rice, Pure Ghee" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                </div>
                <div>
                    <label class="section-heading">বাংলা নাম</label>
                    <input type="text" x-model="ingredientForm.bangla_name" placeholder="উদাঃ বাসমতি চাল" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">পরিমাপের একক *</label>
                        <select x-model="ingredientForm.unit" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                            <option value="kg">কেজি (kg)</option>
                            <option value="gm">গ্রাম (gm)</option>
                            <option value="litre">লিটার (litre)</option>
                            <option value="ml">মিলি (ml)</option>
                            <option value="pcs">পিস (pcs)</option>
                            <option value="pkt">প্যাকেট (pkt)</option>
                        </select>
                    </div>
                    <div>
                        <label class="section-heading">একক ক্রয়মূল্য (৳) *</label>
                        <input type="number" step="0.01" x-model.number="ingredientForm.cost_per_unit" placeholder="140" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold price-maroon rounded-xl">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">বর্তমান স্টক পরিমাণ *</label>
                        <input type="number" step="0.01" x-model.number="ingredientForm.current_stock" placeholder="100" class="pos-input w-full px-3 py-2 text-xs pos-nums rounded-xl">
                    </div>
                    <div>
                        <label class="section-heading">সতর্কতা লেভেল (Low Alert) *</label>
                        <input type="number" step="0.01" x-model.number="ingredientForm.alert_stock" placeholder="20" class="pos-input w-full px-3 py-2 text-xs pos-nums rounded-xl">
                    </div>
                </div>

                <!-- Ingredient Image Upload & URL -->
                <div class="p-3.5 rounded-2xl border bg-[#FBF8F5]" style="border-color:#E8DDD9;">
                    <label class="section-heading mb-2">কাঁচামালের ছবি (Ingredient Image)</label>
                    <div class="flex items-start gap-3.5">
                        <!-- Preview Box (Square 1:1) -->
                        <div class="relative rounded-2xl overflow-hidden bg-white border border-[#E8DDD9] flex items-center justify-center shrink-0 shadow-2xs"
                             style="width: 84px; height: 84px; min-width: 84px; min-height: 84px;">
                            <template x-if="ingredientForm.image_preview || ingredientForm.image">
                                <img :src="ingredientForm.image_preview || ingredientForm.image" style="width: 84px; height: 84px; object-fit: cover; display: block;">
                            </template>
                            <template x-if="!ingredientForm.image_preview && !ingredientForm.image">
                                <div class="flex flex-col items-center justify-center text-center p-2">
                                    <i data-lucide="package" class="w-6 h-6 text-[#8B1A2C] opacity-40 mb-1"></i>
                                    <span class="text-[9px] text-[#9B7A7E]">ছবি নেই</span>
                                </div>
                            </template>
                            <button x-show="ingredientForm.image_preview || ingredientForm.image"
                                    type="button"
                                    @click="ingredientForm.image=''; ingredientForm.image_preview=''; ingredientForm.image_file=null; if($refs.ingFileInput) $refs.ingFileInput.value='';"
                                    class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-md text-xs hover:bg-rose-700">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </div>

                        <!-- Upload & URL Inputs -->
                        <div class="flex-1 space-y-2">
                            <div>
                                <label class="text-[10px] font-bold block mb-1" style="color:#5C3840;">কম্পিউটার বা ডিভাইস থেকে ছবি আপলোড</label>
                                <input type="file" x-ref="ingFileInput" @change="handleIngImageUpload($event)" accept="image/*"
                                       class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-[#8B1A2C]/10 file:text-[#8B1A2C] hover:file:bg-[#8B1A2C]/20 cursor-pointer">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold block mb-1" style="color:#5C3840;">অথবা সরাসরি ছবির লিংক (Image URL)</label>
                                <input type="text" x-model="ingredientForm.image" @input="ingredientForm.image_preview = ingredientForm.image" placeholder="https://example.com/raw-material.jpg"
                                       class="pos-input w-full px-3 py-1.5 text-xs rounded-xl">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openIngredientModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveIngredient()" class="btn-maroon px-6 py-2.5 text-xs font-bold">সংরক্ষণ করুন</button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: STOCK PURCHASE (GRN)                         -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openPurchaseModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openPurchaseModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white">কাঁচামাল ক্রয় এন্ট্রি (Stock In)</h3>
                <button @click="openPurchaseModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 space-y-4">
                <div>
                    <label class="section-heading">কাঁচামাল নির্বাচন *</label>
                    <select x-model="purchaseIngredientId" @change="onPurchaseIngredientChange()" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                        <option value="">বেছে নিন...</option>
                        @foreach($ingredients as $ing)
                        <option value="{{ $ing->id }}" data-cost="{{ $ing->cost_per_unit }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">ক্রয়কৃত পরিমাণ *</label>
                        <input type="number" step="0.01" x-model.number="purchaseQty" placeholder="0.00" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold rounded-xl">
                    </div>
                    <div>
                        <label class="section-heading">একক মূল্য (৳) *</label>
                        <input type="number" step="0.01" x-model.number="purchaseCost" placeholder="0.00" class="pos-input w-full px-3 py-2 text-xs pos-nums rounded-xl">
                    </div>
                </div>

                <div class="p-3 rounded-2xl flex justify-between items-center" style="background:#FBF1F3; border:1px solid rgba(139,26,44,0.2);">
                    <span class="text-xs" style="color:#9B7A7E;">মোট ক্রয় খরচ:</span>
                    <span class="pos-nums font-black text-base price-maroon">৳<span x-text="(purchaseQty * purchaseCost).toLocaleString('en-US',{minimumFractionDigits:2})"></span></span>
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openPurchaseModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="submitPurchase()" class="btn-maroon px-6 py-2.5 text-xs font-bold">স্টক সংরক্ষণ</button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: RECIPE BOM BUILDER                           -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openRecipeModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openRecipeModal = false"
             class="w-full max-w-lg bg-white rounded-3xl overflow-hidden shadow-2xl border flex flex-col max-h-[90vh]"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <div>
                    <h3 class="text-sm font-bold text-white" x-text="activeRecipeItem?.name + ' — রেসিপি BOM'"></h3>
                    <p class="text-xs" style="color:rgba(255,255,255,0.7);">প্রতি ১টি অর্ডার তৈরিতে প্রয়োজনীয় কাঁচামালের অনুপাত নির্ধারণ</p>
                </div>
                <button @click="openRecipeModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 overflow-y-auto space-y-3 flex-1">
                <template x-for="(r, index) in recipeRows" :key="index">
                    <div class="flex items-center gap-2 p-2.5 rounded-xl border bg-gray-50">
                        <select x-model="r.ingredient_id" class="pos-input flex-1 px-2 py-1.5 text-xs rounded-lg bg-white">
                            <option value="">উপাদান সিলেক্ট করুন...</option>
                            @foreach($ingredients as $ing)
                            <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                            @endforeach
                        </select>
                        <input type="number" step="0.001" x-model.number="r.quantity_required" placeholder="পরিমাণ" class="pos-input w-24 px-2 py-1.5 text-xs pos-nums font-bold rounded-lg bg-white price-maroon">
                        <button @click="recipeRows.splice(index, 1)" class="p-1 text-gray-400 hover:text-rose-600">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </template>

                <button @click="recipeRows.push({ingredient_id: '', quantity_required: 0.1})"
                        class="text-xs font-bold hover:underline" style="color:#8B1A2C;">
                    + আরেকটি কাঁচামাল উপাদান যুক্ত করুন
                </button>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openRecipeModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveRecipeBOM()" class="btn-maroon px-6 py-2.5 text-xs font-bold">BOM রেসিপি সংরক্ষণ</button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: WASTAGE RECORD                               -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openWastageModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openWastageModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #7F1D1D, #991B1B);">
                <h3 class="text-sm font-bold text-white">কিচেন ওয়েস্টেজ / নষ্ট কাঁচামাল এন্ট্রি</h3>
                <button @click="openWastageModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 space-y-3">
                <div>
                    <label class="section-heading">নষ্ট হওয়া কাঁচামাল *</label>
                    <select x-model="wastageForm.ingredient_id" @change="onWastageIngredientChange()" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                        <option value="">বেছে নিন...</option>
                        @foreach($ingredients as $ing)
                        <option value="{{ $ing->id }}" data-unit="{{ $ing->unit }}" data-cost="{{ $ing->cost_per_unit }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">নষ্টের পরিমাণ *</label>
                        <input type="number" step="0.01" x-model.number="wastageForm.quantity" placeholder="1.5" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold rounded-xl" style="color:#C02020;">
                    </div>
                    <div>
                        <label class="section-heading">ক্ষতির কারণ *</label>
                        <select x-model="wastageForm.reason" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                            <option value="নষ্ট / পচে গেছে">নষ্ট / পচে গেছে (Spoiled)</option>
                            <option value="মেয়াদোত্তীর্ণ">মেয়াদোত্তীর্ণ (Expired)</option>
                            <option value="রান্নায় পুড়ে গেছে">রান্নায় পুড়ে গেছে (Burnt)</option>
                            <option value="শেফ টেস্টিং">শেফ টেস্টিং (Quality Test)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="section-heading">মন্তব্য / নোট</label>
                    <input type="text" x-model="wastageForm.notes" placeholder="উদাঃ ফ্রিজ বন্ধ থাকায় নষ্ট হয়েছে" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openWastageModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="submitWastage()" class="btn-maroon px-6 py-2.5 text-xs font-bold" style="background:#991B1B;">ওয়েস্টেজ রেকর্ড করুন</button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function inventoryManager() {
    return {
        activeTab: 'ingredients',
        openIngredientModal: false,
        openPurchaseModal: false,
        openRecipeModal: false,
        openWastageModal: false,

        ingredientForm: { id: null, name: '', bangla_name: '', unit: 'kg', image: '', image_preview: '', image_file: null, cost_per_unit: 0, current_stock: 0, alert_stock: 10 },
        purchaseIngredientId: '', purchaseQty: 0, purchaseCost: 0,
        activeRecipeItem: null, recipeRows: [],
        wastageForm: { ingredient_id: '', quantity: 0, unit: 'kg', cost_impact: 0, reason: 'নষ্ট / পচে গেছে', notes: '' },

        init() { this.$nextTick(() => window.initLucideIcons()); },

        handleIngImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.ingredientForm.image_file = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.ingredientForm.image_preview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        resetIngredientForm() {
            this.ingredientForm = { id: null, name: '', bangla_name: '', unit: 'kg', image: '', image_preview: '', image_file: null, cost_per_unit: 0, current_stock: 0, alert_stock: 10 };
            if (this.$refs.ingFileInput) this.$refs.ingFileInput.value = '';
        },

        editIngredient(ing) {
            this.ingredientForm = {
                id: ing.id,
                name: ing.name,
                bangla_name: ing.bangla_name || '',
                unit: ing.unit,
                image: ing.image || '',
                image_preview: ing.image || '',
                image_file: null,
                cost_per_unit: parseFloat(ing.cost_per_unit),
                current_stock: parseFloat(ing.current_stock),
                alert_stock: parseFloat(ing.alert_stock)
            };
            if (this.$refs.ingFileInput) this.$refs.ingFileInput.value = '';
            this.openIngredientModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        async saveIngredient() {
            if (!this.ingredientForm.name || this.ingredientForm.cost_per_unit < 0) {
                alert('অনুগ্রহ করে উপাদান নাম এবং একক ক্রয়মূল্য দিন!'); return;
            }
            try {
                const formData = new FormData();
                if (this.ingredientForm.id) formData.append('id', this.ingredientForm.id);
                formData.append('name', this.ingredientForm.name);
                formData.append('bangla_name', this.ingredientForm.bangla_name || '');
                formData.append('unit', this.ingredientForm.unit);
                formData.append('cost_per_unit', this.ingredientForm.cost_per_unit || 0);
                formData.append('current_stock', this.ingredientForm.current_stock || 0);
                formData.append('alert_stock', this.ingredientForm.alert_stock || 0);

                if (this.ingredientForm.image_file) {
                    formData.append('image_file', this.ingredientForm.image_file);
                } else if (this.ingredientForm.image) {
                    formData.append('image', this.ingredientForm.image);
                }

                const res = await fetch('{{ route('inventory.ingredient.store') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else { alert(data.message || 'সংরক্ষণ ব্যর্থ হয়েছে'); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async deleteIngredient(id, name) {
            if (!confirm(`আপনি কি "${name}" কাঁচামালটি মুছে ফেলতে চান?`)) return;
            try {
                const res = await fetch(`/inventory/ingredient/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        resetPurchaseForm() {
            this.purchaseIngredientId = ''; this.purchaseQty = 0; this.purchaseCost = 0;
        },

        quickPurchaseStock(id, name, unit, cost) {
            this.purchaseIngredientId = id; this.purchaseCost = cost; this.purchaseQty = 0;
            this.openPurchaseModal = true;
        },

        onPurchaseIngredientChange() {
            const sel = event.target;
            const opt = sel.options[sel.selectedIndex];
            if (opt && opt.dataset.cost) this.purchaseCost = parseFloat(opt.dataset.cost);
        },

        async submitPurchase() {
            if (!this.purchaseIngredientId || this.purchaseQty <= 0) { alert('সঠিক পরিমাণ ও উপাদান সিলেক্ট করুন!'); return; }
            try {
                const res = await fetch('{{ route('inventory.purchase.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ ingredient_id: this.purchaseIngredientId, quantity: this.purchaseQty, cost_per_unit: this.purchaseCost })
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        openRecipeModalFor(item) {
            this.activeRecipeItem = item;
            this.recipeRows = item.recipes && item.recipes.length > 0
                ? item.recipes.map(r => ({ ingredient_id: r.ingredient_id, quantity_required: parseFloat(r.quantity_required) }))
                : [{ ingredient_id: '', quantity_required: 0.1 }];
            this.openRecipeModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        async saveRecipeBOM() {
            const valid = this.recipeRows.filter(r => r.ingredient_id && r.quantity_required > 0);
            try {
                const res = await fetch('{{ route('inventory.recipe.save') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ item_id: this.activeRecipeItem.id, recipes: valid })
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        resetWastageForm() {
            this.wastageForm = { ingredient_id: '', quantity: 0, unit: 'kg', cost_impact: 0, reason: 'নষ্ট / পচে গেছে', notes: '' };
        },

        onWastageIngredientChange() {
            const sel = event.target;
            const opt = sel.options[sel.selectedIndex];
            if (opt) {
                if (opt.dataset.unit) this.wastageForm.unit = opt.dataset.unit;
            }
        },

        async submitWastage() {
            if (!this.wastageForm.ingredient_id || this.wastageForm.quantity <= 0) {
                alert('সঠিক উপাদান ও পরিমাণ দিন!'); return;
            }
            try {
                const res = await fetch('{{ route('inventory.wastage.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        ingredient_id: this.wastageForm.ingredient_id,
                        quantity: this.wastageForm.quantity,
                        unit: this.wastageForm.unit,
                        cost_impact: 0,
                        reason: this.wastageForm.reason,
                        notes: this.wastageForm.notes
                    })
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
