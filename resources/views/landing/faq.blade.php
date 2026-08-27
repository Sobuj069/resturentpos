@extends('landing.layout')

@section('title', 'FAQ — Frequently Asked Questions | ' . ($branch->restaurant_name ?? 'Lezzatos'))

@section('content')

    <!-- ════ PAGE HEADER BANNER ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20" data-aos="fade-down">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                FAQ
            </h1>
        </div>
    </section>

    <!-- ════ 1. FAQ ACCORDION / TABS SECTION ════ -->
    <section class="py-24 bg-[#0B0B0B] relative" x-data="{ activeTab: 0 }">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                <!-- Left: Question Tabs List -->
                <div class="lg:col-span-4 space-y-3" data-aos="fade-right">
                    @foreach($faqs as $idx => $faq)
                    <button @click="activeTab = {{ $idx }}" 
                            class="w-full text-left p-4 rounded-xl text-xs font-bold transition-all chamfer-top-right flex items-center justify-between cursor-pointer"
                            :class="activeTab === {{ $idx }} ? 'bg-[#D1A568] text-black shadow-lg scale-102' : 'bg-[#141414] text-[#A8988D] border border-[#C5A880]/20 hover:border-[#C5A880]'">
                        <span>{{ $faq['question'] }}</span>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                    @endforeach
                </div>

                <!-- Right: Answer Panel -->
                <div class="lg:col-span-8 bg-[#121212] p-8 sm:p-12 rounded-3xl border border-[#C5A880]/20 shadow-2xl space-y-6 luxury-card" data-aos="fade-left">
                    @foreach($faqs as $idx => $faq)
                    <div x-show="activeTab === {{ $idx }}" x-transition>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-white mb-4">{{ $faq['question'] }}</h2>
                        <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed mb-4">
                            {{ $faq['answer'] }}
                        </p>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 2. GET IN TOUCH / HAVE A SPECIFIC QUESTION? ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: Text -->
                <div class="lg:col-span-5 space-y-4" data-aos="fade-right">
                    <p class="font-script text-3xl text-[#C5A880]">Get in Touch</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                        Have a Specific Question for Us ?
                    </h2>
                    <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed">
                        Need assistance with catering, private parties, dietary preferences, or custom event hosting? Reach out directly to our guest relations team.
                    </p>
                </div>

                <!-- Right: Sand Gold Form -->
                <div class="lg:col-span-7" data-aos="fade-left">
                    <div class="bg-[#D1A568] p-8 sm:p-10 chamfer-top-right shadow-2xl text-black luxury-card">
                        <div class="text-center mb-6">
                            <h3 class="font-serif text-2xl sm:text-3xl font-bold text-white">Contact Form</h3>
                        </div>

                        <form @submit.prevent="alert('Thank you! Your question has been submitted.');" class="space-y-3.5">
                            <div><input type="text" required placeholder="Name" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm"></div>
                            <div><input type="email" required placeholder="Email" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm"></div>
                            <div><textarea rows="3" required placeholder="Message..." class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none resize-none shadow-sm"></textarea></div>
                            <div class="pt-2">
                                <button type="submit" class="w-full py-3.5 rounded bg-white hover:bg-gray-100 text-[#111] font-bold text-xs uppercase tracking-widest transition-all shadow-xl active:scale-98 gold-glow-btn cursor-pointer">
                                    SUBMIT
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 3. DISCOVER / OUR CREW READY TO HELP YOU ════ -->
    <section class="py-24 bg-[#0B0B0B] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-6" data-aos="fade-right">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=800&q=80" 
                             alt="Crew Staff" class="w-full h-80 object-cover">
                    </div>
                </div>

                <div class="lg:col-span-6" data-aos="fade-left">
                    <div class="bg-white text-[#1A1A1A] p-8 sm:p-12 chamfer-top-right shadow-2xl space-y-4 luxury-card">
                        <p class="font-script text-2xl text-[#C5A880]">Discover</p>
                        <h2 class="font-serif text-3xl font-bold text-[#111]">Our Crew Ready to Help You</h2>
                        <p class="text-xs sm:text-sm text-[#665D56] leading-relaxed">
                            Our team is at your disposal 7 days a week to ensure your fine dining reservation is smooth and delightful.
                        </p>
                        <a href="{{ route('contact-us') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#C5A880] hover:text-[#111] transition-colors border-b border-[#C5A880] pb-0.5">
                            <span>Contact Us</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 4. OUR BOOKING PROCESS ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-6 space-y-6" data-aos="fade-right">
                    <p class="font-script text-3xl text-[#C5A880]">Our Booking Process</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] shrink-0 font-bold text-xs">1</div>
                            <div>
                                <h4 class="font-serif text-sm font-bold text-white mb-0.5">Choose</h4>
                                <p class="text-xs text-[#8C7D73]">Pick preferred date & table area</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] shrink-0 font-bold text-xs">2</div>
                            <div>
                                <h4 class="font-serif text-sm font-bold text-white mb-0.5">Detail</h4>
                                <p class="text-xs text-[#8C7D73]">Enter your contact information</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] shrink-0 font-bold text-xs">3</div>
                            <div>
                                <h4 class="font-serif text-sm font-bold text-white mb-0.5">Payment</h4>
                                <p class="text-xs text-[#8C7D73]">Free table reservation booking</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] shrink-0 font-bold text-xs">4</div>
                            <div>
                                <h4 class="font-serif text-sm font-bold text-white mb-0.5">Confirm</h4>
                                <p class="text-xs text-[#8C7D73]">Instant booking confirmation</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6" data-aos="fade-left">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80" 
                             alt="Dining Hall" class="w-full h-80 object-cover">
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
