@extends('layouts.app')
@section('title', 'মেনু ও আইটেম ম্যানেজমেন্ট')
@section('content')
<div x-data="menuManager()" x-init="init()" class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(139,26,44,0.1); border:1.5px solid rgba(139,26,44,0.25);">
                    <i data-lucide="utensils" class="w-5 h-5" style="color:#8B1A2C;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">মেনু ও আইটেম ম্যানেজমেন্ট</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">খাবারের ক্যাটাগরি, আইটেম, সাইজ ভ্যারিয়েন্ট ও মডিফায়ার সম্পূর্ণ ডায়নামিক পরিচালনা করুন</p>
        </div>

        <div class="flex items-center gap-2">
            <button @click="openCategoryModal = true; resetCategoryForm();"
                    class="btn-outline px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="folder-plus" class="w-4 h-4"></i>
                <span>+ নতুন ক্যাটাগরি</span>
            </button>
            <button @click="openItemModal = true; resetItemForm();"
                    class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ নতুন খাবার আইটেম</span>
            </button>
        </div>
    </div>

    <!-- Tabs & Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-2 rounded-2xl bg-white border" style="border-color:#E8DDD9;">
        <!-- Tabs -->
        <div class="flex items-center gap-1">
            <button @click="activeTab = 'items'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                    :style="activeTab === 'items' ? 'background:#8B1A2C; color:#fff;' : 'color:#5C3840; background:transparent;'">
                <i data-lucide="utensils" class="w-3.5 h-3.5"></i>
                <span>খাবার মেনু তালিকা</span>
            </button>
            <button @click="activeTab = 'categories'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                    :style="activeTab === 'categories' ? 'background:#8B1A2C; color:#fff;' : 'color:#5C3840; background:transparent;'">
                <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                <span>ক্যাটাগরি সমূহ ({{ $categories->count() }})</span>
            </button>
            <button @click="activeTab = 'modifiers'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                    :style="activeTab === 'modifiers' ? 'background:#8B1A2C; color:#fff;' : 'color:#5C3840; background:transparent;'">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                <span>মডিফায়ার ও এক্সট্রা ({{ $allModifiers->count() }})</span>
            </button>
        </div>

        <!-- Search Filter -->
        <div class="relative w-full sm:w-64">
            <i data-lucide="search" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2" style="color:#9B7A7E;"></i>
            <input type="text" x-model="searchQuery" placeholder="খাবারের নাম বা SKU খুঁজুন..."
                   class="pos-input w-full pl-8 pr-3 py-1.5 text-xs rounded-xl">
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TAB 1: FOOD ITEMS LIST                              -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'items'" class="space-y-4">
        @foreach($categories as $category)
        <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
            <!-- Category Bar -->
            <div class="px-5 py-3 border-b flex items-center justify-between" style="background:#FBF8F5; border-color:#E8DDD9;">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full" style="background:#8B1A2C;"></span>
                    <h3 class="font-extrabold text-sm" style="color:#1A0A0C;">{{ $category->name }}</h3>
                    @if($category->bangla_name)
                        <span class="text-xs" style="color:#9B7A7E;">({{ $category->bangla_name }})</span>
                    @endif
                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold pos-nums" style="background:#F0E8E5; color:#5C3840;">
                        {{ $category->items->count() }} টি আইটেম
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="editCategory({{ json_encode($category) }})"
                            class="text-xs font-bold hover:underline" style="color:#8B1A2C;">
                        এডিট ক্যাটাগরি
                    </button>
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                        <tr>
                            <th class="px-3 py-2.5 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">ছবি</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">SKU</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">আইটেম নাম</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">কিচেন স্টেশন</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">বিক্রয় মূল্য</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">ভ্যারিয়েন্ট</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">স্ট্যাটাস</th>
                            <th class="px-4 py-2.5 font-bold uppercase tracking-wider text-[10px] text-right" style="color:#9B7A7E;">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($category->items as $item)
                        <tr class="data-row border-b" style="border-color:#F0E8E5;">
                            <td class="px-3 py-2.5" style="width: 60px; min-width: 60px;">
                                <div class="rounded-xl overflow-hidden bg-[#F8F5F2] border border-[#E0D4CF] flex items-center justify-center shrink-0 shadow-2xs"
                                     style="width: 48px; height: 48px; min-width: 48px; min-height: 48px;">
                                    @if($item->image)
                                        <img src="{{ $item->image }}" alt="{{ $item->name }}" style="width: 48px; height: 48px; object-fit: cover; display: block;">
                                    @else
                                        <i data-lucide="utensils" class="w-4 h-4 opacity-35" style="color:#8B1A2C;"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 pos-nums font-bold" style="color:#9B7A7E;">{{ $item->sku ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <p class="font-bold" style="color:#1A0A0C;">{{ $item->name }}</p>
                                @if($item->bangla_name)
                                    <p class="text-[11px]" style="color:#9B7A7E;">{{ $item->bangla_name }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $stnLabels = [
                                        'main_kitchen' => ['মেইন কিচেন', '#8B1A2C', '#FBF1F3'],
                                        'grill' => ['গ্রিল ও নান', '#B8922A', '#FEF3C7'],
                                        'drinks_bar' => ['ড্রিংকস বার', '#0284C7', '#E0F2FE'],
                                        'dessert' => ['ডেজার্ট', '#9333EA', '#F3E8FF'],
                                    ];
                                    $st = $stnLabels[$item->kitchen_station] ?? ['মেইন কিচেন', '#8B1A2C', '#FBF1F3'];
                                @endphp
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold"
                                      style="background:{{ $st[2] }}; color:{{ $st[0] }};">
                                    {{ $st[0] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 pos-nums font-black price-maroon">
                                ৳{{ number_format($item->selling_price, 2) }}
                            </td>
                            <td class="px-4 py-3">
                                @if($item->has_variants && $item->variants->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($item->variants as $var)
                                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-gray-100 text-gray-700">
                                                {{ $var->name }}: ৳{{ number_format($var->price, 0) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-[11px]" style="color:#9B7A7E;">স্ট্যান্ডার্ড (১ সাইজ)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($item->is_available)
                                    <span class="badge-paid px-2 py-0.5 rounded-full text-[10px] font-bold">✓ চালু আছে</span>
                                @else
                                    <span class="badge-unpaid px-2 py-0.5 rounded-full text-[10px] font-bold">বন্ধ</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button @click="editItem({{ json_encode($item) }})"
                                            class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all"
                                            style="background:#FBF1F3; color:#8B1A2C; border:1px solid rgba(139,26,44,0.25);"
                                            onmouseover="this.style.background='rgba(139,26,44,0.15)'"
                                            onmouseout="this.style.background='#FBF1F3'">
                                        এডিট
                                    </button>
                                    <button @click="deleteItem({{ $item->id }}, '{{ $item->name }}')"
                                            class="p-1 rounded-lg text-gray-400 hover:text-rose-600 transition-colors">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-xs" style="color:#9B7A7E;">
                                এই ক্যাটাগরিতে এখনো কোনো খাবার আইটেম যুক্ত করা হয়নি।
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TAB 2: CATEGORIES CRUD                              -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'categories'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($categories as $cat)
        <div class="pos-card p-4 flex flex-col justify-between">
            <div class="flex items-start justify-between">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#FBF1F3; border:1px solid rgba(139,26,44,0.2);">
                    <i data-lucide="{{ $cat->icon ?: 'utensils' }}" class="w-5 h-5" style="color:#8B1A2C;"></i>
                </div>
                <div class="flex items-center gap-1">
                    <button @click="editCategory({{ json_encode($cat) }})" class="p-1 text-gray-400 hover:text-gray-700">
                        <i data-lucide="edit" class="w-4 h-4"></i>
                    </button>
                    <button @click="deleteCategory({{ $cat->id }}, '{{ $cat->name }}')" class="p-1 text-gray-400 hover:text-rose-600">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <div class="mt-3">
                <h4 class="font-bold text-sm" style="color:#1A0A0C;">{{ $cat->name }}</h4>
                <p class="text-xs" style="color:#9B7A7E;">{{ $cat->bangla_name ?? '—' }}</p>
                <div class="mt-2 pt-2 border-t flex justify-between items-center text-xs" style="border-color:#F0E8E5;">
                    <span style="color:#9B7A7E;">আইটেম সংখ্যা:</span>
                    <span class="font-bold pos-nums price-maroon">{{ $cat->items->count() }} টি</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TAB 3: MODIFIERS CRUD                               -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'modifiers'" class="space-y-4">
        <div class="flex justify-end">
            <button @click="openModifierModal = true; resetModifierForm();"
                    class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ নতুন মডিফায়ার / সাইড ডিশ</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
            <table class="w-full text-left text-xs">
                <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                    <tr>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">মডিফায়ার নাম</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">বাংলা নাম</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">অতিরিক্ত মূল্য</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">স্ট্যাটাস</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px] text-right" style="color:#9B7A7E;">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allModifiers as $mod)
                    <tr class="data-row border-b" style="border-color:#F0E8E5;">
                        <td class="px-4 py-3 font-bold" style="color:#1A0A0C;">{{ $mod->name }}</td>
                        <td class="px-4 py-3" style="color:#9B7A7E;">{{ $mod->bangla_name ?? '—' }}</td>
                        <td class="px-4 py-3 pos-nums font-black price-maroon">
                            {{ $mod->price > 0 ? '+৳' . number_format($mod->price, 2) : 'ফ্রি (৳০.০০)' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge-paid px-2 py-0.5 rounded-full text-[10px] font-bold">সক্রিয়</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="editModifier({{ json_encode($mod) }})"
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                        style="background:#FBF1F3; color:#8B1A2C; border:1px solid rgba(139,26,44,0.25);">
                                    এডিট
                                </button>
                                <button @click="deleteModifier({{ $mod->id }})" class="p-1 text-gray-400 hover:text-rose-600">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-xs" style="color:#9B7A7E;">
                            কোনো মডিফায়ার যোগ করা হয়নি।
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: ADD / EDIT ITEM                              -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openItemModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openItemModal = false"
             class="w-full max-w-xl bg-white rounded-3xl overflow-hidden shadow-2xl border max-h-[90vh] flex flex-col"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white" x-text="itemForm.id ? 'খাবার আইটেম এডিট করুন' : 'নতুন খাবার আইটেম যুক্ত করুন'"></h3>
                <button @click="openItemModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 overflow-y-auto space-y-4 flex-1">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">ক্যাটাগরি *</label>
                        <select x-model="itemForm.category_id" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                            <option value="">বেছে নিন...</option>
                            @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->bangla_name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="section-heading">কিচেন স্টেশন *</label>
                        <select x-model="itemForm.kitchen_station" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                            <option value="main_kitchen">মেইন কিচেন (Main Kitchen)</option>
                            <option value="grill">গ্রিল ও নান (Grill & Naan)</option>
                            <option value="drinks_bar">ড্রিংকস বার (Drinks Bar)</option>
                            <option value="dessert">ডেজার্ট ও সুইটস (Dessert)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">আইটেম নাম (English) *</label>
                        <input type="text" x-model="itemForm.name" placeholder="e.g. Mutton Kacchi Biryani" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                    </div>
                    <div>
                        <label class="section-heading">বাংলা নাম</label>
                        <input type="text" x-model="itemForm.bangla_name" placeholder="উদাঃ মাটন কাচ্চি বিরিয়ানি" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="section-heading">বিক্রয় মূল্য (৳) *</label>
                        <input type="number" step="0.01" x-model.number="itemForm.selling_price" placeholder="450" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold rounded-xl price-maroon">
                    </div>
                    <div>
                        <label class="section-heading">প্রস্তুত খরচ / Cost (৳)</label>
                        <input type="number" step="0.01" x-model.number="itemForm.cost_price" placeholder="280" class="pos-input w-full px-3 py-2 text-xs pos-nums rounded-xl">
                    </div>
                    <div>
                        <label class="section-heading">SKU কোড</label>
                        <input type="text" x-model="itemForm.sku" placeholder="KAC-001" class="pos-input w-full px-3 py-2 text-xs pos-nums uppercase rounded-xl">
                    </div>
                </div>

                <!-- Food Image Upload & URL -->
                <div class="p-3.5 rounded-2xl border bg-[#FBF8F5]" style="border-color:#E8DDD9;">
                    <label class="section-heading mb-2">খাবারের ছবি (Food Image)</label>
                    <div class="flex items-start gap-3.5">
                        <!-- Image Preview (Square 1:1) -->
                        <div class="relative rounded-2xl overflow-hidden bg-white border border-[#E8DDD9] flex items-center justify-center shrink-0 shadow-2xs"
                             style="width: 84px; height: 84px; min-width: 84px; min-height: 84px;">
                            <template x-if="itemForm.image_preview || itemForm.image">
                                <img :src="itemForm.image_preview || itemForm.image" style="width: 84px; height: 84px; object-fit: cover; display: block;">
                            </template>
                            <template x-if="!itemForm.image_preview && !itemForm.image">
                                <div class="flex flex-col items-center justify-center text-center p-2">
                                    <i data-lucide="image" class="w-6 h-6 text-[#8B1A2C] opacity-40 mb-1"></i>
                                    <span class="text-[9px] text-[#9B7A7E]">ছবি নেই</span>
                                </div>
                            </template>
                            <button x-show="itemForm.image_preview || itemForm.image"
                                    type="button"
                                    @click="itemForm.image=''; itemForm.image_preview=''; itemForm.image_file=null; if($refs.itemFileInput) $refs.itemFileInput.value='';"
                                    class="absolute top-1 right-1 w-5 h-5 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-md text-xs hover:bg-rose-700">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </div>

                        <!-- Upload & URL inputs -->
                        <div class="flex-1 space-y-2">
                            <div>
                                <label class="text-[10px] font-bold block mb-1" style="color:#5C3840;">কম্পিউটার বা ডিভাইস থেকে আপলোড</label>
                                <input type="file" x-ref="itemFileInput" @change="handleItemImageUpload($event)" accept="image/*"
                                       class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-[#8B1A2C]/10 file:text-[#8B1A2C] hover:file:bg-[#8B1A2C]/20 cursor-pointer">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold block mb-1" style="color:#5C3840;">অথবা সরাসরি ইমেজ লিংক (Image URL)</label>
                                <input type="text" x-model="itemForm.image" @input="itemForm.image_preview = itemForm.image" placeholder="https://example.com/image.jpg"
                                       class="pos-input w-full px-3 py-1.5 text-xs rounded-xl">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Variants Toggle -->
                <div class="p-3 rounded-2xl border" style="background:#FBF8F5; border-color:#E8DDD9;">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="text-xs font-bold" style="color:#1A0A0C;">সাইজ ভ্যারিয়েন্ট আছে? (Half, Full, 1:1, 1:2)</p>
                            <p class="text-[11px]" style="color:#9B7A7E;">বিভিন্ন সাইজে আলাদা দাম থাকলে অন করুন</p>
                        </div>
                        <input type="checkbox" x-model="itemForm.has_variants" class="w-4 h-4" style="accent-color:#8B1A2C;">
                    </div>

                    <div x-show="itemForm.has_variants" class="space-y-2 pt-2 border-t" style="border-color:#E8DDD9;">
                        <template x-for="(v, index) in itemForm.variants" :key="index">
                            <div class="flex items-center gap-2">
                                <input type="text" x-model="v.name" placeholder="সাইজ নাম (উদাঃ Half / Full)" class="pos-input flex-1 px-2.5 py-1.5 text-xs rounded-lg">
                                <input type="number" step="0.01" x-model.number="v.price" placeholder="মূল্য ৳" class="pos-input w-24 px-2.5 py-1.5 text-xs pos-nums font-bold price-maroon rounded-lg">
                                <button @click="itemForm.variants.splice(index, 1)" class="p-1 text-gray-400 hover:text-rose-600">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                        <button @click="itemForm.variants.push({name:'', price: itemForm.selling_price || 0})"
                                class="text-xs font-bold mt-1 hover:underline" style="color:#8B1A2C;">
                            + আরেকটি ভ্যারিয়েন্ট যোগ করুন
                        </button>
                    </div>
                </div>

                <!-- Modifiers Selector -->
                <div>
                    <label class="section-heading">যুক্ত মডিফায়ার / সাইড অপশন</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($allModifiers as $m)
                        <label class="p-2 rounded-xl border flex items-center gap-2 text-xs cursor-pointer" style="background:#F8F5F2; border-color:#E8DDD9;">
                            <input type="checkbox" :value="{{ $m->id }}" x-model="itemForm.modifier_ids" style="accent-color:#8B1A2C;">
                            <span style="color:#1A0A0C;">{{ $m->name }}</span>
                            <span class="ml-auto pos-nums font-bold text-[11px]" style="color:#8B1A2C;">+৳{{ number_format($m->price, 0) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openItemModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveItem()" class="btn-maroon px-6 py-2.5 text-xs font-bold">সংরক্ষণ করুন</button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: ADD / EDIT CATEGORY                          -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openCategoryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openCategoryModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white" x-text="categoryForm.id ? 'ক্যাটাগরি এডিট করুন' : 'নতুন ক্যাটাগরি তৈরি করুন'"></h3>
                <button @click="openCategoryModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <label class="section-heading">ক্যাটাগরি নাম (English) *</label>
                    <input type="text" x-model="categoryForm.name" placeholder="e.g. Kacchi & Biryani" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
                <div>
                    <label class="section-heading">বাংলা নাম</label>
                    <input type="text" x-model="categoryForm.bangla_name" placeholder="উদাঃ কাচ্চি ও বিরিয়ানি" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">আইকন কোড</label>
                        <input type="text" x-model="categoryForm.icon" placeholder="utensils, flame, cup-soda" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                    </div>
                    <div>
                        <label class="section-heading">ক্রমিক ক্রম (Sort)</label>
                        <input type="number" x-model.number="categoryForm.sort_order" placeholder="0" class="pos-input w-full px-3 py-2 text-xs pos-nums rounded-xl">
                    </div>
                </div>
            </div>
            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openCategoryModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveCategory()" class="btn-maroon px-6 py-2.5 text-xs font-bold">সংরক্ষণ করুন</button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: ADD / EDIT MODIFIER                          -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openModifierModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openModifierModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white" x-text="modifierForm.id ? 'মডিফায়ার এডিট করুন' : 'নতুন মডিফায়ার যুক্ত করুন'"></h3>
                <button @click="openModifierModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <label class="section-heading">মডিফায়ার নাম (English) *</label>
                    <input type="text" x-model="modifierForm.name" placeholder="e.g. Extra Egg, Extra Ghee" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
                <div>
                    <label class="section-heading">বাংলা নাম</label>
                    <input type="text" x-model="modifierForm.bangla_name" placeholder="উদাঃ অতিরিক্ত ডিম" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
                <div>
                    <label class="section-heading">অতিরিক্ত মূল্য (৳) *</label>
                    <input type="number" step="0.01" x-model.number="modifierForm.price" placeholder="30" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold price-maroon rounded-xl">
                </div>
            </div>
            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openModifierModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveModifier()" class="btn-maroon px-6 py-2.5 text-xs font-bold">সংরক্ষণ করুন</button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function menuManager() {
    return {
        activeTab: 'items',
        searchQuery: '',
        openItemModal: false,
        openCategoryModal: false,
        openModifierModal: false,

        itemForm: { id: null, category_id: '', name: '', bangla_name: '', sku: '', barcode: '', image: '', image_preview: '', image_file: null, selling_price: 0, cost_price: 0, vat_percent: 5, kitchen_station: 'main_kitchen', has_variants: false, variants: [], modifier_ids: [] },
        categoryForm: { id: null, name: '', bangla_name: '', icon: 'utensils', sort_order: 0 },
        modifierForm: { id: null, name: '', bangla_name: '', price: 0 },

        init() { this.$nextTick(() => window.initLucideIcons()); },

        handleItemImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.itemForm.image_file = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.itemForm.image_preview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        resetItemForm() {
            this.itemForm = { id: null, category_id: '', name: '', bangla_name: '', sku: '', barcode: '', image: '', image_preview: '', image_file: null, selling_price: 0, cost_price: 0, vat_percent: 5, kitchen_station: 'main_kitchen', has_variants: false, variants: [{name: 'Half', price: 0}, {name: 'Full', price: 0}], modifier_ids: [] };
            if (this.$refs.itemFileInput) this.$refs.itemFileInput.value = '';
        },

        editItem(item) {
            this.itemForm = {
                id: item.id,
                category_id: item.category_id,
                name: item.name,
                bangla_name: item.bangla_name || '',
                sku: item.sku || '',
                barcode: item.barcode || '',
                image: item.image || '',
                image_preview: item.image || '',
                image_file: null,
                selling_price: parseFloat(item.selling_price) || 0,
                cost_price: parseFloat(item.cost_price) || 0,
                vat_percent: parseFloat(item.vat_percent) || 5,
                kitchen_station: item.kitchen_station || 'main_kitchen',
                has_variants: !!item.has_variants,
                variants: item.variants ? item.variants.map(v => ({ id: v.id, name: v.name, price: parseFloat(v.price), cost_price: parseFloat(v.cost_price || 0) })) : [],
                modifier_ids: item.modifiers ? item.modifiers.map(m => m.id) : []
            };
            if (this.$refs.itemFileInput) this.$refs.itemFileInput.value = '';
            this.openItemModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        async saveItem() {
            if (!this.itemForm.name || !this.itemForm.category_id || this.itemForm.selling_price < 0) {
                alert('অনুগ্রহ করে আইটেমের নাম, ক্যাটাগরি এবং মূল্য দিন!'); return;
            }
            try {
                const formData = new FormData();
                if (this.itemForm.id) formData.append('id', this.itemForm.id);
                formData.append('category_id', this.itemForm.category_id);
                formData.append('name', this.itemForm.name);
                formData.append('bangla_name', this.itemForm.bangla_name || '');
                formData.append('sku', this.itemForm.sku || '');
                formData.append('barcode', this.itemForm.barcode || '');
                formData.append('selling_price', this.itemForm.selling_price || 0);
                formData.append('cost_price', this.itemForm.cost_price || 0);
                formData.append('vat_percent', this.itemForm.vat_percent || 5);
                formData.append('kitchen_station', this.itemForm.kitchen_station || 'main_kitchen');
                formData.append('has_variants', this.itemForm.has_variants ? 1 : 0);
                formData.append('is_available', 1);

                if (this.itemForm.image_file) {
                    formData.append('image_file', this.itemForm.image_file);
                } else if (this.itemForm.image) {
                    formData.append('image', this.itemForm.image);
                }

                if (this.itemForm.variants && this.itemForm.variants.length > 0) {
                    this.itemForm.variants.forEach((v, idx) => {
                        formData.append(`variants[${idx}][name]`, v.name);
                        formData.append(`variants[${idx}][price]`, v.price);
                        if (v.cost_price) formData.append(`variants[${idx}][cost_price]`, v.cost_price);
                    });
                }

                if (this.itemForm.modifier_ids && this.itemForm.modifier_ids.length > 0) {
                    this.itemForm.modifier_ids.forEach((id, idx) => {
                        formData.append(`modifier_ids[${idx}]`, id);
                    });
                }

                const res = await fetch('{{ route('menu.item.store') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else { alert(data.message || 'সংরক্ষণ ব্যর্থ হয়েছে'); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async deleteItem(id, name) {
            if (!confirm(`আপনি কি নিশ্চিতভাবে "${name}" আইটেমটি মুছে ফেলতে চান?`)) return;
            try {
                const res = await fetch(`/menu/item/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        resetCategoryForm() {
            this.categoryForm = { id: null, name: '', bangla_name: '', icon: 'utensils', sort_order: 0 };
        },

        editCategory(cat) {
            this.categoryForm = { id: cat.id, name: cat.name, bangla_name: cat.bangla_name || '', icon: cat.icon || 'utensils', sort_order: cat.sort_order || 0 };
            this.openCategoryModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        async saveCategory() {
            if (!this.categoryForm.name) { alert('ক্যাটাগরির নাম আবশ্যক!'); return; }
            try {
                const res = await fetch('{{ route('menu.category.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.categoryForm)
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async deleteCategory(id, name) {
            if (!confirm(`ক্যাটাগরি "${name}" এবং এর ভিতরের সব মেনু মুছে যাবে! আপনি কি নিশ্চিত?`)) return;
            try {
                const res = await fetch(`/menu/category/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        resetModifierForm() {
            this.modifierForm = { id: null, name: '', bangla_name: '', price: 0 };
        },

        editModifier(mod) {
            this.modifierForm = { id: mod.id, name: mod.name, bangla_name: mod.bangla_name || '', price: parseFloat(mod.price) || 0 };
            this.openModifierModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        async saveModifier() {
            if (!this.modifierForm.name) { alert('মডিফায়ারের নাম দিন!'); return; }
            try {
                const res = await fetch('{{ route('menu.modifier.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.modifierForm)
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async deleteModifier(id) {
            if (!confirm('মডিফায়ারটি মুছে ফেলতে চান?')) return;
            try {
                const res = await fetch(`/menu/modifier/${id}`, {
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
