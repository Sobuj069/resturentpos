<!DOCTYPE html>
<html lang="bn" class="h-full antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $table->name }} — ডিজিটাল মেনু ও অর্ডার · {{ $branch->restaurant_name ?? "Sultan's Dine" }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@600;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="guestOrderApp()" x-init="init()" class="h-full min-h-screen flex flex-col antialiased" style="background:#F5F0EC;">

    <!-- Guest Top Brand Bar -->
    <header class="sticky top-0 z-30 shadow-md px-4 py-3 text-white flex items-center justify-between"
            style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                 style="background: rgba(212,172,80,0.25); border: 1.5px solid rgba(212,172,80,0.5);">
                <i data-lucide="utensils-crossed" class="w-5 h-5 stroke-[2.5]" style="color:#D4AC50;"></i>
            </div>
            <div>
                <h1 class="text-sm font-extrabold tracking-tight leading-tight">{{ $branch->restaurant_name ?? "Sultan's Dine" }}</h1>
                <p class="text-[10px] font-bold tracking-wider uppercase" style="color:#D4AC50;">ডিজিটাল সেলফ-অর্ডার</p>
            </div>
        </div>

        <div class="px-2.5 py-1 rounded-xl text-xs font-black flex items-center gap-1.5"
             style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);">
            <i data-lucide="map-pin" class="w-3.5 h-3.5" style="color:#D4AC50;"></i>
            <span>{{ $table->name }}</span>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto p-4 space-y-4 pb-28">

        <!-- Welcome Banner -->
        <div class="bg-white rounded-2xl p-4 border shadow-xs flex items-center justify-between" style="border-color:#E8DDD9;">
            <div>
                <h2 class="text-xs font-bold" style="color:#1A0A0C;">স্বাগতম! আপনার পছন্দের খাবার অর্ডার করুন</h2>
                <p class="text-[11px] mt-0.5" style="color:#9B7A7E;">অর্ডার করার সাথে সাথে খাবার সরাসরি কিচেনে তৈরি শুরু হবে</p>
            </div>
            <span class="text-xl">🍲</span>
        </div>

        <!-- Search Bar -->
        <div class="relative">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2" style="color:#9B7A7E;"></i>
            <input type="text" x-model="searchQuery" placeholder="খাবারের নাম দিয়ে খুঁজুন..."
                   class="pos-input w-full pl-9 pr-4 py-2.5 text-xs font-medium rounded-xl shadow-xs bg-white">
        </div>

        <!-- Categories Pill Bar -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
            <button @click="selectedCategory = null"
                    class="cat-chip px-3.5 py-2 text-xs font-bold whitespace-nowrap shrink-0"
                    :class="selectedCategory === null ? 'active' : ''">
                সকল মেনু (<span x-text="allItems.length"></span>)
            </button>
            <template x-for="cat in categories" :key="cat.id">
                <button @click="selectedCategory = cat.id"
                        class="cat-chip px-3.5 py-2 text-xs font-bold whitespace-nowrap shrink-0"
                        :class="selectedCategory === cat.id ? 'active' : ''">
                    <span x-text="cat.bangla_name || cat.name"></span>
                </button>
            </template>
        </div>

        <!-- Items Grid -->
        <div class="grid grid-cols-2 gap-3">
            <template x-for="item in filteredItems" :key="item.id">
                <div class="pos-card p-2.5 flex flex-col justify-between rounded-2xl bg-white border shadow-xs">
                    <div>
                        <!-- Food Image (Square 1:1) -->
                        <div class="relative w-full aspect-square rounded-xl overflow-hidden mb-1.5 bg-[#F8F5F2] flex items-center justify-center border border-black/5 shrink-0">
                            <template x-if="item.image">
                                <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!item.image">
                                <i data-lucide="utensils" class="w-6 h-6 opacity-35" style="color:#8B1A2C;"></i>
                            </template>
                            <div class="absolute top-1.5 right-1.5 z-10" x-show="item.has_variants">
                                <span class="text-[8px] font-bold px-1.5 py-0.5 rounded-md shadow-xs bg-[#8B1A2C] text-white">ভ্যারিয়েন্ট</span>
                            </div>
                        </div>

                        <h3 class="text-xs font-bold leading-tight line-clamp-1" style="color:#1A0A0C;" x-text="item.name"></h3>
                        <p class="text-[10px] line-clamp-1 mt-0.5" style="color:#9B7A7E;" x-text="item.bangla_name || ''"></p>
                    </div>

                    <div class="mt-2 pt-2 border-t flex items-center justify-between" style="border-color:#F0E8E5;">
                        <span class="text-xs font-black pos-nums price-maroon">৳<span x-text="formatNumber(item.selling_price)"></span></span>
                        <button @click="handleItemClick(item)"
                                class="w-7 h-7 rounded-xl flex items-center justify-center transition-all shadow-xs"
                                style="background:#8B1A2C; color:#fff;">
                            <i data-lucide="plus" class="w-4 h-4 stroke-[2.5]"></i>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </main>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- STICKY FLOATING GUEST CART BAR                      -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="cart.length > 0" x-cloak class="fixed bottom-3 left-3 right-3 z-40">
        <button @click="openCheckoutDrawer = true"
                class="btn-maroon w-full p-3 rounded-2xl flex items-center justify-between shadow-2xl animate-bounce-subtle">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-xs" style="background:#D4AC50; color:#3D0A12;">
                    <span x-text="cart.reduce((s,i)=>s+i.quantity,0)"></span>
                </div>
                <div class="text-left leading-tight">
                    <p class="text-xs font-black text-white">অর্ডার কনফার্ম করুন</p>
                    <p class="text-[10px] text-white/80">{{ $table->name }} · মোট ৳<span x-text="formatNumber(grandTotal)"></span></p>
                </div>
            </div>
            <div class="flex items-center gap-1 text-white text-xs font-black">
                <span>কার্ট দেখুন</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </div>
        </button>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: GUEST CHECKOUT DRAWER                        -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openCheckoutDrawer" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center modal-backdrop">
        <div @click.outside="openCheckoutDrawer = false"
             class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-3xl overflow-hidden shadow-2xl border flex flex-col max-h-[85vh]"
             style="border-color:#E0D4CF;">
            <!-- Header -->
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <div class="flex items-center gap-2 text-white">
                    <i data-lucide="shopping-cart" class="w-5 h-5" style="color:#D4AC50;"></i>
                    <h3 class="text-sm font-bold">আপনার অর্ডার তালিকা ({{ $table->name }})</h3>
                </div>
                <button @click="openCheckoutDrawer = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <!-- Items -->
            <div class="p-4 overflow-y-auto space-y-2 flex-1" style="background:#F8F5F2;">
                <template x-for="(ci, idx) in cart" :key="ci.cart_key">
                    <div class="p-2.5 rounded-xl border bg-white flex items-center justify-between gap-2" style="border-color:#E8DDD9;">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold" style="color:#1A0A0C;" x-text="ci.name"></p>
                            <p x-show="ci.variant_name" class="text-[10px] font-bold" style="color:#B8922A;" x-text="ci.variant_name"></p>
                            <p class="text-[10px] pos-nums" style="color:#9B7A7E;">৳<span x-text="formatNumber(ci.unit_price)"></span> x <span x-text="ci.quantity"></span></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="updateQty(idx, -1)" class="w-6 h-6 rounded bg-gray-100 flex items-center justify-center text-xs font-bold">-</button>
                            <span class="text-xs pos-nums font-black w-4 text-center" x-text="ci.quantity"></span>
                            <button @click="updateQty(idx, 1)" class="w-6 h-6 rounded bg-gray-100 flex items-center justify-center text-xs font-bold">+</button>
                        </div>
                        <span class="text-xs pos-nums font-black price-maroon">৳<span x-text="formatNumber(ci.line_total)"></span></span>
                    </div>
                </template>

                <!-- Customer Details -->
                <div class="p-3 bg-white rounded-2xl border space-y-2.5 mt-2" style="border-color:#E8DDD9;">
                    <div>
                        <label class="section-heading">আপনার নাম (ঐচ্ছিক)</label>
                        <input type="text" x-model="guestName" placeholder="উদাঃ আশফাকুল ইসলাম" class="pos-input w-full px-3 py-1.5 text-xs rounded-xl">
                    </div>
                    <div>
                        <label class="section-heading">মোবাইল নম্বর (পয়েন্টস ও SMS চালানের জন্য)</label>
                        <input type="text" x-model="guestPhone" placeholder="01711000000" class="pos-input w-full px-3 py-1.5 text-xs pos-nums rounded-xl">
                    </div>
                </div>

                <!-- Totals -->
                <div class="p-3 bg-white rounded-2xl border space-y-1 text-xs" style="border-color:#E8DDD9;">
                    <div class="flex justify-between" style="color:#9B7A7E;">
                        <span>সাবটোটাল:</span>
                        <span class="pos-nums font-bold" style="color:#1A0A0C;">৳<span x-text="formatNumber(subtotal)"></span></span>
                    </div>
                    <div class="flex justify-between" style="color:#9B7A7E;">
                        <span>মূসক ভ্যাট ({{ $branch->default_vat_rate ?? 5 }}%):</span>
                        <span class="pos-nums font-bold" style="color:#1A0A0C;">৳<span x-text="formatNumber(vatAmount)"></span></span>
                    </div>
                    <div class="flex justify-between pt-1 border-t text-sm font-black" style="border-color:#E0D4CF;">
                        <span>সর্বমোট প্রদেয়:</span>
                        <span class="pos-nums price-maroon">৳<span x-text="formatNumber(grandTotal)"></span></span>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="p-4 border-t bg-white" style="border-color:#E0D4CF;">
                <button @click="submitOrder()" :disabled="isSubmitting"
                        class="btn-maroon w-full py-3 rounded-2xl text-xs font-black flex items-center justify-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4 stroke-[3]"></i>
                    <span x-text="isSubmitting ? 'অর্ডার কিচেনে পাঠানো হচ্ছে...' : 'কিচেনে অর্ডার প্লেস করুন'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: ORDER SUCCESS POPUP                          -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="orderPlacedSuccess" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div class="w-full max-w-sm bg-white rounded-3xl p-6 text-center shadow-2xl border space-y-4" style="border-color:#E0D4CF;">
            <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center" style="background:#D1FAE5; color:#065F46;">
                <i data-lucide="check" class="w-8 h-8 stroke-[3]"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold" style="color:#1A0A0C;">অর্ডার কিচেনে পাঠানো হয়েছে!</h3>
                <p class="text-xs mt-1" style="color:#9B7A7E;">অর্ডার নম্বর: <strong class="pos-nums price-maroon" x-text="confirmedOrderNo"></strong></p>
                <p class="text-xs mt-0.5" style="color:#5C3840;">শেফ আপনার খাবার তৈরি শুরু করেছেন। অনুগ্রহ করে কিছুক্ষণ অপেক্ষা করুন।</p>
            </div>
            <button @click="orderPlacedSuccess = false; cart = [];"
                    class="btn-maroon w-full py-2.5 rounded-xl text-xs font-bold">
                মেনুতে ফিরে যান
            </button>
        </div>
    </div>

    <!-- ════ MODAL: VARIANT / MODIFIERS SELECTION ════ -->
    <div x-show="openItemModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openItemModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background:#FBF8F5; border-color:#E0D4CF;">
                <div>
                    <h3 class="text-sm font-bold" style="color:#1A0A0C;" x-text="activeItem?.name"></h3>
                    <p class="text-xs font-medium" style="color:#B8922A;" x-text="activeItem?.bangla_name"></p>
                </div>
                <button @click="openItemModal = false" style="color:#9B7A7E;"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-4 space-y-3">
                <div x-show="activeItem?.variants?.length > 0">
                    <label class="section-heading">সাইজ নির্বাচন করুন</label>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="v in activeItem?.variants" :key="v.id">
                            <button @click="selectedVariant = v"
                                    class="p-2.5 rounded-xl border text-left flex items-center justify-between transition-all"
                                    :style="selectedVariant?.id === v.id ? 'background:rgba(139,26,44,0.08); border-color:#8B1A2C;' : 'background:#F8F5F2; border-color:#E8DDD9;'">
                                <span class="text-xs font-bold" x-text="v.name"></span>
                                <span class="text-xs pos-nums font-black price-maroon">৳<span x-text="formatNumber(v.price)"></span></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div>
                    <label class="section-heading">স্পেশাল কিচেন নোট</label>
                    <input type="text" x-model="itemNote" placeholder="উদাঃ কম ঝাল, আলাদা সালাদ দিন" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
            </div>
            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openItemModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="confirmAddToCart()" class="btn-maroon px-6 py-2 text-xs font-bold">কার্টে যোগ করুন</button>
            </div>
        </div>
    </div>

    <script>
    function guestOrderApp() {
        return {
            allItems: @json($categories->flatMap->items),
            categories: @json($categories),
            tableToken: '{{ $table->qr_code_token ?: $table->id }}',
            vatRate: {{ $branch->default_vat_rate ?? 5.0 }},
            searchQuery: '',
            selectedCategory: null,
            cart: [],
            guestName: '',
            guestPhone: '',
            openCheckoutDrawer: false,
            openItemModal: false,
            activeItem: null,
            selectedVariant: null,
            itemNote: '',
            isSubmitting: false,
            orderPlacedSuccess: false,
            confirmedOrderNo: '',

            init() { this.$nextTick(() => window.initLucideIcons()); },

            get filteredItems() {
                return this.allItems.filter(item => {
                    const mc = this.selectedCategory === null || item.category_id === this.selectedCategory;
                    const q = this.searchQuery.toLowerCase();
                    const ms = !q || item.name.toLowerCase().includes(q) || (item.bangla_name && item.bangla_name.toLowerCase().includes(q));
                    return mc && ms;
                });
            },

            handleItemClick(item) {
                if (item.has_variants && item.variants && item.variants.length > 0) {
                    this.activeItem = item;
                    this.selectedVariant = item.variants[0];
                    this.itemNote = '';
                    this.openItemModal = true;
                    this.$nextTick(() => window.initLucideIcons());
                } else {
                    this.addToCart(item, null, '');
                }
            },

            confirmAddToCart() {
                this.addToCart(this.activeItem, this.selectedVariant, this.itemNote);
                this.openItemModal = false;
            },

            addToCart(item, variant, notes) {
                const varId = variant ? variant.id : 'null';
                const key = `${item.id}_${varId}_${notes}`;
                const existing = this.cart.find(c => c.cart_key === key);
                const price = variant ? parseFloat(variant.price) : parseFloat(item.selling_price);

                if (existing) {
                    existing.quantity++;
                    existing.line_total = existing.unit_price * existing.quantity;
                } else {
                    this.cart.push({
                        cart_key: key,
                        item_id: item.id,
                        variant_id: variant ? variant.id : null,
                        name: item.name,
                        variant_name: variant ? variant.name : null,
                        unit_price: price,
                        quantity: 1,
                        line_total: price,
                        notes: notes,
                    });
                }
                this.$nextTick(() => window.initLucideIcons());
            },

            updateQty(idx, delta) {
                const item = this.cart[idx];
                item.quantity += delta;
                if (item.quantity <= 0) this.cart.splice(idx, 1);
                else item.line_total = item.unit_price * item.quantity;
            },

            get subtotal() { return this.cart.reduce((s, i) => s + i.line_total, 0); },
            get vatAmount() { return (this.subtotal * this.vatRate) / 100; },
            get grandTotal() { return Math.round(this.subtotal + this.vatAmount); },

            async submitOrder() {
                if (this.cart.length === 0) return;
                this.isSubmitting = true;
                try {
                    const payload = {
                        customer_name: this.guestName,
                        customer_phone: this.guestPhone,
                        items: this.cart.map(c => ({
                            item_id: c.item_id,
                            variant_id: c.variant_id,
                            quantity: c.quantity,
                            unit_price: c.unit_price,
                            notes: c.notes,
                        }))
                    };

                    const res = await fetch(`/order/table/${this.tableToken}/place`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.confirmedOrderNo = data.order_number;
                        this.openCheckoutDrawer = false;
                        this.orderPlacedSuccess = true;
                        this.cart = [];
                    } else {
                        alert(data.message || 'অর্ডার করতে সমস্যা হয়েছে!');
                    }
                } catch (e) {
                    alert('ত্রুটি: ' + e.message);
                } finally {
                    this.isSubmitting = false;
                }
            },

            formatNumber(n) { return (parseFloat(n) || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
        };
    }
    </script>
</body>
</html>
