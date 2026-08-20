@extends('layouts.app')
@section('title', 'মাল্টি-ব্রাঞ্চ স্টক ট্রান্সফার')
@section('content')
<div x-data="transferManager()" x-init="init()" class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(184,146,42,0.15); border:1.5px solid rgba(184,146,42,0.3);">
                    <i data-lucide="truck" class="w-5 h-5" style="color:#B8922A;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">মাল্টি-ব্রাঞ্চ স্টক ট্রান্সফার ও রিকুইজিশন</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">সেন্ট্রাল কিচেন ও ব্রাঞ্চগুলোর মাঝে কাঁচামাল পরিবহন ও রিসিভিং</p>
        </div>

        <button @click="openTransferModal = true; resetTransferForm();"
                class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>+ নতুন স্টক ট্রান্সফার</span>
        </button>
    </div>

    <!-- Transfers List -->
    <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                    <tr>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">ট্রান্সফার নং</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">উৎস ব্রাঞ্চ</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">গন্তব্য ব্রাঞ্চ</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">আইটেম বিবরণ</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">স্ট্যাটাস</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px] text-right" style="color:#9B7A7E;">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transfers as $t)
                    <tr class="data-row border-b" style="border-color:#F0E8E5;">
                        <td class="px-4 py-3.5 pos-nums font-black price-maroon">{{ $t->transfer_number }}</td>
                        <td class="px-4 py-3.5 font-bold" style="color:#1A0A0C;">{{ $t->sourceBranch->name ?? 'Head Office' }}</td>
                        <td class="px-4 py-3.5 font-bold" style="color:#2E7D52;">{{ $t->destinationBranch->name ?? 'Dhanmondi' }}</td>
                        <td class="px-4 py-3.5">
                            <div class="space-y-0.5">
                                @foreach($t->items as $ti)
                                <p class="text-[11px]" style="color:#5C3840;">
                                    • {{ $ti->ingredient->name ?? 'Item' }}: <strong>{{ $ti->quantity_sent }} {{ $ti->unit }}</strong>
                                </p>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            @if($t->status === 'received')
                                <span class="badge-paid px-2.5 py-1 rounded-full text-[10px] font-bold">✓ রিসিভড</span>
                            @else
                                <span class="badge-cooking px-2.5 py-1 rounded-full text-[10px] font-bold">রাস্তায় রয়েছে (In Transit)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            @if($t->status !== 'received')
                            <button @click="markReceived({{ $t->id }})"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold"
                                    style="background:#D1FAE5; color:#065F46; border:1px solid #A7F3D0;">
                                রিসিভ কনফার্ম করুন ✓
                            </button>
                            @else
                            <span class="text-[11px] text-gray-400">সম্পন্ন</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center" style="color:#9B7A7E;">কোনো স্টক ট্রান্সফার রেকর্ড পাওয়া যায়নি।</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-t" style="border-color:#E8DDD9; background:#FBF8F5;">
            {{ $transfers->links() }}
        </div>
    </div>

    <!-- ════ MODAL: CREATE TRANSFER ════ -->
    <div x-show="openTransferModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openTransferModal = false"
             class="w-full max-w-lg bg-white rounded-3xl overflow-hidden shadow-2xl border flex flex-col max-h-[90vh]"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white">নতুন স্টক ট্রান্সফার রিকুইজিশন</h3>
                <button @click="openTransferModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 space-y-3 overflow-y-auto flex-1">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">উৎস ব্রাঞ্চ (Source) *</label>
                        <select x-model="transferForm.source_branch_id" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                            <option value="">বেছে নিন...</option>
                            @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="section-heading">গন্তব্য ব্রাঞ্চ (Destination) *</label>
                        <select x-model="transferForm.destination_branch_id" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                            <option value="">বেছে নিন...</option>
                            @foreach($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="section-heading">কাঁচামাল সামগ্রী নির্বাচন</label>
                    <div class="space-y-2">
                        <template x-for="(row, idx) in transferForm.items" :key="idx">
                            <div class="flex items-center gap-2 p-2 rounded-xl bg-gray-50 border">
                                <select x-model="row.ingredient_id" @change="onIngredientSelect(idx)" class="pos-input flex-1 px-2 py-1.5 text-xs rounded-lg bg-white">
                                    <option value="">উপাদান সিলেক্ট...</option>
                                    @foreach($ingredients as $ing)
                                    <option value="{{ $ing->id }}" data-unit="{{ $ing->unit }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                    @endforeach
                                </select>
                                <input type="number" step="0.1" x-model.number="row.quantity_sent" placeholder="পরিমাণ" class="pos-input w-24 px-2 py-1.5 text-xs pos-nums font-bold rounded-lg bg-white price-maroon">
                                <span class="text-xs font-bold uppercase w-8 text-center" x-text="row.unit"></span>
                                <button @click="transferForm.items.splice(idx, 1)" class="p-1 text-gray-400 hover:text-rose-600">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <button @click="transferForm.items.push({ ingredient_id: '', quantity_sent: 5, unit: 'kg' })"
                            class="text-xs font-bold mt-2 hover:underline" style="color:#8B1A2C;">
                        + আরেকটি কাঁচামাল যোগ করুন
                    </button>
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openTransferModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="submitTransfer()" class="btn-maroon px-6 py-2.5 text-xs font-bold">ডিসপ্যাচ ও সেন্ড করুন</button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function transferManager() {
    return {
        openTransferModal: false,
        transferForm: {
            source_branch_id: '',
            destination_branch_id: '',
            notes: '',
            items: [{ ingredient_id: '', quantity_sent: 10, unit: 'kg' }]
        },

        init() { this.$nextTick(() => window.initLucideIcons()); },

        resetTransferForm() {
            this.transferForm = {
                source_branch_id: '{{ $branches->first()->id ?? 1 }}',
                destination_branch_id: '',
                notes: '',
                items: [{ ingredient_id: '', quantity_sent: 10, unit: 'kg' }]
            };
        },

        onIngredientSelect(idx) {
            const sel = event.target;
            const opt = sel.options[sel.selectedIndex];
            if (opt && opt.dataset.unit) this.transferForm.items[idx].unit = opt.dataset.unit;
        },

        async submitTransfer() {
            if (!this.transferForm.source_branch_id || !this.transferForm.destination_branch_id) {
                alert('উভয় ব্রাঞ্চ সিলেক্ট করুন!'); return;
            }
            try {
                const res = await fetch('{{ route('transfers.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.transferForm)
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async markReceived(transferId) {
            if (!confirm('আপনি কি নিশ্চিতভাবে এই মালামাল রিসিভ করেছেন?')) return;
            try {
                const res = await fetch(`/transfers/${transferId}/receive`, {
                    method: 'POST',
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
