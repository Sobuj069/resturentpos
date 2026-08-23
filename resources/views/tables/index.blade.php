@extends('layouts.app')
@section('title', 'টেবিল ও ফ্লোরপ্ল্যান ম্যানেজমেন্ট')
@section('content')
<div x-data="tableFloorManager()" x-init="init()" class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(184,146,42,0.12); border:1.5px solid rgba(184,146,42,0.3);">
                    <i data-lucide="layout-grid" class="w-5 h-5" style="color:#B8922A;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">রেস্টুরেন্ট ফ্লোর ও টেবিল ম্যানেজমেন্ট</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">ডাইন-ইন টেবিল লেআউট, সিটিং ক্যাপাসিটি এবং লাইভ অকুপেন্সি নিয়ন্ত্রণ</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('tables.qrCards') }}" class="btn-outline px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="qr-code" class="w-4 h-4"></i>
                <span>টেবিল QR কার্ডস</span>
            </a>
            <button @click="openTableModal = true; resetTableForm();"
                    class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>+ নতুন টেবিল যোগ করুন</span>
            </button>
        </div>
    </div>

    <!-- Live Floor Stats (100% Real-Time Reactive) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#8B1A2C;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">মোট টেবিল</p>
            <p class="text-2xl font-black pos-nums price-maroon"><span x-text="totalTables"></span> <span class="text-xs font-normal" style="color:#9B7A7E;">টি</span></p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#2E7D52;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">খালি আছে (Available)</p>
            <p class="text-2xl font-black pos-nums" style="color:#2E7D52;"><span x-text="availableTables"></span> <span class="text-xs font-normal" style="color:#9B7A7E;">টি</span></p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#C02020;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">কাস্টমার খাচ্ছে (Occupied)</p>
            <p class="text-2xl font-black pos-nums" style="color:#C02020;"><span x-text="occupiedTables"></span> <span class="text-xs font-normal" style="color:#9B7A7E;">টি</span></p>
        </div>
        <div class="stat-card rounded-2xl p-4">
            <div class="card-accent" style="background:#B8922A;"></div>
            <p class="text-[11px] mb-1" style="color:#9B7A7E;">মোট সিটিং ক্যাপাসিটি</p>
            <p class="text-2xl font-black pos-nums" style="color:#B8922A;"><span x-text="totalCapacity"></span> <span class="text-xs font-normal" style="color:#9B7A7E;">জন</span></p>
        </div>
    </div>

    <!-- Floor Tabs -->
    <div class="flex items-center gap-1.5 p-1 rounded-2xl bg-white border overflow-x-auto w-fit" style="border-color:#E8DDD9;">
        <button @click="selectedFloor = 'all'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap"
                :style="selectedFloor === 'all' ? 'background:#8B1A2C; color:#fff;' : 'color:#5C3840; background:transparent;'">
            সকল ফ্লোর ({{ $tables->count() }})
        </button>
        <template x-for="floor in floors" :key="floor">
            <button @click="selectedFloor = floor"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap"
                    :style="selectedFloor === floor ? 'background:#8B1A2C; color:#fff;' : 'color:#5C3840; background:transparent;'"
                    x-text="floor">
            </button>
        </template>
    </div>

    <!-- Tables Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5">
        <template x-for="t in filteredTables" :key="t.id">
            <div class="pos-card p-4 flex flex-col justify-between relative group transition-all"
                 :style="t.status === 'occupied' ? 'border-color:#FCA5A5; background:#FFF5F5; box-shadow: 0 4px 12px rgba(192,32,32,0.08);'
                        : t.status === 'billed' ? 'border-color:#FCD34D; background:#FFFDF0;'
                        : 'border-color:#E8DDD9; background:#FFFFFF;'">

                <!-- Status Indicator Dot -->
                <div class="flex items-start justify-between mb-2">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full cursor-pointer"
                          @click="t.status === 'occupied' ? openOrderDetails(t) : null"
                          :style="t.status === 'occupied' ? 'background:#FEE2E2; color:#991B1B;'
                                 : t.status === 'billed' ? 'background:#FEF3C7; color:#92400E;'
                                 : 'background:#D1FAE5; color:#065F46;'"
                          x-text="t.status === 'occupied' ? 'খাচ্ছে (অর্ডার দেখুন)' : t.status === 'billed' ? 'বিল সম্পন্ন' : 'খালি'">
                    </span>

                    <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                        <button @click="editTable(t)" class="p-1 text-gray-400 hover:text-gray-700" title="এডিট">
                            <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                        </button>
                        <button @click="deleteTable(t.id, t.name)" class="p-1 text-gray-400 hover:text-rose-600" title="মুছুন">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>

                <!-- Table Center Info (Click to view active order if occupied) -->
                <div @click="t.status === 'occupied' ? openOrderDetails(t) : null"
                     class="text-center py-2 cursor-pointer">
                    <div class="w-12 h-12 rounded-2xl mx-auto flex items-center justify-center mb-1.5 transition-transform group-hover:scale-105"
                         :style="t.status === 'occupied' ? 'background:rgba(192,32,32,0.12); color:#C02020;' : 'background:rgba(139,26,44,0.08); color:#8B1A2C;'">
                        <i data-lucide="utensils" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-base font-black pos-nums" style="color:#1A0A0C;" x-text="t.name"></h3>
                    <p class="text-[11px] font-semibold" style="color:#9B7A7E;" x-text="t.floor_name"></p>
                    <p class="text-[11px] font-bold mt-0.5" style="color:#5C3840;" x-text="t.capacity + ' জন বসার আসন'"></p>

                    <!-- Running Bill Preview on Tile if Occupied -->
                    <template x-if="t.status === 'occupied' && t.current_order">
                        <div class="mt-2 px-2 py-1 rounded-xl text-[10px] font-black" style="background:#FEE2E2; color:#991B1B; border:1px solid #FCA5A5;">
                            চলমান বিল: ৳<span class="pos-nums" x-text="formatNumber(t.current_order.grand_total)"></span>
                        </div>
                    </template>
                </div>

                <!-- Quick Action Buttons -->
                <div class="pt-2 mt-2 border-t space-y-1.5" style="border-color:#F0E8E5;">
                    <template x-if="t.status === 'occupied'">
                        <div class="flex items-center gap-1">
                            <button @click="openOrderDetails(t)"
                                    class="flex-1 py-1 px-1 rounded-lg text-[10px] font-black text-center"
                                    style="background:#8B1A2C; color:#fff;">
                                অর্ডার দেখুন ও বিল নিন
                            </button>
                            <button @click="setTableStatus(t, 'available')"
                                    class="p-1 rounded-lg text-[10px] text-gray-500 bg-gray-100 hover:bg-gray-200"
                                    title="টেবিল খালি করুন">
                                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </template>

                    <template x-if="t.status !== 'occupied'">
                        <div class="grid grid-cols-2 gap-1.5">
                            <button @click="setTableStatus(t, 'available')"
                                    class="py-1 rounded-lg text-[10px] font-bold transition-all"
                                    :style="t.status === 'available' ? 'background:#2E7D52; color:#fff;' : 'background:#F0E8E5; color:#5C3840;'">
                                খালি
                            </button>
                            <a :href="'/pos?table_id=' + t.id"
                               class="py-1 rounded-lg text-[10px] font-bold transition-all text-center"
                               style="background:#8B1A2C; color:#fff;">
                                + POS অর্ডার
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: RUNNING ORDER DETAILS OF OCCUPIED TABLE      -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openRunningOrderModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openRunningOrderModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border flex flex-col max-h-[85vh]"
             style="border-color:#E0D4CF;">
            <!-- Header -->
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <div class="flex items-center gap-2.5 text-white">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background:rgba(212,172,80,0.25);">
                        <i data-lucide="receipt" class="w-4 h-4" style="color:#D4AC50;"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black" x-text="activeTable?.name + ' — চলমান অর্ডার ও বিল'"></h3>
                        <p class="text-[10px] text-white/80" x-text="activeTable?.floor_name"></p>
                    </div>
                </div>
                <button @click="openRunningOrderModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <!-- Order Content -->
            <div class="p-4 overflow-y-auto flex-1 space-y-3" style="background:#F8F5F2;">
                <!-- Order Info Pill -->
                <div class="p-3 rounded-2xl bg-white border flex items-center justify-between" style="border-color:#E8DDD9;">
                    <div>
                        <p class="text-[10px] uppercase font-bold" style="color:#9B7A7E;">অর্ডার নম্বর</p>
                        <p class="text-xs font-black pos-nums price-maroon" x-text="activeOrder?.order_number || 'ORD-NEW'"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] uppercase font-bold" style="color:#9B7A7E;">টোকেন নম্বর</p>
                        <p class="text-xs font-black pos-nums" style="color:#B8922A;" x-text="'#' + (activeOrder?.token_number || 'N/A')"></p>
                    </div>
                </div>

                <!-- Customer Details if any -->
                <template x-if="activeOrder?.customer_name || activeOrder?.customer_phone">
                    <div class="p-2.5 rounded-xl bg-white border flex items-center gap-2 text-xs" style="border-color:#E8DDD9;">
                        <i data-lucide="user" class="w-4 h-4" style="color:#8B1A2C;"></i>
                        <div>
                            <span class="font-bold" style="color:#1A0A0C;" x-text="activeOrder?.customer_name || 'Guest'"></span>
                            <span class="text-[11px] pos-nums" style="color:#9B7A7E;" x-text="activeOrder?.customer_phone ? ' (' + activeOrder.customer_phone + ')' : ''"></span>
                        </div>
                    </div>
                </template>

                <!-- Items List -->
                <div>
                    <label class="section-heading">খাবার ও আইটেম তালিকা (যা অর্ডার করেছে)</label>
                    <div class="space-y-1.5 mt-1">
                        <template x-for="item in activeOrder?.items" :key="item.id">
                            <div class="p-2.5 rounded-xl bg-white border flex items-center justify-between gap-2" style="border-color:#E8DDD9;">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black pos-nums text-white shrink-0"
                                              style="background:#8B1A2C;" x-text="item.quantity + 'x'"></span>
                                        <p class="text-xs font-bold truncate" style="color:#1A0A0C;" x-text="item.item_name"></p>
                                    </div>
                                    <p x-show="item.variant_name" class="text-[10px] pl-6 font-bold" style="color:#B8922A;" x-text="item.variant_name"></p>
                                    <p x-show="item.notes" class="text-[10px] pl-6 italic" style="color:#C02020;" x-text="'📝 ' + item.notes"></p>
                                </div>
                                <span class="text-xs pos-nums font-black price-maroon">৳<span x-text="formatNumber(item.subtotal || (item.unit_price * item.quantity))"></span></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Bill Totals -->
                <div class="p-3 bg-white rounded-2xl border space-y-1.5 text-xs" style="border-color:#E8DDD9;">
                    <div class="flex justify-between" style="color:#9B7A7E;">
                        <span>সাবটোটাল:</span>
                        <span class="pos-nums font-bold" style="color:#1A0A0C;">৳<span x-text="formatNumber(activeOrder?.subtotal)"></span></span>
                    </div>
                    <div class="flex justify-between" style="color:#9B7A7E;">
                        <span>ভ্যাট ({{ $branch->default_vat_rate ?? 5 }}%):</span>
                        <span class="pos-nums font-bold" style="color:#1A0A0C;">৳<span x-text="formatNumber(activeOrder?.vat_amount)"></span></span>
                    </div>
                    <div class="flex justify-between pt-1.5 border-t text-sm font-black" style="border-color:#E0D4CF;">
                        <span>সর্বমোট প্রদেয় বিল:</span>
                        <span class="pos-nums price-maroon">৳<span x-text="formatNumber(activeOrder?.grand_total)"></span></span>
                    </div>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="p-4 border-t bg-white flex items-center justify-between gap-2" style="border-color:#E0D4CF;">
                <button @click="setTableStatus(activeTable, 'available'); openRunningOrderModal = false;"
                        class="px-3 py-2.5 rounded-xl text-xs font-bold border text-gray-600 hover:bg-gray-50">
                    টেবিল খালি করুন
                </button>
                <a :href="'/pos?table_id=' + activeTable?.id"
                   class="btn-maroon flex-1 py-2.5 rounded-xl text-xs font-black flex items-center justify-center gap-1.5 shadow-md">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    <span>POS এ বিল নিন / প্রিন্ট চালান</span>
                </a>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: ADD / EDIT TABLE                             -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openTableModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openTableModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white" x-text="tableForm.id ? 'টেবিল এডিট করুন' : 'নতুন টেবিল যুক্ত করুন'"></h3>
                <button @click="openTableModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 space-y-3">
                <div>
                    <label class="section-heading">টেবিল নাম / নম্বর *</label>
                    <input type="text" x-model="tableForm.name" placeholder="e.g. Table 01, VIP-A" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>

                <div>
                    <label class="section-heading">ফ্লোর / জোন নির্বাচন *</label>
                    <input type="text" list="floorList" x-model="tableForm.floor_name" placeholder="Ground Floor, 1st Floor, Rooftop..." class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                    <datalist id="floorList">
                        <template x-for="f in floors" :key="f">
                            <option :value="f"></option>
                        </template>
                    </datalist>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">সিটিং ক্যাপাসিটি (জন) *</label>
                        <input type="number" min="1" max="50" x-model.number="tableForm.capacity" placeholder="4" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold price-maroon rounded-xl">
                    </div>
                    <div>
                        <label class="section-heading">বর্তমান স্ট্যাটাস</label>
                        <select x-model="tableForm.status" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                            <option value="available">খালি (Available)</option>
                            <option value="occupied">খাচ্ছে (Occupied)</option>
                            <option value="billed">বিল সম্পন্ন (Billed)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openTableModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveTable()" class="btn-maroon px-6 py-2.5 text-xs font-bold">সংরক্ষণ করুন</button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function tableFloorManager() {
    return {
        tables: @json($tables),
        floors: @json($floors),
        selectedFloor: 'all',
        openTableModal: false,
        openRunningOrderModal: false,
        activeTable: null,
        activeOrder: null,
        tableSyncChannel: null,

        tableForm: { id: null, name: '', floor_name: 'Ground Floor', capacity: 4, shape: 'square', status: 'available', sort_order: 0 },

        init() {
            // Real-Time Cross-Tab / Cross-Window Sync
            if ('BroadcastChannel' in window) {
                this.tableSyncChannel = new BroadcastChannel('pos_table_sync_channel');
                this.tableSyncChannel.onmessage = (ev) => {
                    if (ev.data && ev.data.tables) {
                        this.applyLiveTables(ev.data.tables);
                    } else if (ev.data && ev.data.type === 'TABLE_UPDATED') {
                        this.updateSingleTableLocally(ev.data.table_id, ev.data.status, ev.data.order);
                    }
                };
            }

            // Cross-window storage sync fallback
            window.addEventListener('storage', (ev) => {
                if (ev.key === 'pos_table_sync_event' && ev.newValue) {
                    try {
                        const payload = JSON.parse(ev.newValue);
                        if (payload.tables) {
                            this.applyLiveTables(payload.tables);
                        } else if (payload.table_id) {
                            this.updateSingleTableLocally(payload.table_id, payload.status, payload.order);
                        }
                    } catch(e) {}
                }
            });

            // Live Background Polling Heartbeat (every 3 seconds)
            setInterval(() => {
                if (!document.hidden) {
                    this.pollLiveTableStatuses();
                }
            }, 3000);

            this.$nextTick(() => window.initLucideIcons());
        },

        get totalTables() { return this.tables.length; },
        get availableTables() { return this.tables.filter(t => t.status === 'available').length; },
        get occupiedTables() { return this.tables.filter(t => t.status === 'occupied').length; },
        get totalCapacity() { return this.tables.reduce((sum, t) => sum + (parseInt(t.capacity) || 0), 0); },

        get filteredTables() {
            if (this.selectedFloor === 'all') return this.tables;
            return this.tables.filter(t => t.floor_name === this.selectedFloor);
        },

        applyLiveTables(newTables) {
            if (!Array.isArray(newTables)) return;
            this.tables = newTables;
            if (this.activeTable) {
                const updated = this.tables.find(t => t.id === this.activeTable.id);
                if (updated) {
                    this.activeTable = updated;
                    this.activeOrder = updated.current_order;
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
            if (this.activeTable && this.activeTable.id == tableId) {
                this.activeTable.status = status;
                this.activeTable.current_order = currentOrder;
                this.activeTable.current_order_id = currentOrder ? currentOrder.id : null;
            }
            this.$nextTick(() => window.initLucideIcons());
        },

        async pollLiveTableStatuses() {
            try {
                const res = await fetch('{{ route('tables.liveStatus') }}');
                const data = await res.json();
                if (data.success && Array.isArray(data.tables)) {
                    this.applyLiveTables(data.tables);
                }
            } catch(e) {}
        },

        resetTableForm() {
            this.tableForm = { id: null, name: '', floor_name: this.floors[0] || 'Ground Floor', capacity: 4, shape: 'square', status: 'available', sort_order: 0 };
        },

        editTable(t) {
            this.tableForm = { id: t.id, name: t.name, floor_name: t.floor_name, capacity: t.capacity, shape: t.shape || 'square', status: t.status, sort_order: t.sort_order || 0 };
            this.openTableModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        openOrderDetails(t) {
            this.activeTable = t;
            this.activeOrder = t.current_order;
            this.openRunningOrderModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        async saveTable() {
            if (!this.tableForm.name || !this.tableForm.floor_name || this.tableForm.capacity <= 0) {
                alert('অনুগ্রহ করে টেবিল নাম, ফ্লোর এবং ক্যাপাসিটি দিন!'); return;
            }
            try {
                const res = await fetch('{{ route('tables.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.tableForm)
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else { alert(data.message || 'সংরক্ষণ ব্যর্থ হয়েছে'); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async setTableStatus(table, status) {
            try {
                const res = await fetch(`/tables/${table.id}/status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ status })
                });
                const data = await res.json();
                if (data.success) {
                    table.status = status;
                    if (status === 'available') table.current_order = null;
                    window.playBeep(990, 80);
                }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async deleteTable(id, name) {
            if (!confirm(`আপনি কি "${name}" টেবিলটি মুছে ফেলতে চান?`)) return;
            try {
                const res = await fetch(`/tables/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        formatNumber(n) { return (parseFloat(n) || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); }
    };
}
</script>
@endpush
@endsection
