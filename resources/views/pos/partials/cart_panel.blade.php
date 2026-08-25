<!-- Mobile Drawer Close Header -->
<div class="sm:hidden p-3 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
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

    <!-- Multi-Guest / Consecutive Sub-Orders on Same Table -->
    <template x-if="orderType === 'dine_in' && selectedTable">
        <div class="p-2 rounded-xl border space-y-1.5" style="background:#FFF9F8; border-color:#EBDCD8;">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-extrabold flex items-center gap-1" style="color:#5C0F1B;">
                    <i data-lucide="users" class="w-3.5 h-3.5" style="color:#8B1A2C;"></i>
                    <span>কাস্টমার / সাব-অর্ডার:</span>
                </span>
                <button @click="startNewGuestOrderOnTable()"
                        class="px-2.5 py-1 rounded-lg text-[10px] font-black transition-all border flex items-center gap-1 shadow-xs hover:opacity-90 cursor-pointer"
                        style="background:#2E7D52; color:#fff; border-color:#2E7D52;">
                    <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                    <span>+ নতুন কাস্টমার বিল</span>
                </button>
            </div>

            <!-- List of Active Guest Orders on This Table -->
            <div class="flex flex-wrap items-center gap-1.5 pt-0.5">
                <template x-for="(ord, idx) in (selectedTable.active_orders || (selectedTable.current_order ? [selectedTable.current_order] : []))" :key="ord.id">
                    <button @click="loadExistingOrder(ord)"
                            class="px-2 py-1 rounded-lg text-[10px] font-bold transition-all border flex items-center gap-1 shadow-xs"
                            :style="currentLoadedOrderId === ord.id ? 'background:#8B1A2C; color:#fff; border-color:#8B1A2C; box-shadow:0 2px 6px rgba(139,26,44,0.25);' : 'background:#FFFFFF; color:#5C3840; border-color:#D8C4BF;'">
                        <span x-text="'কাস্টমার #' + (idx + 1) + ' (টোকেন #' + (ord.token_number || ord.order_number) + ')'"></span>
                        <span class="font-black pos-nums text-[9px] px-1 py-0.2 rounded"
                              :style="currentLoadedOrderId === ord.id ? 'background:rgba(255,255,255,0.25); color:#fff;' : 'background:#FBF1F3; color:#8B1A2C;'"
                              x-text="'৳' + formatNumber(ord.grand_total)"></span>
                    </button>
                </template>
            </div>
        </div>
    </template>

    <!-- Customer CRM Search & Reward Points Bar -->
    <div class="p-2 rounded-xl border space-y-1.5" style="background:#FFFFFF; border-color:#E8DDD9;">
        <div class="flex items-center gap-1.5">
            <div class="relative flex-1">
                <i data-lucide="phone" class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2" style="color:#9B7A7E;"></i>
                <input type="text" x-model="customerPhone" @keyup.enter="searchCustomer(true)"
                       placeholder="মোবাইল নম্বর লিখে Enter চাপুন..."
                       class="pos-input w-full pl-8 pr-2 py-1 text-xs pos-nums font-semibold rounded-lg">
            </div>
            <button @click="searchCustomer(true)"
                    class="px-2.5 py-1 rounded-lg text-xs font-bold shrink-0 transition-all border shadow-xs"
                    style="background:#8B1A2C; color:#fff; border-color:#8B1A2C;">
                <i data-lucide="user-plus" class="w-3.5 h-3.5 inline mr-1"></i>খুঁজুন/সেভ
            </button>
        </div>

        <!-- Customer Data Badge & Points -->
        <div x-show="customerData" class="flex items-center justify-between pt-1 border-t text-xs" style="border-color:#F0E8E5;">
            <div class="flex items-center gap-1.5 min-w-0">
                <i data-lucide="user-check" class="w-3.5 h-3.5 text-emerald-600 shrink-0"></i>
                <span class="font-bold truncate" style="color:#1A0A0C;" x-text="customerData?.name || 'কাস্টমার'"></span>
                <span x-show="isNewCustomerBadge" class="text-[9px] font-black bg-emerald-100 text-emerald-800 px-1.5 py-0.2 rounded-full">নতুন</span>
            </div>
            <div class="flex items-center gap-1 shrink-0">
                <span class="text-[10px] font-bold" style="color:#9B7A7E;">পয়েন্ট: <strong class="pos-nums" style="color:#B8922A;" x-text="customerData?.reward_points || 0"></strong></span>
                <button x-show="customerData?.reward_points > 0 && redeemedPoints === 0"
                        @click="redeemCustomerPoints()"
                        class="px-2 py-1 rounded-lg text-[10px] font-bold shadow-xs hover:opacity-90 transition-all"
                        style="background:#8B1A2C; color:#fff;">
                    পয়েন্ট রিডিম
                </button>
                <span x-show="redeemedPoints > 0" class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">
                    ✓ ৳<span x-text="redeemedPoints"></span> ছাড়
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Cart Items List (Expanded Taller Container & Larger Items) -->
<div class="flex-1 overflow-y-auto p-3 space-y-2.5 min-h-[240px]" style="background:#F8F5F2;">
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
        <div class="p-3 rounded-2xl border bg-white transition-all shadow-2xs hover:border-gray-300"
             :style="cartItem.is_existing ? 'border-color:#A7F3D0; background:#F0FDF4;' : 'border-color:#E5E7EB; background:#FFFFFF;'">
            <!-- Header: Name, Badges & Delete -->
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-extrabold text-gray-900 leading-tight" x-text="cartItem.name"></p>
                    <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                        <span x-show="cartItem.variant_name" class="text-[11px] font-extrabold px-2 py-0.5 rounded-lg bg-amber-50 text-amber-800 border border-amber-200/60" x-text="cartItem.variant_name"></span>
                        <template x-if="cartItem.is_existing">
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1">
                                <i data-lucide="utensils" class="w-3 h-3"></i>
                                <span>পরিবেশিত (Served)</span>
                            </span>
                        </template>
                        <template x-if="!cartItem.is_existing && isOccupiedTable">
                            <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-300 flex items-center gap-1">
                                <i data-lucide="sparkles" class="w-3 h-3"></i>
                                <span>নতুন (New)</span>
                            </span>
                        </template>
                        <template x-for="mod in cartItem.selected_modifiers" :key="mod.id">
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-rose-50 text-rose-800" x-text="'+' + mod.name"></span>
                        </template>
                    </div>
                </div>
                <!-- Delete / Lock Indicator -->
                <div class="shrink-0 pl-1">
                    <template x-if="cartItem.is_existing">
                        <span class="p-1 flex items-center text-gray-400 cursor-not-allowed" title="পরিবেশিত খাবার মোছা যাবে না">
                            <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                        </span>
                    </template>
                    <template x-if="!cartItem.is_existing">
                        <button @click="removeFromCart(index)" class="p-1 text-gray-400 hover:text-rose-600 transition-colors" title="মুছে ফেলুন">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Quantity & Price Row -->
            <div class="flex items-center justify-between pt-2 mt-2 border-t border-gray-100">
                <!-- Quantity Control -->
                <template x-if="cartItem.is_existing">
                    <div class="flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-100 border border-slate-200 text-xs font-extrabold text-slate-700">
                        <i data-lucide="lock" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>Qty: <span class="pos-nums font-black text-slate-900" x-text="cartItem.quantity"></span> টি</span>
                    </div>
                </template>
                <template x-if="!cartItem.is_existing">
                    <div class="flex items-center gap-1.5 p-1 rounded-xl bg-gray-50 border border-gray-200">
                        <button @click="updateQty(index, -1)" class="w-7 h-7 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-rose-900 hover:bg-gray-100 font-bold active:scale-95 transition-all">
                            <i data-lucide="minus" class="w-3.5 h-3.5"></i>
                        </button>
                        <span class="text-sm pos-nums font-black w-6 text-center text-gray-900" x-text="cartItem.quantity"></span>
                        <button @click="updateQty(index, 1)" class="w-7 h-7 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-rose-900 hover:bg-gray-100 font-bold active:scale-95 transition-all">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </template>

                <!-- Line Price -->
                <div class="text-right">
                    <p class="text-[11px] pos-nums font-semibold text-gray-500">@ ৳<span x-text="formatNumber(cartItem.unit_price)"></span></p>
                    <p class="text-sm font-black pos-nums price-maroon">৳<span x-text="formatNumber(cartItem.line_total)"></span></p>
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

<!-- Cart Footer (Financial Summary) -->
<div class="p-3 sm:p-3.5 border-t shrink-0 space-y-1.5 text-xs bg-white" style="border-color:#E0D4CF;">
    <div class="flex justify-between items-center">
        <span class="text-xs font-bold text-gray-600">Subtotal / সাবটোটাল:</span>
        <span class="text-sm pos-nums font-extrabold text-gray-900">৳ <span x-text="formatNumber(subtotal)"></span></span>
    </div>

    <!-- Discounts -->
    <div class="flex items-center justify-between gap-2 py-0.5">
        <span class="text-xs font-bold text-gray-600">Discount / ডিসকাউন্ট:</span>
        <div class="flex items-center gap-1.5">
            <input type="number" x-model.number="discountValue" min="0" placeholder="0"
                   class="pos-input w-20 px-2 py-0.5 text-xs pos-nums font-bold text-right rounded-xl border-gray-300">
            <select x-model="discountType" class="pos-input text-[11px] font-bold py-0.5 px-1.5 rounded-xl border-gray-300">
                <option value="fixed">৳ Fixed</option>
                <option value="percentage">% Percent</option>
            </select>
            <span class="text-xs pos-nums font-black price-maroon shrink-0">- ৳ <span x-text="formatNumber(discountAmount)"></span></span>
        </div>
    </div>

    <div class="flex justify-between items-center">
        <span class="text-xs font-bold text-gray-600">NBR VAT ({{ $currentBranch->default_vat_rate ?? 5 }}%):</span>
        <span class="text-sm pos-nums font-extrabold text-gray-900">৳ <span x-text="formatNumber(vatAmount)"></span></span>
    </div>

    <!-- Total Amount Highlight Box -->
    <div class="p-2.5 sm:p-3 rounded-2xl border flex items-center justify-between mt-1 shadow-2xs"
         style="background: #FDFBF7; border-color: #E8DDD9;">
        <span class="text-base font-black tracking-tight text-gray-900">Total / সর্বমোট:</span>
        <span class="text-2xl font-black pos-nums price-maroon tracking-tight">৳ <span x-text="formatNumber(grandTotal)"></span></span>
    </div>
</div>

<!-- Action Buttons -->
<div class="p-3 sm:p-3.5 border-t shrink-0 space-y-2 bg-white" style="border-color:#E0D4CF;">
    <div class="grid grid-cols-2 gap-2">
        <button @click="handleKotButtonClick()" :disabled="cart.length === 0"
                class="btn-outline py-2.5 rounded-2xl text-xs font-extrabold flex items-center justify-center gap-1.5 transition-all shadow-xs"
                :style="cart.length === 0 ? 'opacity:0.5;' : (isAllKotPrinted ? 'background:#ECFDF5; color:#065F46; border-color:#A7F3D0;' : 'background:#FFFBEB; color:#92400E; border-color:#FDE68A;')">
            <i :data-lucide="isAllKotPrinted ? 'check-circle' : 'printer'" class="w-4 h-4 stroke-[2]"
               :style="isAllKotPrinted ? 'color:#059669;' : 'color:#B8922A;'"></i>
            <span x-text="isAllKotPrinted ? '✓ KOT প্রিন্ট সম্পন্ন (F8)' : ((isOccupiedTable && newItemsCount > 0) ? '+ নতুন (' + newItemsCount + 'টি) KOT (F8)' : '🖨️ কিচেন KOT (F8)')"></span>
        </button>

        <button @click="openSplitBillModal()" :disabled="cart.length === 0"
                class="py-2.5 rounded-2xl text-xs font-extrabold flex items-center justify-center gap-1.5 disabled:opacity-50 transition-all border shadow-2xs"
                style="background:#F8F5F2; color:#5C3840; border-color:#D0BDB8;">
            <i data-lucide="split" class="w-4 h-4 stroke-[2]" style="color:#8B1A2C;"></i>
            <span>স্প্লিট বিল (F9)</span>
        </button>
    </div>

    <!-- Complete Payment Prominent Main Button -->
    <button @click="openPaymentModal = true; mobileCartOpen = false;" :disabled="cart.length === 0 || isProcessing"
            class="btn-maroon w-full py-3 sm:py-3.5 rounded-2xl text-base font-black flex items-center justify-center gap-2.5 shadow-lg active:scale-[0.98] transition-all disabled:opacity-50 cursor-pointer">
        <i data-lucide="credit-card" class="w-5 h-5 stroke-[2.5]"></i>
        <span>Bills & Payments (F4) — ৳ <span x-text="formatNumber(grandTotal)"></span></span>
    </button>
</div>
