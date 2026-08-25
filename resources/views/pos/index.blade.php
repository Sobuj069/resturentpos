@extends('layouts.app')
@section('title', 'POS Billing Terminal')
@section('content')
<div x-data="posTerminal()" x-init="init()" class="h-full flex flex-row overflow-hidden relative">

    <!-- ════════════════════════════════════════════════════════ -->
    <!-- LEFT: Catalog & Item Grid (Flex-1)                       -->
    <!-- ════════════════════════════════════════════════════════ -->
    <div class="flex-1 flex flex-col min-w-0 h-full border-r overflow-hidden" style="background:#F8F5F2; border-color:#E0D4CF;">

        <!-- Toolbar -->
        <div class="px-3 sm:px-4 py-2.5 sm:py-3 border-b shrink-0 bg-white" style="border-color:#E0D4CF;">
            <div class="flex items-center gap-2 mb-2">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2" style="color:#9B7A7E;"></i>
                    <input type="text" x-ref="searchInput" x-model="searchQuery"
                           placeholder="খাবারের নাম বা SKU... (শর্টকাট: / বা F1)"
                           class="pos-input w-full pl-9 pr-8 py-2 text-xs font-semibold">
                    <button x-show="searchQuery" @click="searchQuery=''" class="absolute right-2.5 top-1/2 -translate-y-1/2" style="color:#9B7A7E;">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i>
                    </button>
                </div>

                <!-- Table Floor Button -->
                <button @click="openTableModal = true"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-xl border text-xs font-bold transition-all shrink-0"
                        style="background:#FBF8F5; border-color:#D0BDB8; color:#5C3840;">
                    <i data-lucide="layout-grid" class="w-4 h-4" style="color:#B8922A;"></i>
                    <span class="hidden sm:inline" x-text="selectedTable ? selectedTable.name : 'টেবিল ফ্লোর (F3)'"></span>
                    <span class="sm:hidden" x-text="selectedTable ? selectedTable.name : 'টেবিল'"></span>
                </button>

                <!-- Voice AI Button -->
                <button @click="openAiModal = true"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition-all shrink-0"
                        style="background: linear-gradient(135deg,rgba(139,26,44,0.08),rgba(139,26,44,0.04)); border: 1.5px solid rgba(139,26,44,0.25); color:#8B1A2C;">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span class="hidden lg:inline">AI ভয়েস</span>
                </button>

                @if(auth()->user()?->isSuperAdmin())
                    <!-- SuperAdmin Command Center Link -->
                    <a href="{{ route('saas.dashboard') }}"
                       title="SaaS সুপার-অ্যাডমিন কমান্ড সেন্টার"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-black transition-all shrink-0 border shadow-xs"
                       style="background:#FEF3C7; border-color:#FDE68A; color:#92400E;">
                        <i data-lucide="shield-check" class="w-4 h-4 text-amber-700"></i>
                        <span class="hidden sm:inline">সুপার-অ্যাডমিন</span>
                    </a>
                @endif

                @if(auth()->user()?->isAdmin() || auth()->user()?->isManager())
                    <!-- Management Dashboard Link -->
                    <a href="{{ route('reports.dashboard') }}"
                       title="ম্যানেজমেন্ট ও সেলস ড্যাশবোর্ড"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition-all shrink-0 border shadow-xs"
                       style="background:#EFF6FF; border-color:#DBEAFE; color:#1E40AF;">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-blue-600"></i>
                        <span class="hidden sm:inline">ড্যাশবোর্ড</span>
                    </a>
                @endif

                <!-- Logout / Lock Button -->
                <button @click="logoutConfirm()"
                        title="লগআউট করুন"
                        class="flex items-center gap-1 px-2.5 py-2 rounded-xl text-xs font-bold transition-all shrink-0 border"
                        style="background:#FFF5F5; border-color:#FED7D7; color:#9B1C1C;">
                    <i data-lucide="log-out" class="w-4 h-4 text-rose-600"></i>
                    <span class="hidden xl:inline">লগআউট</span>
                </button>
            </div>

            <!-- Category Chips (Horizontal Scroll) -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                <button @click="selectedCategory = null"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold flex items-center gap-1.5 shrink-0 transition-all border shadow-xs"
                        :style="selectedCategory === null ? 'background:#8B1A2C; color:#ffffff; border-color:#8B1A2C;' : 'background:#FFFFFF; color:#4A2E33; border-color:#E2D8D4;'">
                    <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    <span>সকল মেনু (<span x-text="allItems.length"></span>)</span>
                </button>
                <template x-for="cat in categories" :key="cat.id">
                    <button @click="selectedCategory = cat.id"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold flex items-center gap-1.5 shrink-0 transition-all border shadow-xs"
                            :style="selectedCategory === cat.id ? 'background:#8B1A2C; color:#ffffff; border-color:#8B1A2C;' : 'background:#FFFFFF; color:#4A2E33; border-color:#E2D8D4;'">
                        <span x-text="cat.bangla_name || cat.name"></span>
                        <span class="text-[10px] px-1.5 py-0.2 rounded-full font-mono font-bold"
                              :style="selectedCategory === cat.id ? 'background:rgba(255,255,255,0.25); color:#fff;' : 'background:#F0E8E5; color:#5C3840;'"
                              x-text="cat.items.length"></span>
                    </button>
                </template>
            </div>
        </div>

        <!-- Items Grid (Mobile: 2-3 per row, Desktop: 3 per row) -->
        <div class="flex-1 p-2 sm:p-4 overflow-y-auto min-h-0 pb-24 md:pb-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-3 gap-2.5 sm:gap-3.5">
                <template x-for="item in filteredItems" :key="item.id">
                    <button @click="handleItemClick(item)"
                            class="pos-card group relative flex flex-col justify-between p-2.5 sm:p-3 text-left active:scale-[0.97] transition-all duration-200 rounded-2xl bg-white border border-[#E0D4CF] hover:border-[#8B1A2C] shadow-xs hover:shadow-md select-none overflow-hidden"
                            style="height: 235px; min-height: 235px; max-height: 235px;">
                        
                        <!-- Fixed Height Food Image Container (Always 130px) -->
                        <div class="relative w-full rounded-xl overflow-hidden mb-2 bg-[#F3ECE8] flex items-center justify-center shrink-0 border border-black/5"
                             style="height: 130px; min-height: 130px; max-height: 130px;">
                            <!-- Image if exists -->
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.name"
                                     style="width: 100%; height: 130px; object-fit: cover; object-position: center; display: block;"
                                     class="group-hover:scale-105 transition-transform duration-300">
                            </template>
                            <!-- Fallback Icon if no image -->
                            <template x-if="!item.image">
                                <div class="w-full h-full flex flex-col items-center justify-center text-center p-2 bg-gradient-to-br from-[#8B1A2C]/10 to-[#B8922A]/10"
                                     style="height: 130px;">
                                    <div class="w-8 h-8 rounded-full bg-white/90 shadow-xs flex items-center justify-center mb-1">
                                        <i data-lucide="utensils" class="w-4 h-4 text-[#8B1A2C]"></i>
                                    </div>
                                    <span class="text-[9px] font-bold text-[#8B1A2C] line-clamp-1" x-text="item.name"></span>
                                </div>
                            </template>

                            <!-- Badges Floating on Image -->
                            <div class="absolute top-1.5 left-1.5 z-10 pointer-events-none">
                                <span class="text-[8px] sm:text-[9px] font-black px-1.5 py-0.5 rounded-md pos-nums shadow-xs bg-black/80 text-white tracking-wider" x-text="item.sku || 'ITEM'"></span>
                            </div>
                            <div class="absolute top-1.5 right-1.5 z-10 pointer-events-none" x-show="item.has_variants">
                                <span class="text-[8px] sm:text-[9px] font-bold px-1.5 py-0.5 rounded-md shadow-xs bg-[#8B1A2C] text-white">
                                    ভ্যারিয়েন্ট
                                </span>
                            </div>
                        </div>

                        <!-- Card Content (Uniform layout) -->
                        <div class="flex-1 flex flex-col justify-between w-full min-h-0">
                            <div>
                                <h3 class="text-xs sm:text-[13px] font-extrabold line-clamp-1 leading-tight text-[#1A0A0C]" x-text="item.name"></h3>
                                <p class="text-[10px] sm:text-[11px] font-medium line-clamp-1 mt-0.5 text-[#825E64]" x-text="item.bangla_name || ''"></p>
                            </div>

                            <!-- Price & Plus Button (Pinned to Bottom) -->
                            <div class="flex items-center justify-between pt-1.5 border-t border-[#F0E8E5] mt-auto">
                                <div>
                                    <span class="text-[8px] sm:text-[9px] font-medium block text-[#9B7A7E] leading-none mb-0.5">মূল্য:</span>
                                    <span class="text-xs sm:text-sm font-black text-[#8B1A2C] pos-nums">৳<span x-text="formatNumber(item.selling_price)"></span></span>
                                </div>
                                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg flex items-center justify-center transition-all bg-[#8B1A2C] text-white shadow-xs group-hover:scale-105 group-hover:bg-[#680C1B] shrink-0">
                                    <i data-lucide="plus" class="w-3.5 h-3.5 stroke-[3]"></i>
                                </div>
                            </div>
                        </div>
                    </button>
                </template>
            </div>
            <div x-show="filteredItems.length === 0" class="h-48 flex flex-col items-center justify-center" style="color:#C0A0A4;">
                <i data-lucide="search-x" class="w-8 h-8 mb-2 opacity-40"></i>
                <p class="text-xs font-semibold">কোনো আইটেম পাওয়া যায়নি!</p>
            </div>
        </div>

        <!-- Desktop Hotkeys Bar (Hidden on mobile) -->
        <div class="hidden sm:flex h-9 px-4 items-center justify-between text-[11px] shrink-0 border-t bg-white"
             style="border-color:#E0D4CF; color:#9B7A7E;">
            <div class="flex items-center gap-4">
                @foreach([['F2','নতুন'],['F3','টেবিল'],['F4','পে'],['F8','KOT'],['F9','স্প্লিট']] as $hk)
                <span><kbd class="px-1.5 py-0.5 rounded text-[10px] font-mono font-bold"
                           style="background:#FBF1F3; color:#8B1A2C; border:1px solid rgba(139,26,44,0.2);">{{ $hk[0] }}</kbd> {{ $hk[1] }}</span>
                @endforeach
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full" style="background:#8B1A2C;"></span>
                <span>NBR মূসক ৬.৩ কমপ্লায়েন্ট ({{ $currentBranch->default_vat_rate ?? 5 }}%)</span>
            </div>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════ -->
    <!-- MOBILE STICKY FLOATING CART BAR (Mobile Only: < sm)     -->
    <!-- ════════════════════════════════════════════════════════ -->
    <div x-show="cart.length > 0" x-cloak class="sm:hidden fixed bottom-[60px] left-3 right-3 z-30">
        <button @click="mobileCartOpen = true"
                class="btn-maroon w-full p-3 rounded-2xl flex items-center justify-between shadow-2xl animate-bounce-subtle">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-xl flex items-center justify-center font-black text-xs" style="background: #D4AC50; color:#3D0A12;">
                    <span x-text="cart.reduce((s,i)=>s+i.quantity,0)"></span>
                </div>
                <div class="text-left leading-tight">
                    <p class="text-xs font-black text-white">কার্ট দেখুন</p>
                    <p class="text-[10px] text-white/80" x-text="orderType === 'dine_in' ? (selectedTable ? selectedTable.name : 'ডাইন-ইন') : (orderType === 'takeaway' ? 'পার্সেল' : 'ডেলিভারি')"></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-black pos-nums text-white">৳<span x-text="formatNumber(grandTotal)"></span></span>
                <i data-lucide="chevron-up" class="w-4 h-4 text-white"></i>
            </div>
        </button>
    </div>

    <!-- ════════════════════════════════════════════════════════ -->
    <!-- RIGHT: Desktop Cart Panel (Forced Width 530px)           -->
    <!-- ════════════════════════════════════════════════════════ -->
    <div class="hidden sm:flex h-full flex-col shrink-0 bg-white border-l z-20 overflow-hidden"
         style="width: 530px; min-width: 480px; max-width: 45vw; border-color:#E0D4CF;">
        @include('pos.partials.cart_panel')
    </div>

    <!-- ════════════════════════════════════════════════════════ -->
    <!-- MOBILE: Smartphone Slide-Up Cart Drawer (< 640px)        -->
    <!-- ════════════════════════════════════════════════════════ -->
    <div x-show="mobileCartOpen" x-cloak class="sm:hidden fixed inset-0 z-50 flex flex-col bg-white overflow-hidden">
        @include('pos.partials.cart_panel')
    </div>

    <!-- ════ FLOATING INCOMING WAITER ORDER BANNER ════ -->
    <div x-show="incomingWaiterOrder" x-cloak x-transition
         class="fixed top-4 right-4 z-50 bg-white border-2 border-emerald-500 rounded-3xl shadow-2xl p-4 max-w-sm flex flex-col gap-2.5">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-emerald-800 font-black text-xs">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span>🔔 ওয়েটার থেকে নতুন অর্ডার এসেছে!</span>
            </div>
            <button @click="incomingWaiterOrder = null" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="bg-emerald-50/80 p-3 rounded-2xl border border-emerald-100 text-xs space-y-0.5">
            <p class="font-bold text-gray-900">টেবিল: <span class="font-black text-rose-900" x-text="incomingWaiterOrder?.table_name"></span></p>
            <p class="text-[11px] text-gray-600 font-medium">অর্ডার গ্রহণকারী: <span class="font-bold text-gray-800" x-text="incomingWaiterOrder?.waiter_name || 'ওয়েটার'"></span></p>
            <p class="text-[11px] text-gray-600 font-medium">অর্ডার নং: <span class="font-mono font-bold text-gray-900" x-text="incomingWaiterOrder?.order?.order_number"></span></p>
        </div>
        <div class="flex items-center gap-2 pt-1">
            <button @click="loadIncomingOrder()" class="btn-maroon flex-1 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 shadow-md">
                <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                <span>কার্টে লোড ও KOT প্রিন্ট</span>
            </button>
        </div>
    </div>

    <!-- ════ MODAL 1: VARIANT / MODIFIERS ════ -->
    <div x-show="openModifierModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-backdrop">
        <div @click.outside="openModifierModal = false"
             class="w-full max-w-md rounded-3xl overflow-hidden shadow-2xl border bg-white max-h-[90vh] flex flex-col"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background:#FBF8F5; border-color:#E0D4CF;">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 shrink-0 border border-black/5 flex items-center justify-center">
                        <template x-if="activeItem?.image">
                            <img :src="activeItem.image" :alt="activeItem.name" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!activeItem?.image">
                            <i data-lucide="utensils" class="w-5 h-5 opacity-40" style="color:#8B1A2C;"></i>
                        </template>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold" style="color:#1A0A0C;" x-text="activeItem?.name"></h3>
                        <p class="text-xs font-medium" style="color:#B8922A;" x-text="activeItem?.bangla_name"></p>
                    </div>
                </div>
                <button @click="openModifierModal = false" style="color:#9B7A7E;">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-4 overflow-y-auto space-y-4 flex-1">
                <!-- Variants -->
                <div x-show="activeItem?.variants?.length > 0">
                    <label class="section-heading">সাইজ / অংশ নির্বাচন</label>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="v in activeItem?.variants" :key="v.id">
                            <button @click="selectedVariant = v"
                                    class="p-2.5 rounded-xl border text-left flex items-center justify-between transition-all"
                                    :style="selectedVariant?.id === v.id ? 'background:rgba(139,26,44,0.08); border-color:#8B1A2C;' : 'background:#F8F5F2; border-color:#E8DDD9;'">
                                <span class="text-xs font-bold" style="color:#1A0A0C;" x-text="v.name"></span>
                                <span class="text-xs pos-nums font-black price-maroon">৳<span x-text="formatNumber(v.price)"></span></span>
                            </button>
                        </template>
                    </div>
                </div>
                <!-- Modifiers -->
                <div x-show="activeItem?.modifiers?.length > 0">
                    <label class="section-heading">অতিরিক্ত অপশন</label>
                    <div class="space-y-2">
                        <template x-for="m in activeItem?.modifiers" :key="m.id">
                            <label class="p-2.5 rounded-xl border flex items-center justify-between cursor-pointer transition-all"
                                   :style="selectedModifiers.includes(m.id) ? 'background:rgba(139,26,44,0.06); border-color:#8B1A2C;' : 'background:#F8F5F2; border-color:#E8DDD9;'">
                                <div class="flex items-center gap-2.5">
                                    <input type="checkbox" :value="m.id" x-model="selectedModifiers" class="rounded" style="accent-color:#8B1A2C;">
                                    <span class="text-xs font-bold" style="color:#1A0A0C;" x-text="m.name"></span>
                                </div>
                                <span class="text-xs pos-nums font-bold price-maroon" x-text="m.price > 0 ? '+৳' + formatNumber(m.price) : 'ফ্রি'"></span>
                            </label>
                        </template>
                    </div>
                </div>
                <!-- Note -->
                <div>
                    <label class="section-heading">স্পেশাল কিচেন নোট</label>
                    <input type="text" x-model="itemCustomNote" placeholder="উদাঃ কম ঝাল, আলাদা প্লেট দিন"
                           class="pos-input w-full rounded-xl px-3 py-2 text-xs">
                </div>
            </div>
            <div class="p-4 border-t flex items-center justify-between" style="border-color:#E0D4CF; background:#FBF8F5;">
                <div>
                    <span class="text-xs" style="color:#9B7A7E;">মোট মূল্য:</span>
                    <p class="text-base pos-nums font-black price-maroon">৳<span x-text="formatNumber(calculatedModalPrice)"></span></p>
                </div>
                <button @click="confirmAddCustomizedItem()" class="btn-maroon px-6 py-2.5 text-xs">কার্টে যোগ করুন</button>
            </div>
        </div>
    </div>

    <!-- ════ MODAL 2: TABLE SELECTOR ════ -->
    <div x-show="openTableModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-backdrop">
        <div @click.outside="openTableModal = false"
             class="w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl border bg-white max-h-[90vh] flex flex-col"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background:#FBF8F5; border-color:#E0D4CF;">
                <div class="flex items-center gap-2">
                    <i data-lucide="layout-grid" class="w-5 h-5" style="color:#B8922A;"></i>
                    <h3 class="text-sm font-bold" style="color:#1A0A0C;">রেস্টুরেন্ট ফ্লোর ও টেবিল নির্বাচন</h3>
                </div>
                <button @click="openTableModal = false" style="color:#9B7A7E;">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <!-- Floor Tabs -->
            <div class="p-3 border-b flex items-center gap-2 overflow-x-auto" style="border-color:#E0D4CF; background:#FBF8F5;">
                <button @click="selectedFloor = 'all'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all"
                        :style="selectedFloor === 'all' ? 'background:#8B1A2C; color:#fff;' : 'background:#F0E8E5; color:#5C3840;'">
                    সকল ফ্লোর
                </button>
                <template x-for="floor in uniqueFloors" :key="floor">
                    <button @click="selectedFloor = floor"
                            class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition-all"
                            :style="selectedFloor === floor ? 'background:#8B1A2C; color:#fff;' : 'background:#F0E8E5; color:#5C3840;'"
                            x-text="floor"></button>
                </template>
            </div>
            <!-- Tables Grid -->
            <div class="p-4 overflow-y-auto flex-1" style="background:#F8F5F2;">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2.5 sm:gap-3">
                    <template x-for="t in filteredTables" :key="t.id">
                        <button @click="selectTable(t)"
                                class="p-3 sm:p-3.5 rounded-2xl border flex flex-col items-center justify-center gap-1 transition-all text-center relative hover:scale-[1.02] cursor-pointer"
                                :style="t.status === 'occupied' ? 'background:#FEE2E2; border-color:#FCA5A5; color:#991B1B;'
                                       : t.status === 'billed' ? 'background:#FEF3C7; border-color:#FCD34D; color:#92400E;'
                                       : 'background:#FFFFFF; border-color:#E8DDD9; color:#1A0A0C;'">
                            <div class="w-2.5 h-2.5 rounded-full absolute top-2 right-2"
                                 :style="t.status==='occupied' ? 'background:#EF4444;' : t.status==='billed' ? 'background:#F59E0B;' : 'background:#22C55E;'"></div>
                            <i data-lucide="utensils" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                            <span class="text-xs sm:text-sm font-black pos-nums" x-text="t.name"></span>
                            <span class="text-[9px] sm:text-[10px]" x-text="t.capacity + ' জন'"></span>
                            <div class="flex items-center gap-1 flex-wrap justify-center">
                                <span class="text-[8px] sm:text-[9px] font-bold px-1.5 py-0.2 rounded"
                                      :style="t.status==='occupied' ? 'background:#FEE2E2; color:#991B1B;' : t.status==='billed' ? 'background:#FEF3C7; color:#92400E;' : 'background:#D1FAE5; color:#065F46;'"
                                      x-text="t.status==='occupied' ? 'খাচ্ছে' : t.status==='billed' ? 'বিল সম্পন্ন' : 'খালি আছে'"></span>
                                <template x-if="t.active_orders && t.active_orders.length > 1">
                                    <span class="text-[8px] font-black px-1.5 py-0.2 rounded" style="background:#8B1A2C; color:#fff;"
                                          x-text="t.active_orders.length + ' কাস্টমার'"></span>
                                </template>
                            </div>
                            <template x-if="t.status === 'occupied' && (t.current_order || (t.active_orders && t.active_orders.length > 0))">
                                <span class="text-[9px] font-black pos-nums px-1.5 py-0.5 rounded" style="background:#EF4444; color:#fff;">
                                    বিল: ৳<span x-text="formatNumber(getTableTotal(t))"></span>
                                </span>
                            </template>
                        </button>
                    </template>
                </div>
            </div>
            <div class="p-3 border-t flex items-center justify-center gap-4 sm:gap-6 text-[10px] sm:text-[11px]" style="border-color:#E0D4CF; background:#FBF8F5; color:#9B7A7E;">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full" style="background:#22C55E;"></span> খালি</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full" style="background:#EF4444;"></span> অকুপাইড</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full" style="background:#F59E0B;"></span> বিল সম্পন্ন</span>
            </div>
        </div>
    </div>

    <!-- ════ MODAL 3: QUICK PAYMENT (Pixel-Perfect Stitch Design) ════ -->
    <div x-show="openPaymentModal" x-cloak
         @keydown.enter.window="if(openPaymentModal && !isProcessing) { $event.preventDefault(); processPaymentAndPrint(); }"
         class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 modal-backdrop">
        <div @click.outside="openPaymentModal = false"
             class="w-full max-w-sm sm:max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border flex flex-col max-h-[98vh]"
             style="border-color:#D1D5DB;">

            <!-- Header -->
            <div class="px-4 py-3 border-b flex items-center justify-between shrink-0 bg-white" style="border-color:#E5E7EB;">
                <button @click="openPaymentModal = false" class="text-gray-700 hover:text-black p-1">
                    <i data-lucide="chevron-left" class="w-6 h-6 stroke-[2.5]"></i>
                </button>
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Quick Payment</h3>
                <button @click="openPaymentModal = false" class="text-gray-700 hover:text-black p-1">
                    <i data-lucide="menu" class="w-6 h-6 stroke-[2]"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-3.5 sm:p-4 space-y-3.5 overflow-y-auto flex-1 bg-white">

                <!-- Total Amount Card -->
                <div class="bg-[#F8FAFC] rounded-2xl p-3.5 sm:p-4 border border-gray-200 text-center shadow-2xs">
                    <p class="text-xs font-semibold text-gray-600 mb-0.5">Total Amount</p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">৳ <span x-text="formatNumber(grandTotal)"></span></p>
                </div>

                <!-- Payment Methods Grid (4 Cards in a Row) -->
                <div class="grid grid-cols-4 gap-2">
                    <!-- Cash Card -->
                    <button @click="paymentMethod = 'cash'; paidAmount = grandTotal;"
                            class="p-2.5 rounded-2xl border flex flex-col items-center justify-center gap-1.5 transition-all text-center"
                            :class="paymentMethod === 'cash' ? 'bg-[#F0FDF4] border-[#16A34A] shadow-xs' : 'bg-white border-gray-200 hover:border-gray-300'">
                        <i data-lucide="banknote" class="w-6 h-6" :class="paymentMethod === 'cash' ? 'text-[#16A34A]' : 'text-gray-800'"></i>
                        <span class="text-xs font-semibold text-gray-800">Cash</span>
                    </button>

                    <!-- Card -->
                    <button @click="paymentMethod = 'card'; paidAmount = grandTotal;"
                            class="p-2.5 rounded-2xl border flex flex-col items-center justify-center gap-1.5 transition-all text-center"
                            :class="paymentMethod === 'card' ? 'bg-[#F0FDF4] border-[#16A34A] shadow-xs' : 'bg-white border-gray-200 hover:border-gray-300'">
                        <i data-lucide="credit-card" class="w-6 h-6" :class="paymentMethod === 'card' ? 'text-[#16A34A]' : 'text-gray-800'"></i>
                        <span class="text-xs font-semibold text-gray-800">Card</span>
                    </button>

                    <!-- Bkash -->
                    <button @click="paymentMethod = 'bkash'; paidAmount = grandTotal;"
                            class="p-2.5 rounded-2xl border flex flex-col items-center justify-center gap-1.5 transition-all text-center"
                            :class="paymentMethod === 'bkash' ? 'bg-[#FDF2F8] border-[#DB2777] shadow-xs' : 'bg-white border-gray-200 hover:border-gray-300'">
                        <i data-lucide="smartphone" class="w-6 h-6" :class="paymentMethod === 'bkash' ? 'text-[#DB2777]' : 'text-gray-800'"></i>
                        <span class="text-xs font-semibold text-gray-800">Bkash</span>
                    </button>

                    <!-- Nagad -->
                    <button @click="paymentMethod = 'nagad'; paidAmount = grandTotal;"
                            class="p-2.5 rounded-2xl border flex flex-col items-center justify-center gap-1.5 transition-all text-center"
                            :class="paymentMethod === 'nagad' ? 'bg-[#FFFBEB] border-[#D97706] shadow-xs' : 'bg-white border-gray-200 hover:border-gray-300'">
                        <i data-lucide="wallet" class="w-6 h-6" :class="paymentMethod === 'nagad' ? 'text-[#D97706]' : 'text-gray-800'"></i>
                        <span class="text-xs font-semibold text-gray-800">Nagad</span>
                    </button>
                </div>

                <!-- Given Amount & Change Display Box -->
                <div class="p-3 bg-[#F8FAFC] rounded-2xl border border-gray-200 flex items-center justify-between shadow-2xs">
                    <div class="flex-1">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block tracking-wider mb-0.5">GIVEN AMOUNT</span>
                        <input type="number" x-model.number="paidAmount"
                               @keydown.enter.prevent="processPaymentAndPrint()"
                               class="w-full text-lg font-bold text-gray-900 bg-transparent border-b border-gray-300 focus:outline-none pb-0.5">
                    </div>
                    <div class="text-right shrink-0 pl-4">
                        <span class="text-[10px] font-bold text-gray-500 uppercase block tracking-wider mb-0.5">CHANGE</span>
                        <span class="text-lg font-bold text-[#16A34A] block">
                            ৳ <span x-text="formatNumber(Math.max(0, changeAmount))"></span>
                        </span>
                    </div>
                </div>

                <!-- 3x4 On-Screen Keypad Grid (Exact Stitch Keypad) -->
                <div class="grid grid-cols-3 gap-2">
                    <button @click="appendKeypadDigit('1')" class="py-3 bg-white rounded-2xl border border-gray-200 text-xl font-bold text-gray-900 shadow-2xs active:bg-gray-100 transition-all">1</button>
                    <button @click="appendKeypadDigit('2')" class="py-3 bg-white rounded-2xl border border-gray-200 text-xl font-bold text-gray-900 shadow-2xs active:bg-gray-100 transition-all">2</button>
                    <button @click="appendKeypadDigit('3')" class="py-3 bg-white rounded-2xl border border-gray-200 text-xl font-bold text-gray-900 shadow-2xs active:bg-gray-100 transition-all">3</button>

                    <button @click="appendKeypadDigit('4')" class="py-3 bg-white rounded-2xl border border-gray-200 text-xl font-bold text-gray-900 shadow-2xs active:bg-gray-100 transition-all">4</button>
                    <button @click="appendKeypadDigit('5')" class="py-3 bg-white rounded-2xl border border-gray-200 text-xl font-bold text-gray-900 shadow-2xs active:bg-gray-100 transition-all">5</button>
                    <button @click="appendKeypadDigit('6')" class="py-3 bg-white rounded-2xl border border-gray-200 text-xl font-bold text-gray-900 shadow-2xs active:bg-gray-100 transition-all">6</button>

                    <button @click="appendKeypadDigit('7')" class="py-3 bg-white rounded-2xl border border-gray-200 text-xl font-bold text-gray-900 shadow-2xs active:bg-gray-100 transition-all">7</button>
                    <button @click="appendKeypadDigit('8')" class="py-3 bg-white rounded-2xl border border-gray-200 text-xl font-bold text-gray-900 shadow-2xs active:bg-gray-100 transition-all">8</button>
                    <button @click="deleteKeypadDigit()" class="py-3 bg-white rounded-2xl border border-gray-200 flex items-center justify-center text-gray-800 shadow-2xs active:bg-gray-100 transition-all">
                        <i data-lucide="delete" class="w-6 h-6"></i>
                    </button>

                    <!-- Blank button matching Stitch Reference 1 -->
                    <button @click="appendKeypadDigit('00')" class="py-3 bg-white rounded-2xl border border-gray-200 text-sm font-bold text-gray-400 shadow-2xs active:bg-gray-100 transition-all">00</button>
                    <button @click="appendKeypadDigit('0')" class="py-3 bg-white rounded-2xl border border-gray-200 text-xl font-bold text-gray-900 shadow-2xs active:bg-gray-100 transition-all">0</button>
                    <button @click="processPaymentAndPrint()" class="py-3 bg-[#16A34A] hover:bg-[#15803D] text-white rounded-2xl border border-emerald-600 text-base font-extrabold shadow-xs active:scale-95 transition-all">OK</button>
                </div>
            </div>

            <!-- Complete Payment Green Button -->
            <div class="p-3.5 bg-white border-t shrink-0 border-gray-200">
                <button @click="processPaymentAndPrint()" :disabled="isProcessing"
                        class="w-full py-3.5 rounded-2xl text-base font-bold text-white flex items-center justify-center gap-2 shadow-lg cursor-pointer transition-all active:scale-[0.98] disabled:opacity-50"
                        style="background-color: #25A25A;">
                    <i data-lucide="check-circle-2" class="w-5 h-5 stroke-[2.5]"></i>
                    <span x-text="isProcessing ? 'Processing Payment...' : 'Complete Payment'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- ════ MODAL 4: MUSHAK RECEIPT ════ -->
    <div x-show="openMushakModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-backdrop">
        <div @click.outside="openMushakModal = false"
             class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border"
             style="border-color:#E8DDD9;">
            <div class="p-3 border-b flex items-center justify-between shrink-0" style="background:#FBF8F5; border-color:#E0D4CF;">
                <span class="text-xs font-bold" style="color:#1A0A0C;">NBR মূসক-৬.৩ কর চালানপত্র</span>
                <div class="flex items-center gap-2">
                    <button @click="printReceipt()" class="px-3 py-1.5 rounded-lg text-white font-bold text-xs flex items-center gap-1" style="background:#8B1A2C;">
                        <i data-lucide="printer" class="w-3.5 h-3.5"></i> প্রিন্ট
                    </button>
                    <button @click="openMushakModal = false" style="color:#9B7A7E;"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
            </div>
            <div id="thermalReceipt" class="p-4 overflow-y-auto font-mono text-[11px] leading-tight space-y-2 select-text">
                <div class="text-center pb-2 border-b border-dashed border-gray-300">
                    <p class="text-[9px] font-bold">গণপ্রজাতন্ত্রী বাংলাদেশ সরকার · জাতীয় রাজস্ব বোর্ড</p>
                    <p class="text-xs font-black uppercase mt-1">কর চালানপত্র (মূসক-৬.৩)</p>
                </div>
                <div class="text-center pb-2 border-b border-dashed border-gray-300">
                    <p class="font-black text-sm" x-text="mushakData?.branch?.name"></p>
                    <p class="text-[9px]" x-text="mushakData?.branch?.address"></p>
                    <p class="text-[10px] font-bold mt-0.5">BIN: <span x-text="mushakData?.branch?.bin"></span></p>
                </div>
                <div class="pb-2 border-b border-dashed border-gray-300 space-y-0.5 text-[10px]">
                    <div class="flex justify-between"><span>চালান নং:</span><span class="font-bold" x-text="mushakData?.invoice?.mushak_no"></span></div>
                    <div class="flex justify-between"><span>অর্ডার নং:</span><span x-text="mushakData?.invoice?.order_no"></span></div>
                    <div class="flex justify-between"><span>তারিখ:</span><span x-text="mushakData?.invoice?.date + ' ' + mushakData?.invoice?.time"></span></div>
                    <div class="flex justify-between"><span>ক্যাশিয়ার:</span><span x-text="mushakData?.invoice?.cashier_name"></span></div>
                </div>
                <div class="pb-2 border-b border-dashed border-gray-300">
                    <div class="flex justify-between font-bold text-[10px] border-b pb-1 mb-1">
                        <span class="w-1/2">আইটেম</span><span class="w-1/6 text-center">পরিমাণ</span><span class="w-1/3 text-right">মূল্য (৳)</span>
                    </div>
                    <template x-for="it in mushakData?.items" :key="it.sl">
                        <div class="flex justify-between text-[10px] py-0.5">
                            <span class="w-1/2 truncate" x-text="it.name"></span>
                            <span class="w-1/6 text-center font-bold" x-text="it.quantity"></span>
                            <span class="w-1/3 text-right font-bold" x-text="formatNumber(it.total_price)"></span>
                        </div>
                    </template>
                </div>
                <div class="space-y-1 text-[10px] pb-2 border-b border-dashed border-gray-300">
                    <div class="flex justify-between"><span>সাবটোটাল:</span><span>৳<span x-text="formatNumber(mushakData?.summary?.subtotal)"></span></span></div>
                    <div class="flex justify-between font-bold"><span>মূসক ভ্যাট (<span x-text="mushakData?.summary?.vat_percent"></span>%):</span><span>৳<span x-text="formatNumber(mushakData?.summary?.vat_amount)"></span></span></div>
                    <div class="flex justify-between text-sm font-black pt-1 border-t border-gray-300"><span>সর্বমোট:</span><span>৳<span x-text="formatNumber(mushakData?.summary?.grand_total)"></span></span></div>
                </div>
                <div class="flex flex-col items-center pt-2">
                    <div id="qrcodeCanvas" class="p-1 bg-white border border-gray-200 inline-block"></div>
                    <p class="text-[9px] text-gray-500 mt-1">NBR QR ভেরিফিকেশন</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ════ MODAL 4B: KITCHEN KOT TOKEN RECEIPT ════ -->
    <div x-show="openKotModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-backdrop"
         @click.self="openKotModal = false">
        <div class="w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border"
             style="border-color:#E8DDD9;"
             @click.stop>
            <div class="p-3.5 border-b flex items-center justify-between shrink-0" style="background:#FBF8F5; border-color:#E0D4CF;">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:rgba(46,125,82,0.15);">
                        <i data-lucide="chef-hat" class="w-4 h-4 text-emerald-700"></i>
                    </div>
                    <span class="text-xs font-bold" style="color:#1A0A0C;">কিচেন KOT টোকেন স্লিপ</span>
                </div>
                <button type="button" @click="openKotModal = false"
                        class="w-7 h-7 rounded-lg flex items-center justify-center hover:bg-gray-200 transition-colors text-gray-500 hover:text-gray-900">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="thermalKotReceipt" class="p-4 overflow-y-auto font-mono text-[11px] leading-tight space-y-2 select-text bg-white flex-1">
                <div class="text-center pb-2 border-b-2 border-dashed border-gray-800">
                    <p class="text-xs font-black uppercase">{{ $currentBranch->restaurant_name ?? "Sultan's Dine" }}</p>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-700">কিচেন অর্ডার টোকেন (KOT SLIP)</p>
                </div>

                <!-- Big Bold Token & Table Box -->
                <div class="text-center py-2.5 px-2 bg-gray-50 border-2 border-dashed border-gray-800 rounded-xl my-1">
                    <p class="text-[10px] font-extrabold uppercase text-gray-600">টোকেন নম্বর / TOKEN NO</p>
                    <p class="text-3xl font-black pos-nums text-black my-0.5">#<span x-text="kotPrintData?.token_number"></span></p>
                    <p class="text-xs font-black mt-1 text-black"
                       x-text="kotPrintData?.table_name ? ('টেবিল: ' + kotPrintData.table_name) : (kotPrintData?.order_type === 'takeaway' ? 'পার্সেল (TAKEAWAY)' : 'ডেলিভারি (DELIVERY)')"></p>
                </div>

                <!-- Order Meta -->
                <div class="pb-2 border-b border-dashed border-gray-800 text-[10px] space-y-0.5">
                    <div class="flex justify-between"><span>KOT / অর্ডার নং:</span><span class="font-bold pos-nums" x-text="kotPrintData?.order_number"></span></div>
                    <div class="flex justify-between"><span>তারিখ ও সময়:</span><span class="font-bold pos-nums" x-text="kotPrintData?.time"></span></div>
                    <div class="flex justify-between"><span>ওয়েটার / সার্ভার:</span><span class="font-bold" x-text="kotPrintData?.waiter_name || '{{ $currentUser->name ?? "ক্যাশিয়ার" }}'"></span></div>
                    <template x-if="kotPrintData?.customer_name || kotPrintData?.customer_phone">
                        <div class="flex justify-between"><span>কাস্টমার:</span><span class="font-bold" x-text="(kotPrintData?.customer_name || '') + ' ' + (kotPrintData?.customer_phone || '')"></span></div>
                    </template>
                </div>

                <!-- Food Items List -->
                <div class="pb-2 border-b-2 border-dashed border-gray-800">
                    <div class="flex justify-between font-black text-xs border-b border-gray-800 pb-1 mb-1">
                        <span class="w-2/3">খাবার আইটেম (Item)</span>
                        <span class="w-1/3 text-right">পরিমাণ (Qty)</span>
                    </div>
                    <template x-for="(item, idx) in kotPrintData?.items" :key="idx">
                        <div class="py-1 border-b border-dotted border-gray-200">
                            <div class="flex justify-between items-start text-xs font-black">
                                <span class="w-2/3 leading-snug text-black" x-text="item.item_name || item.name"></span>
                                <span class="w-1/3 text-right font-black text-sm pos-nums text-black" x-text="item.quantity + 'x'"></span>
                            </div>
                            <template x-if="item.variant_name">
                                <p class="text-[10px] text-gray-700 italic">ভ্যারিয়েন্ট: <span x-text="item.variant_name"></span></p>
                            </template>
                            <template x-if="item.selected_modifiers && item.selected_modifiers.length > 0">
                                <p class="text-[10px] text-gray-700 italic">এড-অন: <span x-text="item.selected_modifiers.map(m=>m.name).join(', ')"></span></p>
                            </template>
                            <template x-if="item.notes">
                                <p class="text-[10px] font-bold text-red-700 bg-red-50 p-0.5 rounded mt-0.5">⚠️ নোট: <span x-text="item.notes"></span></p>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Total Count -->
                <div class="flex justify-between text-xs font-black pt-1">
                    <span>মোট আইটেম সংখ্যা:</span>
                    <span class="pos-nums" x-text="kotPrintData?.total_quantity + ' টি'"></span>
                </div>

                <div class="text-center text-[9px] font-bold pt-2 text-gray-600 border-t border-dashed border-gray-800">
                    *** কিচেন কপি (Kitchen Copy) ***
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="p-3 border-t flex items-center justify-between gap-2 shrink-0" style="border-color:#E0D4CF; background:#FBF8F5;">
                <button type="button" @click="openKotModal = false"
                        class="px-4 py-2.5 rounded-xl text-xs font-bold border border-gray-300 hover:bg-gray-100 transition-all text-gray-700">
                    ✕ বন্ধ করুন
                </button>
                <button type="button" @click="printKotReceipt()"
                        class="btn-maroon px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-md">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>🖨️ প্রিন্ট KOT</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ════ MODAL 5: AI VOICE ORDER ════ -->
    <div x-show="openAiModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-backdrop">
        <div @click.outside="openAiModal = false"
             class="w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl border bg-white"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background:linear-gradient(135deg,#5C0F1B,#8B1A2C); border-color:transparent;">
                <div class="flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4 text-white"></i>
                    <h3 class="text-sm font-bold text-white">Gemini AI ভয়েস ও টেক্সট অর্ডার</h3>
                </div>
                <button @click="openAiModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-4 space-y-3">
                <div class="relative">
                    <textarea x-model="aiPromptText" rows="3"
                              placeholder="উদাঃ টেবিল ৪ এ ২টা মাটন কাচ্চি ফুল আর ১টা বোরহানি পার্সেল দিন..."
                              class="pos-input w-full p-3 text-xs rounded-2xl resize-none font-medium"></textarea>
                    <button @click="toggleVoiceRecord()"
                            class="absolute right-3 bottom-3 p-2 rounded-xl border transition-all"
                            :style="isRecording ? 'background:#C02020; color:#fff; border-color:#C02020; animation:pulse 1s infinite;' : 'background:#FBF8F5; color:#8B1A2C; border-color:#D0BDB8;'">
                        <i data-lucide="mic" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="flex flex-wrap gap-1.5 text-[11px]">
                    <span style="color:#9B7A7E;">নমুনা:</span>
                    <button @click="aiPromptText = '২টা মাটন কাচ্চি ফুল আর ১টা বোরহানি দেন'"
                            class="px-2 py-0.5 rounded-md border text-xs transition-all"
                            style="background:#FBF1F3; color:#8B1A2C; border-color:rgba(139,26,44,0.2);">
                        "২টা মাটন কাচ্চি ফুল আর ১টা বোরহানি দেন"
                    </button>
                </div>
            </div>
            <div class="p-4 border-t flex items-center justify-between" style="border-color:#E0D4CF; background:#FBF8F5;">
                <span class="text-[11px]" style="color:#9B7A7E;">Gemini 3-Key Auto Failover</span>
                <button @click="processAiOrder()" :disabled="!aiPromptText || isAiLoading"
                        class="btn-maroon px-5 py-2.5 rounded-xl text-xs flex items-center gap-2 disabled:opacity-50">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span x-text="isAiLoading ? 'AI বিশ্লেষণ করছে...' : 'কার্টে যোগ করুন'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- ════ MODAL 6: SPLIT BILL ════ -->
    <div x-show="openSplitModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-backdrop">
        <div @click.outside="openSplitModal = false"
             class="w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl border bg-white max-h-[90vh] flex flex-col"
             style="border-color:#E0D4CF;">
            <!-- Header -->
            <div class="p-4 border-b flex items-center justify-between" style="background:linear-gradient(135deg,#5C0F1B,#8B1A2C);">
                <div class="flex items-center gap-2.5 text-white">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(212,172,80,0.25);">
                        <i data-lucide="split" class="w-4 h-4" style="color:#D4AC50;"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black">স্প্লিট বিল (বিল ভাগ করে গ্রহণ)</h3>
                        <p class="text-[10px] text-white/80">মোট প্রদেয়: <span class="font-black pos-nums text-white">৳<span x-text="formatNumber(grandTotal)"></span></span></p>
                    </div>
                </div>
                <button @click="openSplitModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <!-- Body -->
            <div class="p-4 overflow-y-auto space-y-4 flex-1" style="background:#F8F5F2;">
                <!-- Split Count Selector -->
                <div class="p-3 bg-white rounded-2xl border space-y-2" style="border-color:#E8DDD9;">
                    <div class="flex items-center justify-between">
                        <label class="section-heading mb-0">কত জনের মাঝে ভাগ করবেন?</label>
                        <span class="text-xs font-black pos-nums price-maroon" x-text="splitCount + ' জন ব্যক্তি'"></span>
                    </div>
                    <div class="grid grid-cols-5 gap-1.5">
                        <template x-for="n in [2, 3, 4, 5, 6]" :key="n">
                            <button @click="splitCount = n; calculateSplits();"
                                    class="py-2 rounded-xl text-xs font-bold transition-all border text-center"
                                    :style="splitCount === n ? 'background:#8B1A2C; color:#fff; border-color:#8B1A2C; box-shadow:0 2px 6px rgba(139,26,44,0.3);' : 'background:#F8F5F2; color:#5C3840; border-color:#E8DDD9;'"
                                    x-text="n + ' জন'"></button>
                        </template>
                    </div>
                </div>

                <!-- Persons Split Table -->
                <div class="space-y-2">
                    <label class="section-heading">ব্যক্তি-ভিত্তিক পেমেন্ট ও মেথড তালিকা</label>
                    <template x-for="(row, idx) in splitRows" :key="idx">
                        <div class="p-3 bg-white rounded-2xl border space-y-2 shadow-xs" style="border-color:#E8DDD9;">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black" style="color:#8B1A2C;" x-text="row.person_label"></span>
                                <div class="flex items-center gap-1">
                                    <span class="text-[11px] font-bold text-gray-500">টাকা: ৳</span>
                                    <input type="number" x-model.number="row.amount"
                                           class="pos-input w-24 px-2 py-1 text-xs pos-nums font-black text-right rounded-lg">
                                </div>
                            </div>
                            <!-- Payment Method for this split -->
                            <div class="grid grid-cols-4 gap-1 pt-1 border-t" style="border-color:#F0E8E5;">
                                <template x-for="pm in [['cash','ক্যাশ'],['bkash','বিকাশ'],['nagad','নগদ'],['card','কার্ড']]" :key="pm[0]">
                                    <button @click="row.payment_method = pm[0]"
                                            class="py-1 px-1 rounded-lg text-[10px] font-bold text-center transition-all border"
                                            :style="row.payment_method === pm[0] ? 'background:#8B1A2C; color:#fff; border-color:#8B1A2C;' : 'background:#F8F5F2; color:#5C3840; border-color:#E8DDD9;'"
                                            x-text="pm[1]"></button>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Total Distribution Status -->
                <div class="p-3 bg-white rounded-2xl border flex items-center justify-between text-xs" style="border-color:#E8DDD9;">
                    <div>
                        <p class="text-[10px] text-gray-500 font-bold">মোট ভাগ করা হয়েছে</p>
                        <p class="text-sm font-black pos-nums"
                           :style="splitTotalPaid === grandTotal ? 'color:#2E7D52;' : 'color:#C02020;'">
                            ৳<span x-text="formatNumber(splitTotalPaid)"></span>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-500 font-bold">অবশিষ্ট / ব্যবধান</p>
                        <p class="text-sm font-black pos-nums"
                           :style="(grandTotal - splitTotalPaid) === 0 ? 'color:#2E7D52;' : 'color:#C02020;'"
                           x-text="(grandTotal - splitTotalPaid) === 0 ? '✓ মিলে গেছে' : '৳' + formatNumber(grandTotal - splitTotalPaid)"></p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-4 border-t flex items-center justify-between" style="border-color:#E0D4CF; background:#FBF8F5;">
                <button @click="openSplitModal = false" class="px-4 py-2 rounded-xl text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="processSplitPaymentAndPrint()" :disabled="isProcessing || (grandTotal - splitTotalPaid) !== 0"
                        class="btn-maroon px-5 sm:px-6 py-2.5 rounded-2xl text-xs font-bold flex items-center gap-2 disabled:opacity-50 shadow-md">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span>স্প্লিট পেমেন্ট ও চালান প্রিন্ট</span>
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function posTerminal() {
    return {
        allItems: @json($categories->flatMap->items),
        categories: @json($categories),
        tables: @json($tables),
        searchQuery: '',
        selectedCategory: null,
        cart: [],
        orderType: 'dine_in',
        selectedTable: null,
        selectedFloor: 'all',
        selectedWaiterId: '',
        tokenNumber: Math.floor(10 + Math.random() * 90),
        discountType: 'fixed',
        discountValue: 0,
        vatRate: {{ $currentBranch->default_vat_rate ?? 5.0 }},
        isProcessing: false,
        mobileCartOpen: false,
        customerPhone: '',
        customerData: null,
        isNewCustomerBadge: false,
        redeemedPoints: 0,
        splitCount: 2,
        splitRows: [],
        openModifierModal: false, openTableModal: false, openPaymentModal: false,
        openMushakModal: false, openKotModal: false, openAiModal: false, openSplitModal: false,
        activeItem: null, selectedVariant: null, selectedModifiers: [], itemCustomNote: '',
        paymentMethod: 'cash', paidAmount: 0, trxId: '', mushakData: null, kotPrintData: null,
        aiPromptText: '', isRecording: false, isAiLoading: false, recognition: null,
        tableSyncChannel: null,
        currentLoadedOrderId: null,
        incomingWaiterOrder: null,

        init() {
            // Setup Cross-Window Real-Time BroadcastChannel for Tables & Orders
            if ('BroadcastChannel' in window) {
                this.tableSyncChannel = new BroadcastChannel('pos_table_sync_channel');
                this.tableSyncChannel.onmessage = (ev) => {
                    if (ev.data && ev.data.tables) {
                        this.applyLiveTables(ev.data.tables);
                    }
                    if (ev.data && ev.data.type === 'TABLE_UPDATED') {
                        this.updateSingleTableLocally(ev.data.table_id, ev.data.status, ev.data.order);
                        if (ev.data.status === 'occupied' && ev.data.order) {
                            this.incomingWaiterOrder = {
                                table_id: ev.data.table_id,
                                table_name: ev.data.table_name || ('Table ' + ev.data.table_id),
                                waiter_name: ev.data.waiter_name || 'ওয়েটার',
                                order: ev.data.order
                            };
                            window.playBeep(1200, 250);
                            if (this.selectedTable && this.selectedTable.id == ev.data.table_id) {
                                this.loadExistingOrder(ev.data.order);
                            }
                        }
                    }
                };
            }

            // Cross-window storage fallback for offline and multi-tab sync
            window.addEventListener('storage', (ev) => {
                if (ev.key === 'pos_table_sync_event' && ev.newValue) {
                    try {
                        const payload = JSON.parse(ev.newValue);
                        if (payload.tables) {
                            this.applyLiveTables(payload.tables);
                        }
                        if (payload.table_id) {
                            this.updateSingleTableLocally(payload.table_id, payload.status, payload.order);
                            if (payload.status === 'occupied' && payload.order) {
                                this.incomingWaiterOrder = {
                                    table_id: payload.table_id,
                                    table_name: payload.table_name || ('Table ' + payload.table_id),
                                    waiter_name: payload.waiter_name || 'ওয়েটার',
                                    order: payload.order
                                };
                                window.playBeep(1200, 250);
                                if (this.selectedTable && this.selectedTable.id == payload.table_id) {
                                    this.loadExistingOrder(payload.order);
                                }
                            }
                        }
                    } catch(e) {}
                }
            });

            // Start silent live background heartbeat polling (every 3 seconds)
            setInterval(() => {
                if (!document.hidden && !this.isProcessing) {
                    this.pollLiveTableStatuses();
                }
            }, 3000);

            window.addEventListener('keydown', (e) => {
                if (e.key==='F2'){ e.preventDefault(); this.resetOrder(true); }
                if (e.key==='F3'){ e.preventDefault(); this.openTableModal=true; }
                if (e.key==='F4'){ e.preventDefault(); if(this.cart.length>0) this.openPaymentModal=true; }
                if (e.key==='F8'){ e.preventDefault(); if(this.cart.length>0) this.sendKOT(); }
                if (e.key==='F9'){ e.preventDefault(); if(this.cart.length>0) this.openSplitBillModal(); }
                if (e.key==='/'&&document.activeElement!==this.$refs.searchInput){ e.preventDefault(); this.$refs.searchInput.focus(); }
                if (e.key==='Escape') this.closeAllModals();
            });
            if ('webkitSpeechRecognition' in window||'SpeechRecognition' in window) {
                const SR = window.SpeechRecognition||window.webkitSpeechRecognition;
                this.recognition = new SR();
                this.recognition.lang='bn-BD'; this.recognition.continuous=false;
                this.recognition.onresult=(ev)=>{ this.aiPromptText=ev.results[0][0].transcript; this.isRecording=false; };
                this.recognition.onerror=()=>this.isRecording=false;
                this.recognition.onend=()=>this.isRecording=false;
            }

            // Check if table_id is passed in URL (e.g. from Tables page)
            const urlParams = new URLSearchParams(window.location.search);
            const tableId = urlParams.get('table_id');
            if (tableId) {
                const matchedTable = this.tables.find(t => t.id == tableId);
                if (matchedTable) {
                    this.selectTable(matchedTable);
                }
            }

            this.$nextTick(()=>window.initLucideIcons());
        },

        applyLiveTables(newTables) {
            if (!Array.isArray(newTables)) return;
            this.tables = newTables;
            if (this.selectedTable) {
                const updated = this.tables.find(t => t.id === this.selectedTable.id);
                if (updated) {
                    this.selectedTable = updated;
                }
            }
            this.$nextTick(() => window.initLucideIcons());
        },

        updateSingleTableLocally(tableId, status, currentOrder = null) {
            if (!tableId) return;
            const t = this.tables.find(x => x.id == tableId);
            if (t) {
                t.status = status;
                t.current_order = currentOrder;
                t.current_order_id = currentOrder ? currentOrder.id : null;
            }
            if (this.selectedTable && this.selectedTable.id == tableId) {
                this.selectedTable.status = status;
                this.selectedTable.current_order = currentOrder;
                this.selectedTable.current_order_id = currentOrder ? currentOrder.id : null;
                if (status === 'available') {
                    this.selectedTable = null;
                }
            }
            this.$nextTick(() => window.initLucideIcons());
        },

        broadcastTableChange(tables, singleTableId = null, singleStatus = null, singleOrder = null) {
            const payload = {
                type: 'TABLE_UPDATED',
                tables: tables || this.tables,
                table_id: singleTableId,
                status: singleStatus,
                order: singleOrder,
                timestamp: Date.now()
            };
            if (this.tableSyncChannel) {
                try { this.tableSyncChannel.postMessage(payload); } catch(e) {}
            }
            try {
                localStorage.setItem('pos_table_sync_event', JSON.stringify(payload));
            } catch(e) {}
        },

        async pollLiveTableStatuses() {
            try {
                const res = await fetch('{{ route('pos.tablesLive') }}');
                const data = await res.json();
                if (data.success && Array.isArray(data.tables)) {
                    this.applyLiveTables(data.tables);
                }
            } catch(e) {}
        },
        closeAllModals() { this.openModifierModal=this.openTableModal=this.openPaymentModal=this.openMushakModal=this.openKotModal=this.openAiModal=this.openSplitModal=false; },
        appendKeypadDigit(digit) {
            let str = (this.paidAmount || 0).toString();
            if (str === '0') str = '';
            str += digit;
            this.paidAmount = parseFloat(str) || 0;
        },
        deleteKeypadDigit() {
            let str = (this.paidAmount || 0).toString();
            str = str.slice(0, -1);
            this.paidAmount = parseFloat(str) || 0;
        },
        get filteredItems() {
            return this.allItems.filter(item=>{
                const mc = this.selectedCategory===null||item.category_id===this.selectedCategory;
                const q=this.searchQuery.toLowerCase();
                const ms=!q||item.name.toLowerCase().includes(q)||(item.bangla_name&&item.bangla_name.toLowerCase().includes(q))||(item.sku&&item.sku.toLowerCase().includes(q));
                return mc&&ms;
            });
        },
        get uniqueFloors() { return [...new Set(this.tables.map(t=>t.floor_name))]; },
        get filteredTables() { return this.selectedFloor==='all'?this.tables:this.tables.filter(t=>t.floor_name===this.selectedFloor); },
        handleItemClick(item) {
            if (item.has_variants||(item.modifiers&&item.modifiers.length>0)) {
                this.activeItem=item;
                this.selectedVariant=item.variants&&item.variants.length>0?item.variants[0]:null;
                this.selectedModifiers=[]; this.itemCustomNote=''; this.openModifierModal=true;
                this.$nextTick(()=>window.initLucideIcons());
            } else { this.addToCart(item,null,[],''); }
        },
        get calculatedModalPrice() {
            if(!this.activeItem) return 0;
            let p=this.selectedVariant?this.selectedVariant.price:this.activeItem.selling_price;
            this.selectedModifiers.forEach(id=>{const m=this.activeItem.modifiers.find(x=>x.id===id); if(m) p+=m.price;});
            return p;
        },
        confirmAddCustomizedItem() {
            const mods=this.selectedModifiers.map(id=>this.activeItem.modifiers.find(m=>m.id===id)).filter(Boolean);
            this.addToCart(this.activeItem,this.selectedVariant,mods,this.itemCustomNote);
            this.openModifierModal=false;
        },
        kotPrinted: false,
        get isOccupiedTable() { return this.selectedTable && this.selectedTable.status === 'occupied' && this.selectedTable.current_order; },
        get newItemsCount() { return this.cart.filter(i => !i.is_existing).length; },
        get isAllKotPrinted() {
            return this.cart.length > 0 && this.newItemsCount === 0 && (this.isOccupiedTable || this.kotPrinted);
        },
        prepareKotPrintData() {
            const currentOrder = this.selectedTable?.current_order || (this.selectedTable?.active_orders ? this.selectedTable.active_orders[0] : null);
            this.kotPrintData = {
                token_number: currentOrder?.token_number || this.tokenNumber || Math.floor(10 + Math.random() * 90),
                order_number: currentOrder?.order_number || ('ORD-' + (currentOrder?.token_number || this.tokenNumber)),
                order_type: currentOrder?.order_type || this.orderType || 'dine_in',
                table_name: this.selectedTable?.name || currentOrder?.table?.name || 'Takeaway',
                waiter_name: currentOrder?.waiter?.name || this.selectedWaiterName || null,
                customer_name: currentOrder?.customer?.name || this.customerData?.name || null,
                customer_phone: currentOrder?.customer_phone || this.customerPhone || null,
                time: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }) + ', ' + new Date().toLocaleDateString('en-GB'),
                items: this.cart.map(c => ({
                    item_name: c.name,
                    variant_name: c.variant_name,
                    quantity: c.quantity,
                    selected_modifiers: c.selected_modifiers || [],
                    notes: c.notes || ''
                })),
                total_quantity: this.cart.reduce((sum, i) => sum + i.quantity, 0),
            };
        },
        handleKotButtonClick() {
            if (this.cart.length === 0) return;

            // If there are new unsaved items, send them to server & print
            if (this.newItemsCount > 0 || !this.currentLoadedOrderId) {
                this.sendKOT();
            } else {
                // If order was already saved (e.g. by waiter), directly open KOT & print!
                this.prepareKotPrintData();
                this.openKotModal = true;
                this.kotPrinted = true;
                this.$nextTick(() => {
                    this.printKotReceipt();
                });
            }
        },
        addToCart(item,variant,modifiers=[],notes='') {
            window.playBeep(920,90);
            const varId=variant?variant.id:'null';
            const modIds=modifiers.map(m=>m.id).sort().join('-');
            const cartKey=`${item.id}_${varId}_${modIds}_${notes}`;
            const existing=this.cart.find(c=>c.cart_key===cartKey);
            if(existing){ existing.quantity++; existing.line_total=existing.unit_price*existing.quantity; }
            else {
                let unitPrice=variant?variant.price:item.selling_price;
                modifiers.forEach(m=>unitPrice+=m.price);
                this.cart.push({cart_key:cartKey,item_id:item.id,variant_id:variant?variant.id:null,name:item.name,variant_name:variant?variant.name:null,unit_price:unitPrice,quantity:1,line_total:unitPrice,selected_modifiers:modifiers,notes,is_existing:false});
            }
            this.$nextTick(()=>window.initLucideIcons());
        },
        updateQty(index,delta) { const i=this.cart[index]; i.quantity+=delta; if(i.quantity<=0) this.cart.splice(index,1); else i.line_total=i.unit_price*i.quantity; this.$nextTick(()=>window.initLucideIcons()); },
        removeFromCart(index) { this.cart.splice(index,1); this.$nextTick(()=>window.initLucideIcons()); },
        get subtotal() { return this.cart.reduce((s,i)=>s+i.line_total,0); },
        get discountAmount() { return this.discountType==='percentage'?(this.subtotal*(this.discountValue||0))/100:Math.min(this.subtotal,this.discountValue||0); },
        get vatAmount() { return (Math.max(0,this.subtotal-this.discountAmount)*this.vatRate)/100; },
        get grandTotal() { return Math.round(Math.max(0,this.subtotal-this.discountAmount)+this.vatAmount); },
        get changeAmount() { return (this.paidAmount||0)-this.grandTotal; },
        getTableTotal(table) {
            if (!table) return 0;
            if (table.active_orders && table.active_orders.length > 0) {
                return table.active_orders.reduce((sum, ord) => sum + (parseFloat(ord.grand_total) || 0), 0);
            }
            return table.current_order ? (parseFloat(table.current_order.grand_total) || 0) : 0;
        },
        selectTable(table) {
            this.selectedTable = table;
            this.orderType = 'dine_in';
            this.openTableModal = false;

            // If table has active orders, load the first active order; otherwise start fresh guest
            if (table.active_orders && table.active_orders.length > 0) {
                this.loadExistingOrder(table.active_orders[0]);
            } else if (table.current_order) {
                this.loadExistingOrder(table.current_order);
            } else {
                this.startNewGuestOrderOnTable();
            }
            this.$nextTick(() => window.initLucideIcons());
        },
        loadIncomingOrder() {
            if (!this.incomingWaiterOrder) return;
            const tId = this.incomingWaiterOrder.table_id;
            const matchedTable = this.tables.find(t => t.id == tId);
            if (matchedTable) {
                this.selectTable(matchedTable);
                // Also automatically prepare & open KOT print modal for kitchen firing
                this.prepareKotPrintData();
                this.openKotModal = true;
            }
            this.incomingWaiterOrder = null;
            this.$nextTick(() => window.initLucideIcons());
        },
        loadExistingOrder(order) {
            if (!order) return;
            this.currentLoadedOrderId = order.id;
            this.cart = [];
            if (order.items && order.items.length > 0) {
                order.items.forEach(it => {
                    const mods = it.modifiers || [];
                    const key = `${it.item_id}_${it.variant_id || 'null'}_${mods.map(m=>m.modifier_id||m.id).join('-')}_${it.notes || ''}`;
                    this.cart.push({
                        cart_key: key,
                        item_id: it.item_id,
                        variant_id: it.variant_id,
                        name: it.item_name,
                        variant_name: it.variant_name,
                        unit_price: parseFloat(it.unit_price),
                        quantity: parseInt(it.quantity),
                        line_total: parseFloat(it.subtotal || (it.unit_price * it.quantity)),
                        selected_modifiers: mods,
                        notes: it.notes || '',
                        is_existing: true,
                    });
                });
            }
            if (order.customer) {
                this.customerData = order.customer;
                this.customerPhone = order.customer.phone;
            } else if (order.customer_phone) {
                this.customerPhone = order.customer_phone;
                this.searchCustomer();
            } else {
                this.customerData = null;
                this.customerPhone = '';
            }
            this.tokenNumber = order.token_number || order.order_number || Math.floor(10 + Math.random() * 90);
            window.playBeep(1100, 120);
            this.$nextTick(() => window.initLucideIcons());
        },
        startNewGuestOrderOnTable() {
            this.currentLoadedOrderId = null;
            this.cart = [];
            this.customerPhone = '';
            this.customerData = null;
            this.redeemedPoints = 0;
            this.discountValue = 0;
            this.tokenNumber = Math.floor(10 + Math.random() * 90);
            window.playBeep(990, 100);
            if (this.$refs.searchInput) {
                this.$refs.searchInput.focus();
            }
            this.$nextTick(() => window.initLucideIcons());
        },
        async searchCustomer(autoCreate = false) {
            const phone = (this.customerPhone || '').trim();
            if (!phone || phone.length < 3) {
                this.customerData = null;
                this.redeemedPoints = 0;
                this.isNewCustomerBadge = false;
                return;
            }
            try {
                const res = await fetch(`{{ route('customers.search') }}?phone=${encodeURIComponent(phone)}&auto_create=${autoCreate ? '1' : '0'}`);
                const data = await res.json();
                if (data.success && data.customer) {
                    this.customerData = data.customer;
                    this.customerPhone = data.customer.phone;
                    this.isNewCustomerBadge = !!data.is_new;
                    if (data.is_new) {
                        window.playBeep(1200, 100);
                    }
                } else if (!autoCreate) {
                    this.customerData = null;
                    this.isNewCustomerBadge = false;
                }
            } catch(e) {}
            this.$nextTick(() => window.initLucideIcons());
        },
        async searchOrRegisterCustomer(forceCreate = true) {
            const phone = (this.customerPhone || '').trim();
            if (!phone || phone.length < 5) {
                alert('অনুগ্রহ করে সঠিক মোবাইল নম্বর লিখুন (কমপক্ষে ৫-১১ ডিজিট)');
                return;
            }
            await this.searchCustomer(forceCreate);
        },
        clearCustomer() {
            this.customerPhone = '';
            this.customerData = null;
            this.redeemedPoints = 0;
            this.isNewCustomerBadge = false;
            if (this.discountType === 'fixed' && this.discountValue === this.redeemedPoints) {
                this.discountValue = 0;
            }
            this.$nextTick(() => window.initLucideIcons());
        },
        redeemCustomerPoints() {
            if (!this.customerData || this.customerData.reward_points <= 0) return;
            const pts = Math.min(this.customerData.reward_points, this.subtotal);
            this.redeemedPoints = pts;
            this.discountType = 'fixed';
            this.discountValue = pts;
            window.playBeep(1100, 100);
        },
        async sendKOT() {
            if(!this.cart.length) return; this.isProcessing=true;
            const targetTableId = this.selectedTable?.id || null;
            try {
                const res=await fetch('{{ route('pos.order.store') }}',{
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    },
                    body:JSON.stringify({
                        order_id:this.currentLoadedOrderId||null,
                        order_type:this.orderType,
                        table_id:targetTableId,
                        token_number:this.tokenNumber.toString(),
                        customer_phone:this.customerPhone||null,
                        customer_name:this.customerData?.name||null,
                        redeemed_points:this.redeemedPoints,
                        items:this.cart.map(c=>({
                            item_id:c.item_id,
                            variant_id:c.variant_id||null,
                            quantity:parseInt(c.quantity)||1,
                            unit_price:parseFloat(c.unit_price)||0,
                            notes:c.notes||'',
                            is_existing:c.is_existing||false,
                            modifiers:c.selected_modifiers?.map(m=>m.id)||[]
                        })),
                        discount_type:this.discountType,
                        discount_value:this.discountValue,
                        vat_percent:this.vatRate,
                        payment_status:'unpaid',
                        waiter_id:this.selectedWaiterId||null
                    })
                });
                const d=await res.json();
                if(res.ok && d.success){
                    window.playBeep(1200,180);
                    
                    this.currentLoadedOrderId = d.order.id;
                    this.tokenNumber = d.order.token_number || d.order.order_number;

                    // Prepare KOT Print Data for Kitchen Token Slip
                    this.kotPrintData = {
                        token_number: d.order.token_number || this.tokenNumber,
                        order_number: d.order.order_number,
                        order_type: d.order.order_type,
                        table_name: this.selectedTable?.name || d.order.table?.name || null,
                        waiter_name: d.order.waiter?.name || null,
                        customer_name: d.order.customer?.name || this.customerData?.name || null,
                        customer_phone: d.order.customer_phone || this.customerPhone || null,
                        time: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }) + ', ' + new Date().toLocaleDateString('en-GB'),
                        items: this.cart.map(c => ({
                            item_name: c.name,
                            variant_name: c.variant_name,
                            quantity: c.quantity,
                            selected_modifiers: c.selected_modifiers || [],
                            notes: c.notes || ''
                        })),
                        total_quantity: this.cart.reduce((sum, i) => sum + i.quantity, 0),
                    };

                    // Instant table status update & broadcast
                    const tableId = targetTableId || d.order?.table_id;
                    if (tableId) {
                        this.updateSingleTableLocally(tableId, 'occupied', d.order);
                        this.broadcastTableChange(d.tables, tableId, 'occupied', d.order);
                    }
                    if (d.tables) {
                        this.applyLiveTables(d.tables);
                    }

                    this.cart.forEach(c => c.is_existing = true);
                    this.mobileCartOpen = false;
                    this.openKotModal = true;

                    this.$nextTick(() => {
                        this.printKotReceipt();
                    });
                } else {
                    alert('KOT পাঠানো যায়নি: ' + (d.message || 'অজানা ত্রুটি'));
                }
            } catch(e){ alert('ত্রুটি: '+e.message); } finally { this.isProcessing=false; }
        },
        printKotReceipt() {
            const content = document.getElementById('thermalKotReceipt')?.innerHTML;
            if (!content) return;
            this.triggerDirectThermalPrint(content, `Kitchen KOT #${this.kotPrintData?.token_number || ''}`);
        },
        printReceipt() {
            const content = document.getElementById('thermalReceipt')?.innerHTML;
            if (!content) return;
            this.triggerDirectThermalPrint(content, `Mushak 6.3 - ${this.mushakData?.invoice?.order_no || ''}`);
        },
        triggerDirectThermalPrint(htmlContent, title = 'Receipt') {
            try {
                let frame = document.getElementById('receiptPrintIframe');
                if (!frame) {
                    frame = document.createElement('iframe');
                    frame.id = 'receiptPrintIframe';
                    frame.style.position = 'fixed';
                    frame.style.right = '-1000px';
                    frame.style.bottom = '-1000px';
                    frame.style.width = '100px';
                    frame.style.height = '100px';
                    frame.style.opacity = '0';
                    frame.style.pointerEvents = 'none';
                    frame.style.border = '0';
                    document.body.appendChild(frame);
                }
                const doc = frame.contentWindow.document;
                doc.open();
                doc.write(`<!DOCTYPE html><html><head><title>${title}</title><style>
                    body { font-family: monospace; font-size: 11px; margin: 0; padding: 6px; width: 58mm; color: #000; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .flex { display: flex; }
                    .justify-between { justify-content: space-between; }
                    .items-start { align-items: flex-start; }
                    .font-bold { font-weight: bold; }
                    .font-black { font-weight: 900; }
                    .text-sm { font-size: 13px; }
                    .text-xs { font-size: 11px; }
                    .text-2xl { font-size: 22px; }
                    .text-3xl { font-size: 26px; }
                    .border-b { border-bottom: 1px dashed #000; padding-bottom: 4px; margin-bottom: 4px; }
                    .border-b-2 { border-bottom: 2px dashed #000; padding-bottom: 4px; margin-bottom: 4px; }
                    .border-t { border-top: 1px dashed #000; padding-top: 4px; margin-top: 4px; }
                    .py-1 { padding-top: 2px; padding-bottom: 2px; }
                    .my-1 { margin-top: 4px; margin-bottom: 4px; }
                </style></head><body>${htmlContent}</body></html>`);
                doc.close();

                setTimeout(() => {
                    try {
                        frame.contentWindow.focus();
                        frame.contentWindow.print();
                    } catch(err) {
                        console.error('Print iframe error:', err);
                    }
                }, 150);
            } catch(e) {
                console.error('Print error:', e);
            }
        },
        async processPaymentAndPrint() {
            if (this.isProcessing) return;
            this.isProcessing=true;
            const targetTableId = this.selectedTable?.id || null;
            try {
                const res=await fetch('{{ route('pos.order.store') }}',{
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN':'{{ csrf_token() }}'
                    },
                    body:JSON.stringify({
                        order_id:this.currentLoadedOrderId||null,
                        order_type:this.orderType,
                        table_id:targetTableId,
                        token_number:this.tokenNumber.toString(),
                        customer_phone:this.customerPhone||null,
                        customer_name:this.customerData?.name||null,
                        redeemed_points:this.redeemedPoints,
                        items:this.cart.map(c=>({
                            item_id:c.item_id,
                            variant_id:c.variant_id||null,
                            quantity:parseInt(c.quantity)||1,
                            unit_price:parseFloat(c.unit_price)||0,
                            notes:c.notes||'',
                            is_existing:c.is_existing||false,
                            modifiers:c.selected_modifiers?.map(m=>m.id)||[]
                        })),
                        discount_type:this.discountType,
                        discount_value:this.discountValue,
                        vat_percent:this.vatRate,
                        payment_status:'paid',
                        payment_method:this.paymentMethod,
                        paid_amount:this.paidAmount||this.grandTotal,
                        waiter_id:this.selectedWaiterId||null
                    })
                });
                const d=await res.json();
                if(res.ok && d.success){
                    this.mushakData=d.mushak;
                    this.openPaymentModal=false;
                    this.mobileCartOpen=false;
                    this.openMushakModal=true;

                    // Instant table status sync & broadcast
                    const tableId = targetTableId || d.order?.table_id;
                    if (d.tables) {
                        this.applyLiveTables(d.tables);
                    }

                    this.$nextTick(()=>{ const q=document.getElementById('qrcodeCanvas'); if(q){q.innerHTML=''; new QRCode(q,{text:d.mushak.qr_string,width:85,height:85,correctLevel:QRCode.CorrectLevel.M});} });

                    // Check if table still has other remaining active guest orders
                    const upTable = d.tables ? d.tables.find(t => t.id == tableId) : null;
                    if (upTable && upTable.active_orders && upTable.active_orders.length > 0) {
                        this.selectedTable = upTable;
                        this.loadExistingOrder(upTable.active_orders[0]);
                        this.broadcastTableChange(d.tables, tableId, 'occupied', upTable.active_orders[0]);
                    } else {
                        if (tableId) {
                            this.updateSingleTableLocally(tableId, 'available', null);
                            this.broadcastTableChange(d.tables, tableId, 'available', null);
                        }
                        this.resetOrder(true);
                    }
                } else {
                    alert('পেমেন্ট ফেইল: ' + (d.message || 'অজানা ত্রুটি'));
                }
            } catch(e){ alert('পেমেন্ট ফেইল: '+e.message); } finally { this.isProcessing=false; }
        },
        resetOrder(clearTable=true) {
            this.currentLoadedOrderId = null;
            this.kotPrinted = false;
            this.cart = [];
            this.discountValue = 0;
            this.paidAmount = 0;
            this.trxId = '';
            this.tokenNumber = Math.floor(10 + Math.random() * 90);
            this.customerPhone = '';
            this.customerData = null;
            this.isNewCustomerBadge = false;
            if (clearTable) this.selectedTable = null;
            this.$nextTick(() => window.initLucideIcons());
        },
        toggleVoiceRecord() {
            if(!this.recognition){ alert('স্পিচ সাপোর্ট নেই। টাইপ করুন।'); return; }
            if(this.isRecording){ this.recognition.stop(); this.isRecording=false; } else { this.aiPromptText=''; this.recognition.start(); this.isRecording=true; }
        },
        async processAiOrder() {
            if(!this.aiPromptText) return; this.isAiLoading=true;
            try {
                const res=await fetch('{{ route('ai.parseVoice') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({prompt:this.aiPromptText})});
                const d=await res.json();
                if(d.success&&d.parsed){
                    const p=d.parsed;
                    if(p.table_number){const t=this.tables.find(x=>x.name.includes(p.table_number)); if(t) this.selectedTable=t;}
                    if(p.order_type) this.orderType=p.order_type;
                    if(p.items&&p.items.length>0){p.items.forEach(pi=>{const it=this.allItems.find(x=>x.id===pi.item_id||x.name.toLowerCase().includes(pi.name.toLowerCase())); if(it){let v=null; if(pi.variant_name&&it.variants) v=it.variants.find(x=>x.name.toLowerCase().includes(pi.variant_name.toLowerCase()))||null; for(let q=0;q<(pi.quantity||1);q++) this.addToCart(it,v,[],pi.notes||'');}});window.playBeep(1100,150); this.openAiModal=false; this.aiPromptText=''; } else alert('AI কোনো আইটেম শনাক্ত করতে পারেনি।');
                }
            } catch(e){ alert('AI এরর: '+e.message); } finally { this.isAiLoading=false; }
        },
        openSplitBillModal() {
            if (this.cart.length === 0) return;
            this.splitCount = 2;
            this.calculateSplits();
            this.openSplitModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },
        calculateSplits() {
            const count = Math.max(1, parseInt(this.splitCount) || 1);
            const perPerson = Math.floor(this.grandTotal / count);
            const remainder = this.grandTotal - (perPerson * count);
            this.splitRows = [];
            for (let i = 0; i < count; i++) {
                const amt = (i === count - 1) ? (perPerson + remainder) : perPerson;
                this.splitRows.push({
                    person_label: `ব্যক্তি #${i + 1}`,
                    amount: amt,
                    payment_method: 'cash',
                    ref: '',
                });
            }
            this.$nextTick(() => window.initLucideIcons());
        },
        get splitTotalPaid() {
            return this.splitRows.reduce((sum, r) => sum + (parseFloat(r.amount) || 0), 0);
        },
        async processSplitPaymentAndPrint() {
            if (this.splitTotalPaid < this.grandTotal) {
                alert(`স্প্লিট বিলের মোট যোগফল (৳${this.splitTotalPaid}) মূল বিল (৳${this.grandTotal}) এর চেয়ে কম! সম্পূর্ণ টাকা বিভাজন করুন।`);
                return;
            }
            this.isProcessing = true;
            const targetTableId = this.selectedTable?.id || null;
            try {
                const res = await fetch('{{ route('pos.order.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_id: this.currentLoadedOrderId || null,
                        order_type: this.orderType,
                        table_id: targetTableId,
                        token_number: this.tokenNumber.toString(),
                        customer_phone: this.customerPhone || null,
                        customer_name: this.customerData?.name || null,
                        redeemed_points: this.redeemedPoints,
                        items: this.cart.map(c => ({
                            item_id: c.item_id,
                            variant_id: c.variant_id || null,
                            quantity: parseInt(c.quantity) || 1,
                            unit_price: parseFloat(c.unit_price) || 0,
                            notes: c.notes || '',
                            is_existing: c.is_existing || false,
                            modifiers: c.selected_modifiers?.map(m => m.id) || []
                        })),
                        discount_type: this.discountType,
                        discount_value: this.discountValue,
                        vat_percent: this.vatRate,
                        payment_status: 'paid',
                        payment_method: this.splitRows.map(r => r.payment_method).join('+'),
                        paid_amount: this.splitTotalPaid,
                        payments: this.splitRows.map(r => ({
                            method: r.payment_method,
                            amount: parseFloat(r.amount) || 0,
                            ref: r.ref || null
                        })),
                        waiter_id: this.selectedWaiterId || null
                    })
                });
                const d = await res.json();
                if (res.ok && d.success) {
                    this.mushakData = d.mushak;
                    this.openSplitModal = false;
                    this.mobileCartOpen = false;
                    this.openMushakModal = true;

                    // Instant table status sync & broadcast
                    const tableId = targetTableId || d.order?.table_id;
                    if (d.tables) {
                        this.applyLiveTables(d.tables);
                    }

                    this.$nextTick(() => {
                        const q = document.getElementById('qrcodeCanvas');
                        if (q) {
                            q.innerHTML = '';
                            new QRCode(q, { text: d.mushak.qr_string, width: 85, height: 85, correctLevel: QRCode.CorrectLevel.M });
                        }
                    });

                    // Check if table still has other remaining active guest orders
                    const upTable = d.tables ? d.tables.find(t => t.id == tableId) : null;
                    if (upTable && upTable.active_orders && upTable.active_orders.length > 0) {
                        this.selectedTable = upTable;
                        this.loadExistingOrder(upTable.active_orders[0]);
                        this.broadcastTableChange(d.tables, tableId, 'occupied', upTable.active_orders[0]);
                    } else {
                        if (tableId) {
                            this.updateSingleTableLocally(tableId, 'available', null);
                            this.broadcastTableChange(d.tables, tableId, 'available', null);
                        }
                        this.resetOrder(true);
                    }
                } else {
                    alert('স্প্লিট পেমেন্ট ফেইল: ' + (d.message || 'অজানা ত্রুটি'));
                }
            } catch (e) {
                alert('স্প্লিট পেমেন্ট ফেইল: ' + e.message);
            } finally {
                this.isProcessing = false;
            }
        },
        formatNumber(num) { return (parseFloat(num)||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }
    };
}
</script>
@endpush
@endsection
