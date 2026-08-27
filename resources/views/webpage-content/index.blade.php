@extends('layouts.app')

@section('title', 'ওয়েবপেজ কনটেন্ট ম্যানেজমেন্ট (Webpage Content CMS)')

@push('styles')
<style>
    .cms-layout-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    @media (min-width: 1024px) {
        .cms-layout-grid {
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
        }
        .cms-sidebar-col {
            width: 290px;
            flex-shrink: 0;
            position: sticky;
            top: 1rem;
        }
        .cms-editor-col {
            flex: 1;
            min-width: 0;
        }
    }
    .cms-tab-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.8125rem;
        font-weight: 700;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        background: transparent;
        color: #4B5563;
        cursor: pointer;
        text-align: left;
    }
    .cms-tab-btn:hover {
        background: #F3F4F6;
        color: #111827;
    }
    .cms-tab-btn.active {
        background: #801424 !important;
        color: #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(128, 20, 36, 0.35);
        border-color: #D4AC50 !important;
    }
    .cms-tab-btn.active i {
        color: #D4AC50 !important;
    }
    .cms-save-btn {
        background: linear-gradient(135deg, #801424 0%, #A01B2E 100%) !important;
        color: #FFFFFF !important;
        border: 1.5px solid #D4AC50 !important;
        box-shadow: 0 4px 15px rgba(128, 20, 36, 0.35);
        padding: 0.625rem 1.25rem;
        border-radius: 0.75rem;
        font-weight: 800;
        font-size: 0.8125rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .cms-save-btn:hover {
        background: linear-gradient(135deg, #630C19 0%, #801424 100%) !important;
        box-shadow: 0 6px 20px rgba(128, 20, 36, 0.5);
        transform: translateY(-1px);
    }
    .cms-save-btn:active {
        transform: scale(0.98);
    }
</style>
@endpush

@section('content')
<div x-data="webpageCmsApp()" x-init="init()" class="p-4 sm:p-6 max-w-[1600px] mx-auto cms-layout-wrapper">

    <!-- Top Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-[#E5E0DC] shadow-xs">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#801424] text-[#D4AC50] shadow-sm">
                <i data-lucide="globe" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-xl font-black text-gray-900 flex items-center gap-2">
                    <span>ওয়েবপেজ কনটেন্ট ম্যানেজমেন্ট</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300">Live CMS</span>
                </h1>
                <p class="text-xs text-gray-500 mt-0.5">১০টি লাক্সারি ল্যান্ডিং পেজের সকল টেক্সট, ছবি, শেফ, মেনু, অফার ও তথ্য ডাইনামিক পরিবর্তন করুন</p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('home') }}" target="_blank"
               class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold flex items-center gap-1.5 transition-all">
                <i data-lucide="external-link" class="w-4 h-4 text-gray-600"></i>
                <span>ওয়েবসাইট প্রিভিউ</span>
            </a>

            <button type="button" @click="resetAllToDefault()"
                    class="px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold flex items-center gap-1.5 transition-all">
                <i data-lucide="rotate-ccw" class="w-4 h-4 text-rose-600"></i>
                <span>রিসেট ডিফল্ট</span>
            </button>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="toast.show" x-cloak
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed bottom-6 right-6 z-50 px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border text-xs font-bold"
         :class="toast.type === 'success' ? 'bg-emerald-900 text-emerald-100 border-emerald-500' : 'bg-rose-900 text-rose-100 border-rose-500'">
        <i :data-lucide="toast.type === 'success' ? 'check-circle' : 'alert-triangle'" class="w-5 h-5"></i>
        <span x-text="toast.message"></span>
    </div>

    <!-- Main Tabbed Layout -->
    <div class="cms-layout-grid">
        
        <!-- Left Tab Navigation Menu (Sidebar) -->
        <div class="cms-sidebar-col bg-white p-3 rounded-2xl border border-[#E5E0DC] shadow-xs space-y-1">
            <p class="px-3 py-2 text-[10px] font-extrabold uppercase tracking-widest text-gray-400">সেকশন নির্বাচন করুন</p>
            
            <template x-for="tab in tabList" :key="tab.id">
                <button type="button" @click="activeTab = tab.id"
                        class="cms-tab-btn"
                        :class="activeTab === tab.id ? 'active' : ''">
                    <div class="flex items-center gap-2.5 truncate">
                        <i :data-lucide="tab.icon" class="w-4 h-4 shrink-0"></i>
                        <span x-text="tab.label" class="truncate"></span>
                    </div>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 opacity-60"></i>
                </button>
            </template>
        </div>

        <!-- Right Content Editor Panels -->
        <div class="cms-editor-col bg-white p-6 sm:p-8 rounded-2xl border border-[#E5E0DC] shadow-xs">
            
            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 1: HERO & GENERAL SECTION                       -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'hero'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">🏠 হিরো ব্যানার ও ব্র্যান্ড ইনফো</h2>
                        <p class="text-xs text-gray-500">হোম পেজের প্রধান ব্যানার, টাইটেল, বাটন এবং ৪টি ফুড কোলাজ ছবি</p>
                    </div>
                    <button type="button" @click="saveSection('hero', sections.hero)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">ওয়েলকাম ট্যাগলাইন</label>
                        <input type="text" x-model="sections.hero.tagline" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">ভিডিও লিংক (YouTube/MP4)</label>
                        <input type="url" x-model="sections.hero.video_url" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">প্রধান শিরোনাম (Line 1)</label>
                        <input type="text" x-model="sections.hero.title_line1" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">প্রধান শিরোনাম (Line 2)</label>
                        <input type="text" x-model="sections.hero.title_line2" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">বর্ণনা / সাবটাইটেল</label>
                    <textarea rows="3" x-model="sections.hero.description" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">বাটন টেক্সট</label>
                        <input type="text" x-model="sections.hero.btn_text" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">বাটন লিংক (URL)</label>
                        <input type="text" x-model="sections.hero.btn_url" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold">
                    </div>
                </div>

                <!-- Hero Section Background Food Image -->
                <div class="p-4 bg-amber-50/60 rounded-xl border border-amber-200 space-y-3">
                    <h3 class="text-xs font-bold text-amber-900 uppercase tracking-wider">হিরো সেকশনের ফুড ব্যাকগ্রাউন্ড ছবি (Hero Food Background Image)</h3>
                    <div class="flex flex-col sm:flex-row gap-4 items-center">
                        <div class="w-24 h-16 rounded-lg overflow-hidden border bg-gray-200 shrink-0">
                            <img :src="sections.hero.bg_image || 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?auto=format&fit=crop&w=1920&q=80'" alt="Bg Preview" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 w-full space-y-1.5">
                            <div class="flex gap-2">
                                <input type="text" x-model="sections.hero.bg_image" placeholder="Background Image URL" class="w-full px-3 py-2 rounded-lg border text-xs font-semibold bg-white">
                                <label class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-xs font-bold cursor-pointer shrink-0 flex items-center gap-1">
                                    <span>ছবি আপলোড</span>
                                    <input type="file" class="hidden" accept="image/*" @change="uploadFile($event, (url) => sections.hero.bg_image = url)">
                                </label>
                            </div>
                            <p class="text-[11px] text-gray-500">হোম পেজের প্রধান হিরো সেকশনের ব্যাকগ্রাউন্ডে প্রদর্শিত হবে।</p>
                        </div>
                    </div>
                </div>

                <!-- 4 Hero Images Grid -->
                <div class="pt-4 border-t space-y-3">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">হিরো ফুড কোলাজ ৪টি ছবি (Image URLs or Upload)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <template x-for="idx in [1, 2, 3, 4]" :key="idx">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 space-y-2">
                                <div class="h-28 rounded-lg overflow-hidden border bg-gray-200 relative">
                                    <img :src="sections.hero['image' + idx]" alt="Preview" class="w-full h-full object-cover">
                                </div>
                                <input type="text" x-model="sections.hero['image' + idx]" placeholder="Image URL" class="w-full px-2.5 py-1.5 rounded-lg border text-[11px]">
                                <label class="block text-center py-1 px-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-[10px] font-bold cursor-pointer transition-all">
                                    <span>ছবি আপলোড করুন</span>
                                    <input type="file" class="hidden" accept="image/*" @change="uploadFile($event, (url) => sections.hero['image' + idx] = url)">
                                </label>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 2: SPECIALIST CUISINES & FACILITIES             -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'cuisines'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">🍽️ স্পেশাল কুইজিন ও ফ্যাসিলিটিজ</h2>
                        <p class="text-xs text-gray-500">Discover Our Specialist Cuisine (৪টি কার্ড)</p>
                    </div>
                    <button type="button" @click="saveSection('cuisines', sections.cuisines)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <template x-for="(cuisine, idx) in sections.cuisines" :key="idx">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-gray-700" x-text="'কুইজিন কার্ড #' + (idx + 1)"></span>
                                <input type="text" x-model="cuisine.icon" placeholder="Lucide Icon (e.g. utensils)" class="w-28 px-2 py-1 rounded border text-[11px]">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 mb-1">কুইজিনের নাম</label>
                                <input type="text" x-model="cuisine.title" class="w-full px-3 py-1.5 rounded-lg border text-xs font-semibold">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 mb-1">সংক্ষিপ্ত বিবরণ</label>
                                <textarea rows="2" x-model="cuisine.description" class="w-full px-3 py-1.5 rounded-lg border text-xs"></textarea>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 3: ABOUT US & FOUNDER                           -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'about'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">📖 অ্যাবাউট আস, হিস্ট্রি ও ফাউন্ডার</h2>
                        <p class="text-xs text-gray-500">রেস্তোরাঁর গল্প, ইতিহাস এবং প্রতিষ্ঠাতার তথ্য</p>
                    </div>
                    <button type="button" @click="saveSection('about', sections.about)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">ট্যাগলাইন</label>
                        <input type="text" x-model="sections.about.tagline" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">হেডিং</label>
                        <input type="text" x-model="sections.about.title" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">গল্প প্যারাগ্রাফ ১</label>
                        <textarea rows="3" x-model="sections.about.story_p1" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">গল্প প্যারাগ্রাফ ২</label>
                        <textarea rows="3" x-model="sections.about.story_p2" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold"></textarea>
                    </div>
                </div>

                <!-- Founder Details -->
                <div class="p-4 bg-amber-50/50 rounded-xl border border-amber-200 space-y-4">
                    <h3 class="text-xs font-bold text-amber-900 uppercase tracking-wider">প্রতিষ্ঠাতার বাণী ও প্রোফাইল</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">প্রতিষ্ঠাতার নাম</label>
                            <input type="text" x-model="sections.about.founder_name" class="w-full px-3 py-2 rounded-lg border text-xs font-semibold bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">প্রতিষ্ঠাতার ছবির URL</label>
                            <div class="flex gap-2">
                                <input type="text" x-model="sections.about.founder_image" class="w-full px-3 py-2 rounded-lg border text-xs font-semibold bg-white">
                                <label class="px-3 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-xs font-bold cursor-pointer shrink-0">
                                    <span>আপলোড</span>
                                    <input type="file" class="hidden" accept="image/*" @change="uploadFile($event, (url) => sections.about.founder_image = url)">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">প্রতিষ্ঠাতার কোটেশন / বাণী</label>
                        <textarea rows="2" x-model="sections.about.founder_quote" class="w-full px-3 py-2 rounded-lg border text-xs bg-white"></textarea>
                    </div>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 4: COUNTERS & STATS                             -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'stats'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">📊 কাউন্টার ও স্ট্যাটিস্টিকস</h2>
                        <p class="text-xs text-gray-500">রেস্তোরাঁর মোট ব্রাঞ্চ, অভিজ্ঞতা, অ্যাওয়ার্ড ও মেনুর সংখ্যা</p>
                    </div>
                    <button type="button" @click="saveSection('stats', sections.stats)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl border space-y-2">
                        <label class="block text-xs font-bold text-gray-700">রেস্তোরাঁ সংখ্যা</label>
                        <input type="text" x-model="sections.stats.restaurants" class="w-full px-3 py-2 rounded-lg border text-xs font-bold">
                        <input type="text" x-model="sections.stats.restaurants_label" class="w-full px-3 py-1.5 rounded-lg border text-[11px] text-gray-500">
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl border space-y-2">
                        <label class="block text-xs font-bold text-gray-700">অভিজ্ঞতার বছর</label>
                        <input type="text" x-model="sections.stats.experience_years" class="w-full px-3 py-2 rounded-lg border text-xs font-bold">
                        <input type="text" x-model="sections.stats.experience_label" class="w-full px-3 py-1.5 rounded-lg border text-[11px] text-gray-500">
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl border space-y-2">
                        <label class="block text-xs font-bold text-gray-700">অ্যাওয়ার্ড সংখ্যা</label>
                        <input type="text" x-model="sections.stats.awards_won" class="w-full px-3 py-2 rounded-lg border text-xs font-bold">
                        <input type="text" x-model="sections.stats.awards_label" class="w-full px-3 py-1.5 rounded-lg border text-[11px] text-gray-500">
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl border space-y-2">
                        <label class="block text-xs font-bold text-gray-700">ফুড মেনু আইটেম</label>
                        <input type="text" x-model="sections.stats.food_menus" class="w-full px-3 py-2 rounded-lg border text-xs font-bold">
                        <input type="text" x-model="sections.stats.menus_label" class="w-full px-3 py-1.5 rounded-lg border text-[11px] text-gray-500">
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl border space-y-2">
                        <label class="block text-xs font-bold text-gray-700">সন্তুষ্ট কাস্টমার</label>
                        <input type="text" x-model="sections.stats.customers" class="w-full px-3 py-2 rounded-lg border text-xs font-bold">
                        <input type="text" x-model="sections.stats.customers_label" class="w-full px-3 py-1.5 rounded-lg border text-[11px] text-gray-500">
                    </div>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 5: SUNDAY OFFERS & RECOMMENDED DISHES           -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'offers'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">⭐ হট অফার (Sunday Offers - 20% OFF)</h2>
                        <p class="text-xs text-gray-500">মেনু পেজের স্পেশাল রবিবারের অফার আইটেমসমূহ</p>
                    </div>
                    <button type="button" @click="saveSection('sunday_offers', sections.sunday_offers)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <template x-for="(offer, idx) in sections.sunday_offers" :key="idx">
                        <div class="p-3 bg-gray-50 rounded-xl border space-y-2">
                            <div class="h-28 rounded-lg overflow-hidden border bg-gray-200 relative">
                                <img :src="offer.image" alt="Preview" class="w-full h-full object-cover">
                                <span class="absolute top-1 left-1 px-1.5 py-0.5 rounded bg-amber-400 text-black font-black text-[9px]" x-text="offer.discount"></span>
                            </div>
                            <input type="text" x-model="offer.title" placeholder="নাম" class="w-full px-2.5 py-1.5 rounded border text-xs font-bold">
                            <div class="flex gap-2">
                                <input type="text" x-model="offer.price" placeholder="মূল্য ($18)" class="w-1/2 px-2 py-1 rounded border text-xs font-bold text-[#801424]">
                                <input type="text" x-model="offer.discount" placeholder="ডিসকাউন্ট ট্যাগ" class="w-1/2 px-2 py-1 rounded border text-xs">
                            </div>
                            <input type="text" x-model="offer.image" placeholder="Image URL" class="w-full px-2 py-1 rounded border text-[10px]">
                            <label class="block text-center py-1 px-2 rounded bg-gray-200 hover:bg-gray-300 text-[10px] font-bold cursor-pointer">
                                <span>ছবি আপলোড</span>
                                <input type="file" class="hidden" accept="image/*" @change="uploadFile($event, (url) => offer.image = url)">
                            </label>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 6: DOTTED MENUS BY CATEGORY                     -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'dotted_menus'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">📋 ডটেড মেনু তালিকা (Dotted Menus)</h2>
                        <p class="text-xs text-gray-500">Appetizer, Main Course, Dessert ও Specials তালিকার আইটেম ও ডটেড প্রাইস</p>
                    </div>
                    <button type="button" @click="saveSection('dotted_menus', sections.dotted_menus)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Appetizers -->
                    <div class="p-4 bg-gray-50 rounded-xl border space-y-3">
                        <h3 class="text-xs font-bold text-[#801424] uppercase tracking-wider">🥗 Appetizers (অ্যাপেটাইজার মেনু)</h3>
                        <template x-for="(item, idx) in sections.dotted_menus.appetizers" :key="idx">
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center bg-white p-2.5 rounded-lg border">
                                <div class="sm:col-span-4">
                                    <input type="text" x-model="item.name" placeholder="খাবারের নাম" class="w-full px-2.5 py-1 rounded border text-xs font-bold">
                                </div>
                                <div class="sm:col-span-2">
                                    <input type="text" x-model="item.price" placeholder="মূল্য ($20)" class="w-full px-2.5 py-1 rounded border text-xs font-bold text-[#801424]">
                                </div>
                                <div class="sm:col-span-6">
                                    <input type="text" x-model="item.desc" placeholder="সংক্ষিপ্ত বিবরণ" class="w-full px-2.5 py-1 rounded border text-xs text-gray-500">
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Main Course -->
                    <div class="p-4 bg-gray-50 rounded-xl border space-y-3">
                        <h3 class="text-xs font-bold text-[#801424] uppercase tracking-wider">🍲 Main Course (মেইন কোর্স মেনু)</h3>
                        <template x-for="(item, idx) in sections.dotted_menus.main_course" :key="idx">
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center bg-white p-2.5 rounded-lg border">
                                <div class="sm:col-span-4">
                                    <input type="text" x-model="item.name" placeholder="খাবারের নাম" class="w-full px-2.5 py-1 rounded border text-xs font-bold">
                                </div>
                                <div class="sm:col-span-2">
                                    <input type="text" x-model="item.price" placeholder="মূল্য ($32)" class="w-full px-2.5 py-1 rounded border text-xs font-bold text-[#801424]">
                                </div>
                                <div class="sm:col-span-6">
                                    <input type="text" x-model="item.desc" placeholder="সংক্ষিপ্ত বিবরণ" class="w-full px-2.5 py-1 rounded border text-xs text-gray-500">
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Desserts -->
                    <div class="p-4 bg-gray-50 rounded-xl border space-y-3">
                        <h3 class="text-xs font-bold text-[#801424] uppercase tracking-wider">🍰 Desserts (মিষ্টান্ন ও ডেজার্ট মেনু)</h3>
                        <template x-for="(item, idx) in sections.dotted_menus.desserts" :key="idx">
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center bg-white p-2.5 rounded-lg border">
                                <div class="sm:col-span-4">
                                    <input type="text" x-model="item.name" placeholder="খাবারের নাম" class="w-full px-2.5 py-1 rounded border text-xs font-bold">
                                </div>
                                <div class="sm:col-span-2">
                                    <input type="text" x-model="item.price" placeholder="মূল্য ($16)" class="w-full px-2.5 py-1 rounded border text-xs font-bold text-[#801424]">
                                </div>
                                <div class="sm:col-span-6">
                                    <input type="text" x-model="item.desc" placeholder="সংক্ষিপ্ত বিবরণ" class="w-full px-2.5 py-1 rounded border text-xs text-gray-500">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 7: MASTER CHEFS                                 -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'chefs'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">👨‍🍳 মাস্টার শেফ প্রোফাইলস (Meet Our Chefs)</h2>
                        <p class="text-xs text-gray-500">৬ জন আন্তর্জাতিক শেফের নাম, পদবি, ছবি এবং সোশ্যাল মিডিয়া লিংক</p>
                    </div>
                    <button type="button" @click="saveSection('chefs', sections.chefs)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <template x-for="(chef, idx) in sections.chefs" :key="idx">
                        <div class="p-3.5 bg-gray-50 rounded-xl border space-y-2.5">
                            <div class="h-36 rounded-lg overflow-hidden border bg-gray-200 relative">
                                <img :src="chef.image" alt="Chef" class="w-full h-full object-cover">
                            </div>
                            <input type="text" x-model="chef.name" placeholder="শেফের নাম" class="w-full px-2.5 py-1.5 rounded border text-xs font-bold">
                            <input type="text" x-model="chef.designation" placeholder="পদবি" class="w-full px-2.5 py-1 rounded border text-[11px] text-gray-600">
                            
                            <div class="grid grid-cols-3 gap-1">
                                <input type="text" x-model="chef.facebook" placeholder="FB URL" class="px-1.5 py-1 rounded border text-[10px]">
                                <input type="text" x-model="chef.instagram" placeholder="Insta URL" class="px-1.5 py-1 rounded border text-[10px]">
                                <input type="text" x-model="chef.twitter" placeholder="Twitter URL" class="px-1.5 py-1 rounded border text-[10px]">
                            </div>

                            <label class="block text-center py-1 px-2 rounded bg-gray-200 hover:bg-gray-300 text-[10px] font-bold cursor-pointer">
                                <span>ছবি আপলোড</span>
                                <input type="file" class="hidden" accept="image/*" @change="uploadFile($event, (url) => chef.image = url)">
                            </label>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 8: DINING PACKAGES & PRICING                    -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'packages'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">💎 ডাইনিং প্যাকেজ ও প্রাইসিং (Single, Couple, Family)</h2>
                        <p class="text-xs text-gray-500">স্পেশাল কাস্টমার প্যাকেজের মূল্য এবং বুলেট পয়েন্ট সুবিধাসমূহ</p>
                    </div>
                    <button type="button" @click="saveSection('packages', sections.packages)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <template x-for="(pkg, idx) in sections.packages" :key="idx">
                        <div class="p-4 rounded-xl border space-y-3" :class="pkg.is_featured ? 'bg-amber-50 border-amber-300' : 'bg-gray-50 border-gray-200'">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black" x-text="pkg.name + ' প্যাকেজ'"></span>
                                <span x-show="pkg.is_featured" class="px-2 py-0.5 rounded bg-amber-400 text-black text-[9px] font-bold">Featured</span>
                            </div>

                            <div class="flex gap-2">
                                <input type="text" x-model="pkg.price" placeholder="মূল্য ($29.99)" class="w-2/3 px-2.5 py-1.5 rounded border text-xs font-bold text-[#801424] bg-white">
                                <input type="text" x-model="pkg.billing" placeholder="/ day" class="w-1/3 px-2 py-1.5 rounded border text-xs bg-white">
                            </div>

                            <div class="space-y-1.5 pt-2 border-t">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase">সুবিধাসমূহ (৫টি লাইন)</label>
                                <template x-for="(feat, fIdx) in pkg.features" :key="fIdx">
                                    <input type="text" x-model="pkg.features[fIdx]" class="w-full px-2 py-1 rounded border text-[11px] bg-white">
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 9: CUSTOMER REVIEWS & TESTIMONIALS              -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'testimonials'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">💬 কাস্টমার রিভিউ ও টেস্টমোনিয়াল</h2>
                        <p class="text-xs text-gray-500">ওয়েবসাইটের প্রশংসাসূচক রিভিউ ও ক্লায়েন্ট তথ্য</p>
                    </div>
                    <button type="button" @click="saveSection('testimonials', sections.testimonials)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(testi, idx) in sections.testimonials" :key="idx">
                        <div class="p-4 bg-gray-50 rounded-xl border space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 mb-1">কাস্টমারের নাম</label>
                                    <input type="text" x-model="testi.name" class="w-full px-3 py-1.5 rounded-lg border text-xs font-bold">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 mb-1">পদবি / পেশা</label>
                                    <input type="text" x-model="testi.role" class="w-full px-3 py-1.5 rounded-lg border text-xs">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-gray-600 mb-1">রিভিউ কোটেশন</label>
                                <textarea rows="2" x-model="testi.quote" class="w-full px-3 py-1.5 rounded-lg border text-xs"></textarea>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 10: FAQ MANAGEMENT                              -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'faqs'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">❓ সচরাচর জিজ্ঞাসিত প্রশ্ন (FAQs)</h2>
                        <p class="text-xs text-gray-500">FAQ পেজ এবং একর্ডিয়ন এর প্রশ্ন ও উত্তর</p>
                    </div>
                    <button type="button" @click="saveSection('faqs', sections.faqs)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(faq, idx) in sections.faqs" :key="idx">
                        <div class="p-4 bg-gray-50 rounded-xl border space-y-2">
                            <label class="block text-xs font-bold text-gray-700" x-text="'প্রশ্ন #' + (idx + 1)"></label>
                            <input type="text" x-model="faq.question" placeholder="প্রশ্ন" class="w-full px-3 py-1.5 rounded-lg border text-xs font-bold">
                            <textarea rows="2" x-model="faq.answer" placeholder="উত্তর" class="w-full px-3 py-1.5 rounded-lg border text-xs"></textarea>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 11: CONTACT, HOURS & SOCIAL                     -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'contact'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">🕒 যোগাযোগ, খোলার সময় ও সোশ্যাল মিডিয়া</h2>
                        <p class="text-xs text-gray-500">হটলাইন নম্বর, ইমেইল, রেস্তোরাঁর ঠিকানা এবং সোশ্যাল লিংক</p>
                    </div>
                    <button type="button" @click="saveSection('contact', sections.contact)" :disabled="saving" class="cms-save-btn">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">হটলাইন ফোন নম্বর</label>
                        <input type="text" x-model="sections.contact.phone" class="w-full px-3.5 py-2 rounded-xl border text-xs font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">ইমেইল ঠিকানা</label>
                        <input type="email" x-model="sections.contact.email" class="w-full px-3.5 py-2 rounded-xl border text-xs font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">রেস্তোরাঁর ঠিকানা</label>
                        <input type="text" x-model="sections.contact.address" class="w-full px-3.5 py-2 rounded-xl border text-xs font-bold">
                    </div>
                </div>

                <!-- Opening Hours -->
                <div class="p-4 bg-gray-50 rounded-xl border space-y-3">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">খোলার সময়সূচি (Opening Hours)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">সোম - শুক্র (Mon - Fri)</label>
                            <input type="text" x-model="sections.contact.opening_hours.mon_fri" class="w-full px-3 py-1.5 rounded-lg border text-xs bg-white">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">শনিবার (Saturday)</label>
                            <input type="text" x-model="sections.contact.opening_hours.sat" class="w-full px-3 py-1.5 rounded-lg border text-xs bg-white">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">রবিবার (Sunday)</label>
                            <input type="text" x-model="sections.contact.opening_hours.sun" class="w-full px-3 py-1.5 rounded-lg border text-xs bg-white text-red-600 font-bold">
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="p-4 bg-gray-50 rounded-xl border space-y-3">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">সোশ্যাল মিডিয়া প্রোফাইল লিংকসমূহ</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">Facebook URL</label>
                            <input type="text" x-model="sections.contact.social.facebook" class="w-full px-3 py-1.5 rounded-lg border text-xs bg-white">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">Instagram URL</label>
                            <input type="text" x-model="sections.contact.social.instagram" class="w-full px-3 py-1.5 rounded-lg border text-xs bg-white">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">Twitter URL</label>
                            <input type="text" x-model="sections.contact.social.twitter" class="w-full px-3 py-1.5 rounded-lg border text-xs bg-white">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 mb-1">YouTube URL</label>
                            <input type="text" x-model="sections.contact.social.youtube" class="w-full px-3 py-1.5 rounded-lg border text-xs bg-white">
                        </div>
                    </div>
                </div>
            <!-- ════════════════════════════════════════════════════ -->
            <!-- TAB 12: PARTNERS & SPONSORS (FULLY DYNAMIC)         -->
            <!-- ════════════════════════════════════════════════════ -->
            <div x-show="activeTab === 'partners'" x-cloak class="space-y-6">
                <div class="flex items-center justify-between border-b pb-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">🤝 পার্টনার ও স্পন্সর লোগো (Brand Logos)</h2>
                        <p class="text-xs text-gray-500">হোম পেজের অটো-স্ক্রোলিং পার্টনার ও স্পন্সরদের অরিজিনাল লোগো আপলোড ও ম্যানেজ করুন</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="sections.partners.items = sections.partners.items || []; sections.partners.items.push({ name: '', logo: '', url: '' })" class="px-3 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 text-xs font-bold flex items-center gap-1.5 transition-all">
                            <i data-lucide="plus" class="w-4 h-4 text-amber-700"></i>
                            <span>+ নতুন লোগো যোগ</span>
                        </button>
                        <button type="button" @click="saveSection('partners', sections.partners)" :disabled="saving" class="cms-save-btn">
                            <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                            <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন'"></span>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">ট্যাগলাইন</label>
                        <input type="text" x-model="sections.partners.tagline" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">প্রধান শিরোনাম</label>
                        <input type="text" x-model="sections.partners.title" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 text-xs font-semibold">
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider">পার্টনার ও স্পন্সর লোগো তালিকা</label>
                        <span class="text-[11px] text-gray-500 font-semibold" x-text="(sections.partners.items ? sections.partners.items.length : 0) + ' টি লোগো যুক্ত আছে'"></span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-for="(p, pIdx) in sections.partners.items" :key="pIdx">
                            <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3 relative group">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-700" x-text="'লোগো #' + (pIdx + 1)"></span>
                                    <button type="button" @click="sections.partners.items.splice(pIdx, 1)" class="text-rose-600 hover:text-rose-800 p-1 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="মুছে ফেলুন">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>

                                <!-- Logo Image Thumbnail Preview -->
                                <div class="h-16 bg-black/90 rounded-xl flex items-center justify-center p-2 border border-gray-300 overflow-hidden">
                                    <template x-if="p.logo">
                                        <img :src="p.logo" :alt="p.name" class="h-10 w-auto max-w-[120px] object-contain">
                                    </template>
                                    <template x-if="!p.logo">
                                        <span class="text-[11px] text-gray-400 font-bold" x-text="p.name || 'No Logo Selected'"></span>
                                    </template>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 mb-1">ব্র্যান্ডের নাম</label>
                                    <input type="text" x-model="p.name" placeholder="যেমন: Coca-Cola" class="w-full px-3 py-1.5 rounded-lg border text-xs font-bold">
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 mb-1">লোগো ছবি (Image URL বা আপলোড)</label>
                                    <div class="flex gap-1.5">
                                        <input type="text" x-model="p.logo" placeholder="Logo Image/SVG URL" class="w-full px-2.5 py-1.5 rounded-lg border text-[11px]">
                                        <label class="px-2.5 py-1.5 bg-gray-200 hover:bg-gray-300 rounded-lg text-[10px] font-bold cursor-pointer shrink-0 flex items-center">
                                            <span>আপলোড</span>
                                            <input type="file" class="hidden" accept="image/*,.svg" @change="uploadFile($event, (url) => p.logo = url)">
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[11px] font-bold text-gray-600 mb-1">ওয়েবসাইট লিংক (URL)</label>
                                    <input type="url" x-model="p.url" placeholder="https://brand.com" class="w-full px-3 py-1.5 rounded-lg border text-[11px]">
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="pt-2">
                        <button type="button" @click="sections.partners.items = sections.partners.items || []; sections.partners.items.push({ name: '', logo: '', url: '' })" class="w-full py-3 border-2 border-dashed border-gray-300 hover:border-[#801424] hover:bg-rose-50/40 rounded-2xl text-xs font-bold text-gray-600 hover:text-[#801424] flex items-center justify-center gap-2 transition-all">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            <span>+ আরও পার্টনার / স্পন্সর লোগো যোগ করুন</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- ════ STICKY BOTTOM SAVE ACTION BAR ════ -->
            <div class="sticky bottom-4 z-40 bg-white/95 backdrop-blur-md p-4 rounded-2xl border-2 border-[#D4AC50] shadow-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-8">
                <div class="flex items-center gap-2 text-xs font-bold text-gray-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>বর্তমান নির্বাচিত সেকশন: <strong class="text-[#801424]" x-text="getCurrentTabName()"></strong></span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" @click="saveCurrentTab()" :disabled="saving" class="cms-save-btn w-full sm:w-auto px-7 py-3">
                        <i data-lucide="save" class="w-4 h-4 text-[#D4AC50]"></i>
                        <span x-text="saving ? 'সংরক্ষণ হচ্ছে...' : 'সেভ পরিবর্তন (Save Changes)'"></span>
                    </button>
                </div>
            </div>

        </div>

    </div>

</div>

<script>
function webpageCmsApp() {
    return {
        activeTab: 'hero',
        saving: false,
        toast: { show: false, message: '', type: 'success' },
        sections: @json($sections),

        tabList: [
            { id: 'hero', label: 'হিরো ও ব্র্যান্ড ব্যানার', icon: 'home' },
            { id: 'cuisines', label: 'স্পেশাল কুইজিন', icon: 'utensils' },
            { id: 'about', label: 'আমাদের গল্প ও হিস্ট্রি', icon: 'book-open' },
            { id: 'stats', label: 'কাউন্টার ও স্ট্যাটস', icon: 'bar-chart-2' },
            { id: 'offers', label: 'রবিবারের স্পেশাল অফার', icon: 'tag' },
            { id: 'dotted_menus', label: 'ডটেড মেনু তালিকা', icon: 'list' },
            { id: 'chefs', label: 'মাস্টার শেফ টিম', icon: 'chef-hat' },
            { id: 'packages', label: 'ডাইনিং প্যাকেজ ও রেট', icon: 'gift' },
            { id: 'testimonials', label: 'কাস্টমার রিভিউ', icon: 'message-square' },
            { id: 'partners', label: 'পার্টনার ও স্পন্সর লোগো', icon: 'shield-check' },
            { id: 'faqs', label: 'সচরাচর প্রশ্ন (FAQs)', icon: 'help-circle' },
            { id: 'contact', label: 'যোগাযোগ ও খোলার সময়', icon: 'clock' },
        ],

        getCurrentTabName() {
            const found = this.tabList.find(t => t.id === this.activeTab);
            return found ? found.label : 'সেকশন';
        },

        saveCurrentTab() {
            let sectionKey = this.activeTab;
            if (sectionKey === 'offers') sectionKey = 'sunday_offers';
            
            const payload = this.sections[sectionKey];
            if (payload !== undefined) {
                this.saveSection(sectionKey, payload);
            }
        },

        init() {
            this.$nextTick(() => {
                if (window.lucide) window.lucide.createIcons();
            });
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => this.toast.show = false, 3500);
        },

        async saveSection(sectionKey, content) {
            this.saving = true;
            try {
                const res = await fetch('{{ route('webpage-content.section.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ section_key: sectionKey, content: content })
                });
                const data = await res.json();
                if (data.success) {
                    this.showToast(data.message, 'success');
                } else {
                    this.showToast(data.message || 'Error saving content', 'error');
                }
            } catch (err) {
                this.showToast('Network error while saving.', 'error');
            } finally {
                this.saving = false;
            }
        },

        async uploadFile(event, callback) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            try {
                this.showToast('ছবি আপলোড হচ্ছে...', 'success');
                const res = await fetch('{{ route('webpage-content.upload') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    callback(data.url);
                    this.showToast('ছবি সফলভাবে আপলোড হয়েছে!', 'success');
                } else {
                    this.showToast(data.message || 'আপলোড ব্যর্থ হয়েছে', 'error');
                }
            } catch (err) {
                this.showToast('Upload error', 'error');
            }
        },

        async resetAllToDefault() {
            if (!confirm('আপনি কি সত্যিই সকল ওয়েবপেজ সেকশন মূল ডিফল্টে ফিরিয়ে নিতে চান?')) return;
            try {
                const res = await fetch('{{ route('webpage-content.reset') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                });
                const data = await res.json();
                if (data.success) {
                    alert('সফলভাবে রিসেট হয়েছে! পেজটি রিফ্রেশ হবে।');
                    location.reload();
                }
            } catch (err) {
                alert('Reset error');
            }
        }
    };
}
</script>
@endsection
