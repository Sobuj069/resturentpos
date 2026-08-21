@extends('layouts.app')
@section('title', 'POS Billing Terminal')
@section('content')
<div x-data="posTerminal()" x-init="init()" class="h-full flex flex-col md:flex-row overflow-hidden relative">

    <!-- ════════════════════════════════════════════════════════ -->
    <!-- LEFT: Catalog & Item Grid                                -->
    <!-- ════════════════════════════════════════════════════════ -->
    <div class="flex-1 flex flex-col min-w-0 h-full border-r" style="background:#F8F5F2; border-color:#E0D4CF;">

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

        <!-- Items Grid -->
        <div class="flex-1 p-3 sm:p-4 overflow-y-auto min-h-0 pb-24 md:pb-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3">
                <template x-for="item in filteredItems" :key="item.id">
                    <button @click="handleItemClick(item)"
                            class="pos-card group relative flex flex-col justify-between p-2.5 text-left active:scale-[0.97] transition-all duration-200 rounded-2xl bg-white border border-[#E0D4CF] hover:border-[#8B1A2C] shadow-xs hover:shadow-md h-full select-none"
                            style="min-height: 220px;">
                        
                        <!-- Fixed Height Square Food Image Container -->
                        <div class="relative w-full rounded-xl overflow-hidden mb-2 bg-[#F3ECE8] flex items-center justify-center shrink-0 border border-black/5"
                             style="height: 120px;">
                            <!-- Image if exists -->
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.name"
                                     style="width: 100%; height: 120px; object-fit: cover; object-position: center;"
                                     class="block group-hover:scale-105 transition-transform duration-300">
                            </template>
                            <!-- Fallback Icon if no image -->
                            <template x-if="!item.image">
                                <div class="w-full h-full flex flex-col items-center justify-center text-center p-2 bg-gradient-to-br from-[#8B1A2C]/10 to-[#B8922A]/10">
                                    <div class="w-9 h-9 rounded-full bg-white/90 shadow-xs flex items-center justify-center mb-1">
                                        <i data-lucide="utensils" class="w-4 h-4 text-[#8B1A2C]"></i>
                                    </div>
                                    <span class="text-[9px] font-bold text-[#8B1A2C] line-clamp-1" x-text="item.name"></span>
                                </div>
                            </template>

                            <!-- Badges Floating on Image -->
                            <div class="absolute top-1.5 left-1.5 z-10 pointer-events-none">
                                <span class="text-[9px] font-black px-1.5 py-0.5 rounded-md pos-nums shadow-xs bg-black/80 text-white tracking-wider" x-text="item.sku || 'ITEM'"></span>
                            </div>
                            <div class="absolute top-1.5 right-1.5 z-10 pointer-events-none" x-show="item.has_variants">
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md shadow-xs bg-[#8B1A2C] text-white">
                                    ভ্যারিয়েন্ট
                                </span>
                            </div>
                        </div>

                        <!-- Card Content (Uniform layout) -->
                        <div class="flex-1 flex flex-col justify-between w-full">
                            <div class="mb-2">
                                <h3 class="text-xs sm:text-[13px] font-extrabold line-clamp-1 leading-tight text-[#1A0A0C]" x-text="item.name"></h3>
                                <p class="text-[10px] sm:text-[11px] font-medium line-clamp-1 mt-0.5 text-[#825E64]" x-text="item.bangla_name || ''"></p>
                            </div>

                            <!-- Price & Plus Button -->
                            <div class="flex items-center justify-between pt-2 border-t border-[#F0E8E5] mt-auto">
                                <div>
                                    <span class="text-[9px] font-medium block text-[#9B7A7E] leading-none mb-0.5">মূল্য:</span>
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
        <div class="hidden md:flex h-9 px-4 items-center justify-between text-[11px] shrink-0 border-t bg-white"
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
    <!-- MOBILE STICKY FLOATING CART BAR (Mobile Only: < md)     -->
    <!-- ════════════════════════════════════════════════════════ -->
    <div x-show="cart.length > 0" x-cloak class="md:hidden fixed bottom-[60px] left-3 right-3 z-30">
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
    <!-- RIGHT: Cart Panel (Desktop + Mobile Slide-up Drawer)     -->
    <!-- ════════════════════════════════════════════════════════ -->
    <div class="w-full md:w-96 lg:w-[390px] h-full flex flex-col shrink-0 bg-white border-l z-40 transition-transform duration-300"
         :class="mobileCartOpen ? 'fixed inset-0 md:relative' : 'hidden md:flex'"
         style="border-color:#E0D4CF;">

        <!-- Mobile Drawer Close Header -->
        <div class="md:hidden p-3 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
            <div class="flex items-center gap-2 text-white">
                <i data-lucide="shopping-cart" class="w-4 h-4" style="color:#D4AC50;"></i>
                <span class="text-xs font-bold">কার্ট ও চেকআউট (<span x-text="cart.length"></span>)</span>
            </div>
            <button @click="mobileCartOpen = false" class="text-white/80 hover:text-white p-1">
                <i data-lucide="chevron-down" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Cart Header: Order Types -->
        <div class="p-3 border-b shrink-0 space-y-2" style="background:#FBF8F5; border-color:#E0D4CF;">
            <!-- Order Type Switcher -->
            <div class="grid grid-cols-3 gap-1 p-1 rounded-xl border" style="background:#F0EBE8; border-color:#D0BDB8;">
                @foreach([['dine_in','utensils','ডাইন-ইন'],['takeaway','shopping-bag','পার্সেল'],['delivery','bike','ডেলিভারি']] as $ot)
                <button @click="orderType = '{{ $ot[0] }}'"
                        class="py-1.5 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5"
                        :style="orderType === '{{ $ot[0] }}' ? 'background:#8B1A2C; color:#fff; box-shadow:0 2px 8px rgba(139,26,44,0.3);' : 'color:#5C3840;'">
                    <i data-lucide="{{ $ot[1] }}" class="w-3.5 h-3.5"></i>
                    <span>{{ $ot[2] }}</span>
                </button>
                @endforeach
            </div>
            <!-- Table & Waiter -->
            <div class="flex items-center justify-between text-xs">
                <template x-if="orderType === 'dine_in'">
                    <button @click="openTableModal = true"
                            class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg font-bold"
                            style="background:rgba(184,146,42,0.1); color:#B8922A; border:1px solid rgba(184,146,42,0.3);">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        <span x-text="selectedTable ? selectedTable.name : 'টেবিল সিলেক্ট করুন'"></span>
                    </button>
                </template>
                <template x-if="orderType === 'takeaway'">
                    <div class="flex items-center gap-1.5">
                        <span style="color:#9B7A7E;">টোকেন:</span>
                        <span class="px-2 py-0.5 rounded font-bold pos-nums"
                              style="background:rgba(139,26,44,0.08); color:#8B1A2C; border:1px solid rgba(139,26,44,0.2);" x-text="'#' + tokenNumber"></span>
                    </div>
                </template>
                <template x-if="orderType === 'delivery'">
                    <span style="color:#9B7A7E;">ডেলিভারি: <strong style="color:#8B1A2C;">পাঠাও/ফুডপান্ডা</strong></span>
                </template>
                <select x-model="selectedWaiterId" class="pos-input text-xs rounded-lg px-2 py-1.5">
                    <option value="">ওয়েটার: অটো</option>
                    @foreach($waiters as $w)
                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Customer CRM & Loyalty Points Bar -->
            <div class="pt-2 border-t space-y-1.5" style="border-color:#E8DDD9;">
                <div class="flex items-center gap-1.5">
                    <div class="relative flex-1">
                        <i data-lucide="phone" class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2" style="color:#9B7A7E;"></i>
                        <input type="text" x-model="customerPhone" @input.debounce.400ms="searchCustomer()"
                               placeholder="কাস্টমার মোবাইল (017...)"
                               class="pos-input w-full pl-7 pr-2 py-1 text-xs pos-nums rounded-lg bg-white">
                    </div>
                    <span x-show="customerData" class="text-[10px] font-bold px-2 py-1 rounded-lg uppercase"
                          :style="customerData?.membership_tier === 'platinum' ? 'background:#F3E8FF; color:#9333EA;' : (customerData?.membership_tier === 'gold' ? 'background:#FEF3C7; color:#B8922A;' : 'background:#E0F2FE; color:#0284C7;')"
                          x-text="customerData?.membership_tier || 'Bronze'"></span>
                </div>

                <!-- Customer Details & Points Redeem -->
                <div x-show="customerData" x-cloak class="p-2 rounded-xl flex items-center justify-between text-xs" style="background:#FBF1F3; border:1px solid rgba(139,26,44,0.15);">
                    <div>
                        <p class="font-bold text-xs" style="color:#1A0A0C;" x-text="customerData?.name"></p>
                        <p class="text-[10px]" style="color:#8B1A2C;">
                            পয়েন্টস: <strong class="pos-nums" x-text="customerData?.reward_points"></strong> (৳<span x-text="customerData?.reward_points"></span>)
                        </p>
                    </div>
                    <button x-show="customerData?.reward_points > 0 && redeemedPoints === 0"
                            @click="redeemCustomerPoints()"
                            class="px-2 py-1 rounded-lg text-[10px] font-bold"
                            style="background:#8B1A2C; color:#fff;">
                        পয়েন্ট রিডিম
                    </button>
                    <span x-show="redeemedPoints > 0" class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">
                        ✓ ৳<span x-text="redeemedPoints"></span> ছাড়
                    </span>
                </div>
            </div>
        </div>

        <!-- Cart Items List -->
        <div class="flex-1 overflow-y-auto p-3 space-y-2 min-h-0" style="background:#F8F5F2;">
            <!-- Active Table Running Bill Alert Banner -->
            <template x-if="selectedTable && selectedTable.status === 'occupied' && selectedTable.current_order">
                <div class="p-2.5 rounded-xl border flex items-center justify-between text-xs mb-2 shadow-xs"
                     style="background:#FFF5F5; border-color:#FCA5A5;">
                    <div class="min-w-0">
                        <p class="font-black flex items-center gap-1.5" style="color:#991B1B;">
                            <span class="w-2 h-2 rounded-full animate-pulse" style="background:#DC2626;"></span>
                            <span>চলমান ডাইন-ইন বিল (<span x-text="selectedTable.name"></span>)</span>
                        </p>
                        <p class="text-[10px] pos-nums" style="color:#C02020;" x-text="'অর্ডার #' + (selectedTable.current_order.order_number || '') + ' (টোকেন #' + (selectedTable.current_order.token_number || '') + ')'"></p>
                    </div>
                    <span class="font-black pos-nums text-xs price-maroon">৳<span x-text="formatNumber(selectedTable.current_order.grand_total)"></span></span>
                </div>
            </template>

            <template x-for="(cartItem, index) in cart" :key="cartItem.cart_key">
                <div class="p-2.5 rounded-xl border bg-white transition-all shadow-xs"
                     :style="cartItem.is_existing ? 'border-color:#D1FAE5; background:#FAFCFA;' : 'border-color:#E8DDD9; background:#FFFFFF;'">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-bold truncate" style="color:#1A0A0C;" x-text="cartItem.name"></p>
                            <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                <span x-show="cartItem.variant_name" class="text-[10px] font-bold px-1.5 py-0.2 rounded"
                                      style="background:rgba(184,146,42,0.1); color:#B8922A;" x-text="cartItem.variant_name"></span>
                                <template x-if="cartItem.is_existing">
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1"
                                          style="background:#D1FAE5; color:#065F46; border:1px solid #A7F3D0;">
                                        <i data-lucide="utensils" class="w-2.5 h-2.5"></i>
                                        <span>টেবিলে পরিবেশিত (Served)</span>
                                    </span>
                                </template>
                                <template x-if="!cartItem.is_existing && isOccupiedTable">
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1"
                                          style="background:#FEF3C7; color:#B45309; border:1px solid #FDE68A;">
                                        <i data-lucide="sparkles" class="w-2.5 h-2.5"></i>
                                        <span>নতুন অর্ডার (New)</span>
                                    </span>
                                </template>
                                <template x-for="mod in cartItem.selected_modifiers" :key="mod.id">
                                    <span class="text-[10px] px-1.5 py-0.2 rounded"
                                          style="background:rgba(139,26,44,0.06); color:#8B1A2C;" x-text="'+' + mod.name"></span>
                                </template>
                            </div>
                        </div>
                        <!-- Delete / Lock Indicator -->
                        <div>
                            <template x-if="cartItem.is_existing">
                                <span class="p-1 flex items-center text-gray-400 cursor-not-allowed" title="খাবার ইতিমধ্যে টেবিলে পরিবেশিত হওয়ায় মোছা যাবে না">
                                    <i data-lucide="lock" class="w-3.5 h-3.5 text-gray-400"></i>
                                </span>
                            </template>
                            <template x-if="!cartItem.is_existing">
                                <button @click="removeFromCart(index)" style="color:#C0A0A4;" onmouseover="this.style.color='#C02020'" onmouseout="this.style.color='#C0A0A4'" title="মুছে ফেলুন">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Quantity & Price Row -->
                    <div class="flex items-center justify-between pt-1.5 mt-1.5 border-t" style="border-color:#F0E8E5;">
                        <!-- Locked or Editable Quantity Control -->
                        <template x-if="cartItem.is_existing">
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg border text-[11px] font-bold"
                                 style="background:#F1F5F9; color:#475569; border-color:#E2E8F0;">
                                <i data-lucide="lock" class="w-3 h-3 text-slate-400"></i>
                                <span>পরিমাণ: <span class="pos-nums font-black text-slate-800" x-text="cartItem.quantity"></span> টি</span>
                            </div>
                        </template>

                        <template x-if="!cartItem.is_existing">
                            <div class="flex items-center gap-2 p-1 rounded-lg border" style="background:#F8F5F2; border-color:#E8DDD9;">
                                <button @click="updateQty(index, -1)" class="w-6 h-6 rounded flex items-center justify-center transition-colors"
                                        style="background:#F0E8E5; color:#8B1A2C;">
                                    <i data-lucide="minus" class="w-3 h-3"></i>
                                </button>
                                <span class="text-xs pos-nums font-black w-5 text-center" style="color:#1A0A0C;" x-text="cartItem.quantity"></span>
                                <button @click="updateQty(index, 1)" class="w-6 h-6 rounded flex items-center justify-center transition-colors"
                                        style="background:#F0E8E5; color:#8B1A2C;">
                                    <i data-lucide="plus" class="w-3 h-3"></i>
                                </button>
                            </div>
                        </template>

                        <div class="text-right">
                            <p class="text-[10px] pos-nums" style="color:#9B7A7E;">@ ৳<span x-text="formatNumber(cartItem.unit_price)"></span></p>
                            <p class="text-xs font-black pos-nums price-maroon">৳<span x-text="formatNumber(cartItem.line_total)"></span></p>
                        </div>
                    </div>
                </div>
            </template>
            <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center py-12" style="color:#C0A0A4;">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3"
                     style="background:#FBF1F3; border:1px solid rgba(139,26,44,0.15);">
                    <i data-lucide="shopping-cart" class="w-6 h-6" style="color:#C0A0A4;"></i>
                </div>
                <p class="text-xs font-bold" style="color:#9B7A7E;">কার্ট সম্পূর্ণ খালি</p>
                <p class="text-[11px] mt-0.5">মেনু থেকে খাবার আইটেম যোগ করুন</p>
            </div>
        </div>

        <!-- Cart Footer -->
        <div class="p-3 border-t shrink-0 space-y-1.5 text-xs bg-white" style="border-color:#E0D4CF;">
            <div class="flex justify-between">
                <span style="color:#9B7A7E;">সাবটোটাল:</span>
                <span class="pos-nums font-bold" style="color:#1A0A0C;">৳<span x-text="formatNumber(subtotal)"></span></span>
            </div>
            <!-- Discounts -->
            <div class="flex items-center justify-between gap-2 py-1">
                <span style="color:#9B7A7E;">ডিসকাউন্ট:</span>
                <div class="flex items-center gap-1.5">
                    <input type="number" x-model.number="discountValue" min="0" placeholder="0"
                           class="pos-input w-16 px-1.5 py-0.5 text-xs pos-nums font-bold text-right rounded">
                    <select x-model="discountType" class="pos-input text-[11px] py-0.5 px-1 rounded">
                        <option value="fixed">৳ নির্দিষ্ট</option>
                        <option value="percentage">% শতকরা</option>
                    </select>
                    <span class="pos-nums font-bold price-maroon">- ৳<span x-text="formatNumber(discountAmount)"></span></span>
                </div>
            </div>
            <div class="flex justify-between">
                <span style="color:#9B7A7E;">NBR মূসক ভ্যাট ({{ $currentBranch->default_vat_rate ?? 5 }}%):</span>
                <span class="pos-nums font-bold" style="color:#1A0A0C;">৳<span x-text="formatNumber(vatAmount)"></span></span>
            </div>
            <div class="flex justify-between items-baseline pt-2 border-t" style="border-color:#E0D4CF;">
                <span class="text-sm font-black" style="color:#1A0A0C;">সর্বমোট:</span>
                <span class="text-xl font-black pos-nums price-maroon">৳<span x-text="formatNumber(grandTotal)"></span></span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="p-3 border-t shrink-0 space-y-2 bg-white" style="border-color:#E0D4CF;">
            <div class="grid grid-cols-2 gap-2">
                <button @click="handleKotButtonClick()"
                        class="btn-outline py-2.5 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all"
                        :style="(isOccupiedTable && newItemsCount === 0) ? 'background:#ECFDF5; color:#065F46; border-color:#A7F3D0; cursor:default;' : ''">
                    <i :data-lucide="(isOccupiedTable && newItemsCount === 0) ? 'check-circle' : 'printer'" class="w-4 h-4"
                       :style="(isOccupiedTable && newItemsCount === 0) ? 'color:#059669;' : 'color:#B8922A;'"></i>
                    <span x-text="(isOccupiedTable && newItemsCount === 0) ? '✓ টেবিলে সার্ভড' : (isOccupiedTable && newItemsCount > 0) ? '+ নতুন (' + newItemsCount + 'টি) KOT' : 'কিচেন KOT (F8)'"></span>
                </button>
                <button @click="openSplitBillModal()" :disabled="cart.length === 0"
                        class="py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 disabled:opacity-50 transition-all border"
                        style="background:#F8F5F2; color:#5C3840; border-color:#D0BDB8;">
                    <i data-lucide="split" class="w-4 h-4" style="color:#8B1A2C;"></i>
                    স্প্লিট বিল (F9)
                </button>
            </div>
            <button @click="openPaymentModal = true; mobileCartOpen = false;" :disabled="cart.length === 0 || isProcessing"
                    class="btn-maroon w-full py-3 rounded-2xl text-sm flex items-center justify-center gap-2 disabled:opacity-50">
                <i data-lucide="credit-card" class="w-5 h-5 stroke-[2.5]"></i>
                <span>বিল ও পেমেন্ট (F4) — ৳<span x-text="formatNumber(grandTotal)"></span></span>
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
                                class="p-3 sm:p-3.5 rounded-2xl border flex flex-col items-center justify-center gap-1 transition-all text-center relative"
                                :style="t.status === 'occupied' ? 'background:#FEE2E2; border-color:#FCA5A5; color:#991B1B;'
                                       : t.status === 'billed' ? 'background:#FEF3C7; border-color:#FCD34D; color:#92400E;'
                                       : 'background:#FFFFFF; border-color:#E8DDD9; color:#1A0A0C;'">
                            <div class="w-2.5 h-2.5 rounded-full absolute top-2 right-2"
                                 :style="t.status==='occupied' ? 'background:#EF4444;' : t.status==='billed' ? 'background:#F59E0B;' : 'background:#22C55E;'"></div>
                            <i data-lucide="utensils" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                            <span class="text-xs sm:text-sm font-black pos-nums" x-text="t.name"></span>
                            <span class="text-[9px] sm:text-[10px]" x-text="t.capacity + ' জন'"></span>
                            <span class="text-[8px] sm:text-[9px] font-bold px-1.5 py-0.2 rounded"
                                  :style="t.status==='occupied' ? 'background:#FEE2E2; color:#991B1B;' : t.status==='billed' ? 'background:#FEF3C7; color:#92400E;' : 'background:#D1FAE5; color:#065F46;'"
                                  x-text="t.status==='occupied' ? 'খাচ্ছে' : t.status==='billed' ? 'বিল সম্পন্ন' : 'খালি আছে'"></span>
                            <template x-if="t.status === 'occupied' && t.current_order">
                                <span class="text-[9px] font-black pos-nums px-1.5 py-0.5 rounded" style="background:#EF4444; color:#fff;">
                                    বিল: ৳<span x-text="formatNumber(t.current_order.grand_total)"></span>
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

    <!-- ════ MODAL 3: PAYMENT ════ -->
    <div x-show="openPaymentModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 modal-backdrop">
        <div @click.outside="openPaymentModal = false"
             class="w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl border bg-white max-h-[90vh] flex flex-col"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b shrink-0" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C); border-color: transparent;">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-white">পেমেন্ট ও চালান জেনারেশন</h3>
                        <p class="text-xs" style="color:rgba(255,255,255,0.7);">মোট প্রদেয়: <span class="pos-nums font-black text-white">৳<span x-text="formatNumber(grandTotal)"></span></span></p>
                    </div>
                    <button @click="openPaymentModal = false" style="color:rgba(255,255,255,0.7);">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
            <!-- Payment Method Tabs -->
            <div class="p-2.5 sm:p-3 border-b flex items-center gap-1 shrink-0 overflow-x-auto" style="border-color:#E0D4CF; background:#FBF8F5;">
                @foreach([['cash','banknote','ক্যাশ','#2E7D52'],['bkash','smartphone','বিকাশ','#e2136e'],['nagad','zap','নগদ','#f7931e'],['card','credit-card','কার্ড','#8B1A2C']] as $pm)
                <button @click="paymentMethod = '{{ $pm[0] }}'; paidAmount = grandTotal;"
                        class="flex-1 py-2 px-1 rounded-xl text-[11px] sm:text-xs font-bold flex items-center justify-center gap-1 transition-all border shrink-0"
                        :style="paymentMethod === '{{ $pm[0] }}' ? 'background:{{ $pm[3] }}; color:#fff; border-color:{{ $pm[3] }}; box-shadow:0 2px 8px rgba(0,0,0,0.2);' : 'background:#FFFFFF; color:#5C3840; border-color:#E8DDD9;'">
                    <i data-lucide="{{ $pm[1] }}" class="w-3.5 h-3.5"></i>
                    <span>{{ $pm[2] }}</span>
                </button>
                @endforeach
            </div>
            <div class="p-4 space-y-4 overflow-y-auto flex-1">
                <!-- Cash -->
                <div x-show="paymentMethod === 'cash'" class="space-y-3">
                    <label class="section-heading">কাস্টমার প্রদত্ত টাকা:</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 font-bold pos-nums" style="color:#9B7A7E;">৳</span>
                        <input type="number" x-model.number="paidAmount"
                               class="pos-input w-full pl-8 pr-4 py-2.5 text-lg pos-nums font-black rounded-xl" style="color:#1A0A0C;">
                    </div>
                    <div class="grid grid-cols-4 gap-1.5 sm:gap-2">
                        <button @click="paidAmount = grandTotal" class="py-2 rounded-xl text-xs font-bold border transition-all"
                                style="background:#FBF1F3; color:#8B1A2C; border-color:rgba(139,26,44,0.25);">সঠিক</button>
                        @foreach([500,1000,2000] as $amt)
                        <button @click="paidAmount = {{ $amt }}" class="py-2 rounded-xl text-xs font-bold border transition-all"
                                style="background:#F8F5F2; color:#5C3840; border-color:#E8DDD9;">৳{{ number_format($amt) }}</button>
                        @endforeach
                    </div>
                    <div class="p-3 rounded-2xl flex items-center justify-between border" style="background:#F8F5F2; border-color:#E8DDD9;">
                        <span class="text-xs font-bold" style="color:#9B7A7E;">ভাঙতি ফেরত (Change):</span>
                        <span class="text-lg pos-nums font-black"
                              :style="changeAmount >= 0 ? 'color:#2E7D52;' : 'color:#C02020;'">
                            ৳<span x-text="formatNumber(Math.max(0, changeAmount))"></span>
                        </span>
                    </div>
                </div>
                <!-- bKash -->
                <div x-show="paymentMethod === 'bkash'" class="space-y-3">
                    <div class="p-3 rounded-2xl text-center" style="background:#FCE7F3; border:1px solid #FBCFE8;">
                        <p class="text-xs font-bold" style="color:#be185d;">মার্চেন্ট বিকাশ নাম্বার: {{ $currentBranch->bkash_number ?? '01711-223344' }}</p>
                    </div>
                    <div>
                        <label class="section-heading">bKash TrxID:</label>
                        <input type="text" x-model="trxId" placeholder="উদাঃ BKH8927492" class="pos-input w-full rounded-xl px-3 py-2 text-xs pos-nums uppercase">
                    </div>
                </div>
                <!-- Nagad -->
                <div x-show="paymentMethod === 'nagad'" class="space-y-3">
                    <div class="p-3 rounded-2xl text-center" style="background:#FEF3C7; border:1px solid #FDE68A;">
                        <p class="text-xs font-bold" style="color:#b45309;">নগদ মার্চেন্ট নাম্বার: {{ $currentBranch->nagad_number ?? '01711-223344' }}</p>
                    </div>
                    <div>
                        <label class="section-heading">Nagad TrxID:</label>
                        <input type="text" x-model="trxId" placeholder="উদাঃ NGD8927492" class="pos-input w-full rounded-xl px-3 py-2 text-xs pos-nums uppercase">
                    </div>
                </div>
                <!-- Card -->
                <div x-show="paymentMethod === 'card'" class="space-y-3">
                    <div>
                        <label class="section-heading">POS মেশিন কার্ড রেফারেন্স:</label>
                        <input type="text" x-model="trxId" placeholder="উদাঃ Ref: 9942, Card 4281" class="pos-input w-full rounded-xl px-3 py-2 text-xs pos-nums">
                    </div>
                </div>
            </div>
            <div class="p-4 border-t flex items-center justify-between shrink-0" style="border-color:#E0D4CF; background:#FBF8F5;">
                <button @click="openPaymentModal = false" class="px-4 py-2 rounded-xl text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="processPaymentAndPrint()" :disabled="isProcessing"
                        class="btn-maroon px-5 sm:px-6 py-2.5 sm:py-3 rounded-2xl text-xs font-bold flex items-center gap-2 disabled:opacity-50">
                    <i data-lucide="check-circle" class="w-4 h-4 stroke-[3]"></i>
                    <span x-text="isProcessing ? 'প্রসেসিং...' : 'পেমেন্ট ও মূসক ৬.৩ চালান'"></span>
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
        redeemedPoints: 0,
        splitCount: 2,
        splitRows: [],
        openModifierModal: false, openTableModal: false, openPaymentModal: false,
        openMushakModal: false, openAiModal: false, openSplitModal: false,
        activeItem: null, selectedVariant: null, selectedModifiers: [], itemCustomNote: '',
        paymentMethod: 'cash', paidAmount: 0, trxId: '', mushakData: null,
        aiPromptText: '', isRecording: false, isAiLoading: false, recognition: null,

        init() {
            window.addEventListener('keydown', (e) => {
                if (e.key==='F2'){ e.preventDefault(); this.resetOrder(); }
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
        closeAllModals() { this.openModifierModal=this.openTableModal=this.openPaymentModal=this.openMushakModal=this.openAiModal=this.openSplitModal=false; },
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
        get isOccupiedTable() { return this.selectedTable && this.selectedTable.status === 'occupied' && this.selectedTable.current_order; },
        get newItemsCount() { return this.cart.filter(i => !i.is_existing).length; },
        handleKotButtonClick() {
            if (this.isOccupiedTable && this.newItemsCount === 0) {
                alert('এই টেবিলের বর্তমান সকল আইটেম ইতিমধ্যে কিচেনে পাঠানো আছে। নতুন কোনো খাবার মেনু থেকে কার্টে যোগ করলে শুধু সেটি কিচেনে পাঠানো যাবে।');
                return;
            }
            this.sendKOT();
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
        selectTable(table) {
            this.selectedTable = table;
            this.orderType = 'dine_in';
            this.openTableModal = false;

            // Auto-load running order items into cart if table is occupied
            if (table.current_order && table.current_order.items && table.current_order.items.length > 0) {
                this.cart = [];
                table.current_order.items.forEach(it => {
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
                if (table.current_order.customer) {
                    this.customerData = table.current_order.customer;
                    this.customerPhone = table.current_order.customer.phone;
                } else if (table.current_order.customer_phone) {
                    this.customerPhone = table.current_order.customer_phone;
                    this.searchCustomer();
                }
                if (table.current_order.token_number) {
                    this.tokenNumber = table.current_order.token_number;
                }
                window.playBeep(1100, 120);
            }
            this.$nextTick(() => window.initLucideIcons());
        },
        async searchCustomer() {
            if (!this.customerPhone || this.customerPhone.length < 4) { this.customerData = null; this.redeemedPoints = 0; return; }
            try {
                const res = await fetch(`{{ route('customers.search') }}?phone=${this.customerPhone}`);
                const data = await res.json();
                if (data.success && data.customer) {
                    this.customerData = data.customer;
                } else {
                    this.customerData = null;
                }
            } catch(e) {}
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
            try {
                const res=await fetch('{{ route('pos.order.store') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({order_id:this.selectedTable?.current_order?.id||null,order_type:this.orderType,table_id:this.selectedTable?.id||null,token_number:this.tokenNumber.toString(),customer_phone:this.customerPhone||null,customer_name:this.customerData?.name||null,redeemed_points:this.redeemedPoints,items:this.cart.map(c=>({item_id:c.item_id,variant_id:c.variant_id,quantity:c.quantity,unit_price:c.unit_price,notes:c.notes,is_existing:c.is_existing||false,modifiers:c.selected_modifiers?.map(m=>m.id)||[]})),discount_type:this.discountType,discount_value:this.discountValue,vat_percent:this.vatRate,payment_status:'unpaid',waiter_id:this.selectedWaiterId||null})});
                const d=await res.json();
                if(d.success){
                    window.playBeep(1200,180);
                    alert(this.isOccupiedTable ? 'নতুন আইটেম কিচেনে KOT পাঠানো হয়েছে!' : 'KOT কিচেনে পাঠানো হয়েছে! অর্ডার নং: '+d.order.order_number);
                    this.cart.forEach(c => c.is_existing = true);
                    if (!this.isOccupiedTable) { this.resetOrder(); }
                    this.mobileCartOpen=false;
                }
            } catch(e){ alert('ত্রুটি: '+e.message); } finally { this.isProcessing=false; }
        },
        async processPaymentAndPrint() {
            this.isProcessing=true;
            try {
                const res=await fetch('{{ route('pos.order.store') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify({order_id:this.selectedTable?.current_order?.id||null,order_type:this.orderType,table_id:this.selectedTable?.id||null,token_number:this.tokenNumber.toString(),customer_phone:this.customerPhone||null,customer_name:this.customerData?.name||null,redeemed_points:this.redeemedPoints,items:this.cart.map(c=>({item_id:c.item_id,variant_id:c.variant_id,quantity:c.quantity,unit_price:c.unit_price,notes:c.notes,is_existing:c.is_existing||false,modifiers:c.selected_modifiers?.map(m=>m.id)||[]})),discount_type:this.discountType,discount_value:this.discountValue,vat_percent:this.vatRate,payment_status:'paid',payment_method:this.paymentMethod,paid_amount:this.paidAmount||this.grandTotal,waiter_id:this.selectedWaiterId||null})});
                const d=await res.json();
                if(d.success){ this.mushakData=d.mushak; this.openPaymentModal=false; this.mobileCartOpen=false; this.openMushakModal=true; this.$nextTick(()=>{ const q=document.getElementById('qrcodeCanvas'); if(q){q.innerHTML=''; new QRCode(q,{text:d.mushak.qr_string,width:85,height:85,correctLevel:QRCode.CorrectLevel.M});} }); this.resetOrder(false); }
            } catch(e){ alert('পেমেন্ট ফেইল: '+e.message); } finally { this.isProcessing=false; }
        },
        printReceipt() {
            const c=document.getElementById('thermalReceipt').innerHTML;
            const w=window.open('','_blank','width=350,height=600');
            w.document.write(`<html><head><title>Mushak 6.3</title><style>body{font-family:monospace;font-size:11px;margin:0;padding:10px;width:58mm;}.text-center{text-align:center;}.text-right{text-align:right;}.flex{display:flex;}.justify-between{justify-content:space-between;}.font-bold{font-weight:bold;}.border-b{border-bottom:1px dashed #000;padding-bottom:5px;margin-bottom:5px;}</style></head><body>${c}</body></html>`);
            w.document.close(); w.focus(); w.print(); w.close();
        },
        resetOrder(clearTable=true) { this.cart=[]; this.discountValue=0; this.paidAmount=0; this.trxId=''; this.tokenNumber=Math.floor(10+Math.random()*90); if(clearTable) this.selectedTable=null; this.$nextTick(()=>window.initLucideIcons()); },
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
            try {
                const res = await fetch('{{ route('pos.order.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        order_id: this.selectedTable?.current_order?.id || null,
                        order_type: this.orderType,
                        table_id: this.selectedTable?.id || null,
                        token_number: this.tokenNumber.toString(),
                        customer_phone: this.customerPhone || null,
                        customer_name: this.customerData?.name || null,
                        redeemed_points: this.redeemedPoints,
                        items: this.cart.map(c => ({
                            item_id: c.item_id,
                            variant_id: c.variant_id,
                            quantity: c.quantity,
                            unit_price: c.unit_price,
                            notes: c.notes,
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
                if (d.success) {
                    this.mushakData = d.mushak;
                    this.openSplitModal = false;
                    this.mobileCartOpen = false;
                    this.openMushakModal = true;
                    this.$nextTick(() => {
                        const q = document.getElementById('qrcodeCanvas');
                        if (q) {
                            q.innerHTML = '';
                            new QRCode(q, { text: d.mushak.qr_string, width: 85, height: 85, correctLevel: QRCode.CorrectLevel.M });
                        }
                    });
                    this.resetOrder(false);
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
