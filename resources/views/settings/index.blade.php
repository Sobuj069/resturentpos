@extends('layouts.app')
@section('title', 'সিস্টেম ও রেস্টুরেন্ট সেটিংস')
@section('content')
<div x-data="settingsManager()" x-init="init()" class="min-h-full p-5 lg:p-6 space-y-5 pb-24" style="background:#F5F0EC;">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(139,26,44,0.1); border:1.5px solid rgba(139,26,44,0.25);">
                    <i data-lucide="settings" class="w-5 h-5" style="color:#8B1A2C;"></i>
                </div>
                <h1 class="text-lg font-extrabold" style="color:#1A0A0C;">সিস্টেম ও রেস্টুরেন্ট সেটিংস</h1>
            </div>
            <p class="text-xs" style="color:#9B7A7E;">রেস্টুরেন্টের তথ্য, NBR মূসক ভ্যাট, বিকাশ ও নগদ মার্চেন্ট নম্বর এবং স্টাফ ইউজার পরিচালনা</p>
        </div>

        <button @click="saveBranchSettings()" :disabled="isSaving"
                class="btn-maroon px-5 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span x-text="isSaving ? 'সংরক্ষণ হচ্ছে...' : 'সেটিংস আপডেট করুন'"></span>
        </button>
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-1 p-1 rounded-2xl bg-white border w-fit" style="border-color:#E8DDD9;">
        <button @click="activeTab = 'general'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                :style="activeTab === 'general' ? 'background:#8B1A2C; color:#fff;' : 'color:#5C3840; background:transparent;'">
            <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
            <span>রেস্টুরেন্ট ও NBR মূসক</span>
        </button>
        <button @click="activeTab = 'payments'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                :style="activeTab === 'payments' ? 'background:#8B1A2C; color:#fff;' : 'color:#5C3840; background:transparent;'">
            <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
            <span>পেমেন্ট গেটওয়ে ও MFS</span>
        </button>
        <button @click="activeTab = 'staff'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                :style="activeTab === 'staff' ? 'background:#8B1A2C; color:#fff;' : 'color:#5C3840; background:transparent;'">
            <i data-lucide="users" class="w-3.5 h-3.5"></i>
            <span>স্টাফ ও ক্যাশিয়ার একাউন্ট ({{ $users->count() }})</span>
        </button>
        <button @click="activeTab = 'shifts'"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                :style="activeTab === 'shifts' ? 'background:#8B1A2C; color:#fff;' : 'color:#5C3840; background:transparent;'">
            <i data-lucide="history" class="w-3.5 h-3.5"></i>
            <span>শিফট হিস্টোরি</span>
        </button>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TAB 1: RESTAURANT & NBR MUSHAK SETTINGS             -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'general'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Restaurant Identity -->
        <div class="pos-card p-5 space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b" style="border-color:#F0E8E5;">
                <i data-lucide="store" class="w-5 h-5" style="color:#8B1A2C;"></i>
                <h3 class="font-extrabold text-sm" style="color:#1A0A0C;">রেস্টুরেন্ট পরিচিতি ও ব্রাঞ্চ তথ্য</h3>
            </div>

            <div>
                <label class="section-heading">রেস্টুরেন্ট ব্র্যান্ড নাম *</label>
                <input type="text" x-model="branchForm.restaurant_name" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="section-heading">ব্রাঞ্চ নাম *</label>
                    <input type="text" x-model="branchForm.name" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
                <div>
                    <label class="section-heading">ব্রাঞ্চ কোড *</label>
                    <input type="text" x-model="branchForm.code" class="pos-input w-full px-3 py-2 text-xs pos-nums uppercase rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="section-heading">হেল্পলাইন / ফোন নম্বর</label>
                    <input type="text" x-model="branchForm.phone" class="pos-input w-full px-3 py-2 text-xs pos-nums rounded-xl">
                </div>
                <div>
                    <label class="section-heading">অফিসিয়াল ইমেইল</label>
                    <input type="email" x-model="branchForm.email" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                </div>
            </div>

            <div>
                <label class="section-heading">রেস্টুরেন্টের সম্পূর্ণ ঠিকানা (চালানে প্রিন্ট হবে)</label>
                <textarea x-model="branchForm.address" rows="2" class="pos-input w-full p-2.5 text-xs rounded-xl resize-none"></textarea>
            </div>

            <!-- Logo Upload & Live Preview -->
            <div class="p-3.5 rounded-2xl border bg-[#FBF8F5]" style="border-color:#E8DDD9;">
                <label class="section-heading mb-2">রেস্টুরেন্ট লোগো (Restaurant Logo)</label>
                <div class="flex items-center gap-3.5">
                    <!-- Logo Preview -->
                    <div class="relative rounded-2xl overflow-hidden bg-white border border-[#E8DDD9] flex items-center justify-center shrink-0 shadow-2xs"
                         style="width: 72px; height: 72px; min-width: 72px; min-height: 72px; background: #2D050B;">
                        <img :src="logoPreview || branchForm.logo || '/images/logo.svg'"
                             alt="Logo Preview"
                             style="width: 72px; height: 72px; object-fit: contain; padding: 4px;"
                             class="block">
                    </div>

                    <!-- Upload & URL inputs -->
                    <div class="flex-1 space-y-2">
                        <div>
                            <label class="text-[10px] font-bold block mb-1" style="color:#5C3840;">কম্পিউটার/মোবাইল থেকে লোগো আপলোড</label>
                            <input type="file" @change="handleLogoUpload($event)" accept="image/*"
                                   class="block w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-2.5 file:rounded-xl file:border-0 file:text-[11px] file:font-semibold file:bg-[#8B1A2C]/10 file:text-[#8B1A2C] hover:file:bg-[#8B1A2C]/20 cursor-pointer">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold block mb-1" style="color:#5C3840;">অথবা লোগোর লিংক (Logo URL)</label>
                            <input type="text" x-model="branchForm.logo" @input="logoPreview = branchForm.logo" placeholder="/images/logo.svg"
                                   class="pos-input w-full px-3 py-1.5 text-xs rounded-xl">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NBR Mushak 6.3 & Tax Settings -->
        <div class="pos-card p-5 space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b" style="border-color:#F0E8E5;">
                <i data-lucide="file-badge-2" class="w-5 h-5" style="color:#B8922A;"></i>
                <h3 class="font-extrabold text-sm" style="color:#1A0A0C;">NBR মূসক-৬.৩ ও কর সেটিংস</h3>
            </div>

            <div>
                <label class="section-heading">NBR বিজনেস আইডেন্টিফিকেশন নম্বর (BIN) *</label>
                <input type="text" x-model="branchForm.bin_number" placeholder="001928374-0102" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold rounded-xl price-maroon">
                <p class="text-[11px] mt-1" style="color:#9B7A7E;">প্রতিটি মূসক ৬.৩ চালানে এই BIN নম্বর বাধ্যতামূলক।</p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="section-heading">মূসক চালান কোড</label>
                    <input type="text" x-model="branchForm.mushak_code" placeholder="6.3" class="pos-input w-full px-3 py-2 text-xs pos-nums rounded-xl">
                </div>
                <div>
                    <label class="section-heading">স্ট্যান্ডার্ড ভ্যাট হার (%) *</label>
                    <input type="number" step="0.01" x-model.number="branchForm.default_vat_rate" placeholder="5.00" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold price-maroon rounded-xl">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="section-heading">কারেন্সি প্রতীক</label>
                    <input type="text" x-model="branchForm.currency_symbol" placeholder="৳" class="pos-input w-full px-3 py-2 text-xs pos-nums font-bold rounded-xl">
                </div>
                <div>
                    <label class="section-heading">কারেন্সি কোড</label>
                    <input type="text" x-model="branchForm.currency" placeholder="BDT" class="pos-input w-full px-3 py-2 text-xs pos-nums uppercase rounded-xl">
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TAB 2: PAYMENT & MFS SETTINGS                       -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'payments'" class="max-w-2xl bg-white rounded-2xl border p-5 space-y-4" style="border-color:#E8DDD9;">
        <div class="flex items-center gap-2 pb-3 border-b" style="border-color:#F0E8E5;">
            <i data-lucide="smartphone" class="w-5 h-5" style="color:#8B1A2C;"></i>
            <h3 class="font-extrabold text-sm" style="color:#1A0A0C;">মোবাইল ব্যাংকিং (bKash, Nagad) মার্চেন্ট নম্বর</h3>
        </div>

        <div class="p-3.5 rounded-2xl border flex items-center gap-3" style="background:#FCE7F3; border-color:#FBCFE8;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm text-white shrink-0" style="background:#e2136e;">
                ৳
            </div>
            <div class="flex-1">
                <label class="text-xs font-bold" style="color:#be185d;">বিকাশ মার্চেন্ট একাউন্ট নম্বর:</label>
                <input type="text" x-model="branchForm.bkash_number" placeholder="01711-223344"
                       class="pos-input w-full mt-1 px-3 py-2 text-xs pos-nums font-bold rounded-xl bg-white">
            </div>
        </div>

        <div class="p-3.5 rounded-2xl border flex items-center gap-3" style="background:#FEF3C7; border-color:#FDE68A;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm text-white shrink-0" style="background:#f7931e;">
                ৳
            </div>
            <div class="flex-1">
                <label class="text-xs font-bold" style="color:#b45309;">নগদ মার্চেন্ট একাউন্ট নম্বর:</label>
                <input type="text" x-model="branchForm.nagad_number" placeholder="01711-223344"
                       class="pos-input w-full mt-1 px-3 py-2 text-xs pos-nums font-bold rounded-xl bg-white">
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- TAB 3: STAFF & CASHIER MANAGEMENT                   -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'staff'" class="space-y-4">
        <div class="flex justify-end">
            <button @click="openUserModal = true; resetUserForm();"
                    class="btn-maroon px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>+ নতুন স্টাফ একাউন্ট</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
            <table class="w-full text-left text-xs">
                <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                    <tr>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">নাম</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">পদবী / রোল</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">দ্রুত লগইন PIN</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">মোবাইল</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">স্ট্যাটাস</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px] text-right" style="color:#9B7A7E;">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr class="data-row border-b" style="border-color:#F0E8E5;">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-xs text-white" style="background:#8B1A2C;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold" style="color:#1A0A0C;">{{ $u->name }}</p>
                                    <p class="text-[10px]" style="color:#9B7A7E;">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $roleColors = [
                                    'admin' => ['অ্যাডমিন', '#8B1A2C', '#FBF1F3'],
                                    'cashier' => ['ক্যাশিয়ার', '#2E7D52', '#D1FAE5'],
                                    'waiter' => ['ওয়েটার', '#B8922A', '#FEF3C7'],
                                    'kitchen' => ['শেফ / কিচেন', '#0284C7', '#E0F2FE'],
                                ];
                                $rc = $roleColors[$u->role] ?? ['স্টাফ', '#5C3840', '#F0E8E5'];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold"
                                  style="background:{{ $rc[2] }}; color:{{ $rc[1] }};">
                                {{ $rc[0] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 pos-nums font-black price-maroon">
                            {{ $u->pin_code ?? '—' }}
                        </td>
                        <td class="px-4 py-3 pos-nums" style="color:#5C3840;">
                            {{ $u->phone ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($u->is_active)
                                <span class="badge-paid px-2 py-0.5 rounded-full text-[10px] font-bold">সক্রিয়</span>
                            @else
                                <span class="badge-unpaid px-2 py-0.5 rounded-full text-[10px] font-bold">নিষ্ক্রিয়</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="editUser({{ json_encode($u) }})"
                                        class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                        style="background:#FBF1F3; color:#8B1A2C; border:1px solid rgba(139,26,44,0.25);">
                                    এডিট
                                </button>
                                <button @click="deleteUser({{ $u->id }}, '{{ $u->name }}')" class="p-1 text-gray-400 hover:text-rose-600">
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
    <!-- TAB 4: SHIFT AUDIT LOGS                             -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="activeTab === 'shifts'" class="bg-white rounded-2xl border overflow-hidden" style="border-color:#E8DDD9;">
        <table class="w-full text-left text-xs">
            <thead style="background:#F8F5F2; border-bottom: 1px solid #E8DDD9;">
                <tr>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">শিফট ID</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">ক্যাশিয়ার</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">শুরুর সময়</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">সমাপ্তি সময়</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">ওপেনিং ক্যাশ</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">নগদ বিক্রয়</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">ড্রয়ার ভ্যারিয়েন্স</th>
                    <th class="px-4 py-3 font-bold uppercase tracking-wider text-[10px]" style="color:#9B7A7E;">স্ট্যাটাস</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentShifts as $s)
                <tr class="data-row border-b" style="border-color:#F0E8E5;">
                    <td class="px-4 py-3 pos-nums font-bold" style="color:#9B7A7E;">#SH-{{ $s->id }}</td>
                    <td class="px-4 py-3 font-bold" style="color:#1A0A0C;">{{ $s->user->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 pos-nums" style="color:#5C3840;">{{ $s->opened_at ? \Carbon\Carbon::parse($s->opened_at)->format('d/m/y h:i A') : '—' }}</td>
                    <td class="px-4 py-3 pos-nums" style="color:#5C3840;">{{ $s->closed_at ? \Carbon\Carbon::parse($s->closed_at)->format('d/m/y h:i A') : 'চলমান...' }}</td>
                    <td class="px-4 py-3 pos-nums font-bold" style="color:#1A0A0C;">৳{{ number_format($s->opening_float, 2) }}</td>
                    <td class="px-4 py-3 pos-nums font-black" style="color:#2E7D52;">৳{{ number_format($s->cash_sales, 2) }}</td>
                    <td class="px-4 py-3 pos-nums font-bold" style="color:{{ $s->cash_difference < 0 ? '#C02020' : '#2E7D52' }};">
                        {{ $s->cash_difference !== null ? '৳'.number_format($s->cash_difference, 2) : '—' }}
                    </td>
                    <td class="px-4 py-3">
                        @if($s->status === 'open')
                            <span class="badge-paid px-2 py-0.5 rounded-full text-[10px] font-bold">চলমান শিফট</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">ক্লোজড</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center" style="color:#9B7A7E;">কোনো শিফট রেকর্ড পাওয়া যায়নি।</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ═══════════════════════════════════════════════════ -->
    <!-- MODAL: ADD / EDIT STAFF USER                        -->
    <!-- ═══════════════════════════════════════════════════ -->
    <div x-show="openUserModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
        <div @click.outside="openUserModal = false"
             class="w-full max-w-md bg-white rounded-3xl overflow-hidden shadow-2xl border"
             style="border-color:#E0D4CF;">
            <div class="p-4 border-b flex items-center justify-between" style="background: linear-gradient(135deg, #5C0F1B, #8B1A2C);">
                <h3 class="text-sm font-bold text-white" x-text="userForm.id ? 'স্টাফ একাউন্ট এডিট করুন' : 'নতুন স্টাফ একাউন্ট তৈরি করুন'"></h3>
                <button @click="openUserModal = false" style="color:rgba(255,255,255,0.7);"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-5 space-y-3">
                <div>
                    <label class="section-heading">স্টাফ পূর্ণ নাম *</label>
                    <input type="text" x-model="userForm.name" placeholder="উদাঃ Rafiqul Islam" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">রোল / পদবী *</label>
                        <select x-model="userForm.role" class="pos-input w-full px-3 py-2 text-xs rounded-xl font-bold">
                            <option value="cashier">ক্যাশিয়ার (Cashier)</option>
                            <option value="waiter">ওয়েটার (Waiter)</option>
                            <option value="kitchen">শেফ (Kitchen Chef)</option>
                            <option value="manager">ম্যানেজার (Manager)</option>
                            <option value="admin">অ্যাডমিন (Admin)</option>
                        </select>
                    </div>
                    <div>
                        <label class="section-heading">৪ ডিজিট লগইন PIN *</label>
                        <input type="text" maxlength="6" x-model="userForm.pin_code" placeholder="1234" class="pos-input w-full px-3 py-2 text-xs pos-nums font-black price-maroon rounded-xl">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="section-heading">মোবাইল নম্বর</label>
                        <input type="text" x-model="userForm.phone" placeholder="01711000000" class="pos-input w-full px-3 py-2 text-xs pos-nums rounded-xl">
                    </div>
                    <div>
                        <label class="section-heading">ইমেইল (ঐচ্ছিক)</label>
                        <input type="email" x-model="userForm.email" placeholder="staff@pos.com" class="pos-input w-full px-3 py-2 text-xs rounded-xl">
                    </div>
                </div>
            </div>

            <div class="p-4 border-t flex justify-between items-center" style="background:#FBF8F5; border-color:#E0D4CF;">
                <button @click="openUserModal = false" class="px-4 py-2 text-xs font-bold" style="color:#9B7A7E;">বাতিল</button>
                <button @click="saveUser()" class="btn-maroon px-6 py-2.5 text-xs font-bold">সংরক্ষণ করুন</button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function settingsManager() {
    return {
        activeTab: 'general',
        isSaving: false,
        openUserModal: false,

        branchForm: @json($branch),
        logoFile: null,
        logoPreview: null,
        userForm: { id: null, name: '', email: '', role: 'cashier', pin_code: '1234', phone: '' },

        init() { this.$nextTick(() => window.initLucideIcons()); },

        handleLogoUpload(e) {
            const file = e.target.files[0];
            if (file) {
                this.logoFile = file;
                const reader = new FileReader();
                reader.onload = (ev) => { this.logoPreview = ev.target.result; };
                reader.readAsDataURL(file);
            }
        },

        async saveBranchSettings() {
            this.isSaving = true;
            try {
                const formData = new FormData();
                for (const key in this.branchForm) {
                    if (this.branchForm[key] !== null && this.branchForm[key] !== undefined) {
                        formData.append(key, this.branchForm[key]);
                    }
                }
                if (this.logoFile) {
                    formData.append('logo_file', this.logoFile);
                }

                const res = await fetch('{{ route('settings.branch.update') }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else { alert(data.message || 'সেটিংস আপডেট করতে সমস্যা হয়েছে'); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
            finally { this.isSaving = false; }
        },

        resetUserForm() {
            this.userForm = { id: null, name: '', email: '', role: 'cashier', pin_code: '1234', phone: '' };
        },

        editUser(u) {
            this.userForm = { id: u.id, name: u.name, email: u.email, role: u.role, pin_code: u.pin_code || '1234', phone: u.phone || '' };
            this.openUserModal = true;
            this.$nextTick(() => window.initLucideIcons());
        },

        async saveUser() {
            if (!this.userForm.name || !this.userForm.pin_code) {
                alert('অনুগ্রহ করে নাম এবং ৪ ডিজিট লগইন PIN দিন!'); return;
            }
            try {
                const res = await fetch('{{ route('settings.user.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(this.userForm)
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else { alert(data.message || 'সংরক্ষণ ব্যর্থ হয়েছে'); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        },

        async deleteUser(id, name) {
            if (!confirm(`আপনি কি "${name}" এর স্টাফ একাউন্ট মুছে ফেলতে চান?`)) return;
            try {
                const res = await fetch(`/settings/user/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if (data.success) { alert(data.message); location.reload(); }
                else { alert(data.message); }
            } catch(e) { alert('ত্রুটি: ' + e.message); }
        }
    };
}
</script>
@endpush
@endsection
