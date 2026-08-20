@extends('layouts.app')
@section('title', 'টেবিল QR কার্ডস প্রিন্ট')
@section('content')
<div class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Header -->
    <div class="flex items-center justify-between no-print">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(184,146,42,0.15); border:1.5px solid rgba(184,146,42,0.3);">
                    <i data-lucide="qr-code" class="w-5 h-5" style="color:#B8922A;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">টেবিল QR কোড প্রিন্ট শিট</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">টেবিলে রাখার জন্য মার্জিত QR কোড টেন্ট কার্ড প্রিন্ট করুন</p>
        </div>

        <button onclick="window.print()" class="btn-maroon px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>সকল QR কার্ড প্রিন্ট করুন</span>
        </button>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 print:grid-cols-2 print:gap-6">
        @foreach($tables as $table)
        <div class="bg-white rounded-3xl p-6 border shadow-sm text-center flex flex-col items-center justify-between space-y-4 print:shadow-none print:border-2"
             style="border-color:#E8DDD9;">

            <!-- Header -->
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest" style="color:#B8922A;">{{ $branch->restaurant_name ?? "Sultan's Dine" }}</p>
                <h2 class="text-xl font-black mt-0.5" style="color:#1A0A0C;">{{ $table->name }}</h2>
                <p class="text-xs font-semibold" style="color:#9B7A7E;">{{ $table->floor_name }}</p>
            </div>

            <!-- QR Code Canvas Container -->
            <div class="p-3 bg-white rounded-2xl border inline-block" style="border-color:#E8DDD9;">
                <div class="qr-canvas-holder" data-url="{{ $table->qr_url }}"></div>
            </div>

            <!-- Instructions -->
            <div class="space-y-1">
                <p class="text-xs font-bold" style="color:#8B1A2C;">📱 ক্যামেরা দিয়ে স্ক্যান করুন</p>
                <p class="text-[10px]" style="color:#9B7A7E;">ডিজিটাল মেনু দেখুন ও টেবিল থেকে সরাসরি অর্ডার দিন</p>
            </div>

            <!-- Card Footer -->
            <div class="w-full pt-3 border-t text-[9px] flex justify-between" style="border-color:#F0E8E5; color:#9B7A7E;">
                <span>বিনামূল্যে WiFi সংযুক্ত থাকুন</span>
                <span>BIN: {{ $branch->bin_number ?? '001928374-0102' }}</span>
            </div>
        </div>
        @endforeach
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.qr-canvas-holder').forEach(el => {
        const url = el.getAttribute('data-url');
        new QRCode(el, {
            text: url,
            width: 140,
            height: 140,
            correctLevel: QRCode.CorrectLevel.M
        });
    });
});
</script>
@endpush

<style>
@media print {
    .sidebar, .no-print, nav, header { display: none !important; }
    main { padding: 0 !important; background: #fff !important; }
    body { background: #fff !important; }
}
</style>
@endsection
