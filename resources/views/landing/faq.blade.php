@extends('landing.layout')

@section('title', 'FAQ — Frequently Asked Questions | Lezzatos')

@section('content')

    <!-- ════ PAGE HEADER BANNER ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                FAQ
            </h1>
        </div>
    </section>

    <!-- ════ 1. FAQ ACCORDION / TABS SECTION (MATCHING SCREENSHOT) ════ -->
    <section class="py-24 bg-[#0B0B0B] relative" x-data="{ activeTab: 1 }">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                
                <!-- Left: Question Tabs List -->
                <div class="lg:col-span-4 space-y-3">
                    
                    <button @click="activeTab = 1" 
                            class="w-full text-left p-4 rounded-xl text-xs font-bold transition-all chamfer-top-right flex items-center justify-between"
                            :class="activeTab === 1 ? 'bg-[#D1A568] text-black shadow-lg' : 'bg-[#141414] text-[#A8988D] border border-[#C5A880]/20 hover:border-[#C5A880]'">
                        <span>What is Lezzatos ?</span>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>

                    <button @click="activeTab = 2" 
                            class="w-full text-left p-4 rounded-xl text-xs font-bold transition-all chamfer-top-right flex items-center justify-between"
                            :class="activeTab === 2 ? 'bg-[#D1A568] text-black shadow-lg' : 'bg-[#141414] text-[#A8988D] border border-[#C5A880]/20 hover:border-[#C5A880]'">
                        <span>How to make a food reservation?</span>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>

                    <button @click="activeTab = 3" 
                            class="w-full text-left p-4 rounded-xl text-xs font-bold transition-all chamfer-top-right flex items-center justify-between"
                            :class="activeTab === 3 ? 'bg-[#D1A568] text-black shadow-lg' : 'bg-[#141414] text-[#A8988D] border border-[#C5A880]/20 hover:border-[#C5A880]'">
                        <span>Where is the restaurant address located?</span>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>

                    <button @click="activeTab = 4" 
                            class="w-full text-left p-4 rounded-xl text-xs font-bold transition-all chamfer-top-right flex items-center justify-between"
                            :class="activeTab === 4 ? 'bg-[#D1A568] text-black shadow-lg' : 'bg-[#141414] text-[#A8988D] border border-[#C5A880]/20 hover:border-[#C5A880]'">
                        <span>How to cancel an order?</span>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>

                    <button @click="activeTab = 5" 
                            class="w-full text-left p-4 rounded-xl text-xs font-bold transition-all chamfer-top-right flex items-center justify-between"
                            :class="activeTab === 5 ? 'bg-[#D1A568] text-black shadow-lg' : 'bg-[#141414] text-[#A8988D] border border-[#C5A880]/20 hover:border-[#C5A880]'">
                        <span>Where to contact if having problems?</span>
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>

                </div>

                <!-- Right: Answer Panel -->
                <div class="lg:col-span-8 bg-[#121212] p-8 sm:p-12 rounded-3xl border border-[#C5A880]/20 shadow-2xl space-y-6">
                    
                    <div x-show="activeTab === 1" x-cloak>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-white mb-4">What is Lezzatos ?</h2>
                        <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed mb-4">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                        </p>
                        <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed">
                            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.
                        </p>
                    </div>

                    <div x-show="activeTab === 2" x-cloak>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-white mb-4">How to make a food reservation?</h2>
                        <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed mb-4">
                            You can easily reserve your table through our online reservation system. Simply select your preferred date, time slot, guest count, and table area. You will receive an instant confirmation on screen.
                        </p>
                        <a href="{{ route('reservation') }}" class="inline-block mt-2 px-6 py-2.5 rounded bg-[#D1A568] text-black font-bold text-xs uppercase tracking-wider">Book Now</a>
                    </div>

                    <div x-show="activeTab === 3" x-cloak>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-white mb-4">Where is the restaurant address located?</h2>
                        <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed mb-4">
                            We are located in the heart of the city at Braga Street 28, Bandung, West Java. We offer complimentary valet parking for all our dining guests.
                        </p>
                    </div>

                    <div x-show="activeTab === 4" x-cloak>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-white mb-4">How to cancel an order?</h2>
                        <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed mb-4">
                            To cancel or reschedule a table reservation, please call our direct hotline at +62 898245124 at least 2 hours before your scheduled dining time.
                        </p>
                    </div>

                    <div x-show="activeTab === 5" x-cloak>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-white mb-4">Where to contact if having problems?</h2>
                        <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed mb-4">
                            Our customer support team is available daily from 08:00 to 23:00 via email at lezzatos@restaurant.com or by submitting the contact form below.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- ════ 2. GET IN TOUCH / HAVE A SPECIFIC QUESTION? ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: Text -->
                <div class="lg:col-span-5 space-y-4">
                    <p class="font-script text-3xl text-[#C5A880]">Get in Touch</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                        Have a Specific Question for Us ?
                    </h2>
                    <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.
                    </p>
                </div>

                <!-- Right: Sand Gold Form -->
                <div class="lg:col-span-7">
                    <div class="bg-[#D1A568] p-8 sm:p-10 chamfer-top-right shadow-2xl text-black">
                        <div class="text-center mb-6">
                            <h3 class="font-serif text-2xl sm:text-3xl font-bold text-white">Contact Form</h3>
                        </div>

                        <form @submit.prevent="alert('Thank you! Your question has been submitted.');" class="space-y-3.5">
                            <div><input type="text" required placeholder="Name" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none"></div>
                            <div><input type="email" required placeholder="Email" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none"></div>
                            <div><textarea rows="3" required placeholder="Message..." class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none resize-none"></textarea></div>
                            <div class="pt-2">
                                <button type="submit" class="w-full py-3.5 rounded bg-white hover:bg-gray-100 text-[#111] font-bold text-xs uppercase tracking-widest transition-all shadow-xl active:scale-98">
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
                
                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=800&q=80" 
                             alt="Crew Staff" class="w-full h-80 object-cover">
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="bg-white text-[#1A1A1A] p-8 sm:p-12 chamfer-top-right shadow-2xl space-y-4">
                        <p class="font-script text-2xl text-[#C5A880]">Discover</p>
                        <h2 class="font-serif text-3xl font-bold text-[#111]">Our Crew Ready to Help You</h2>
                        <p class="text-xs sm:text-sm text-[#665D56] leading-relaxed">
                            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae.
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
                
                <div class="lg:col-span-6 space-y-6">
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

                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80" 
                             alt="Dining Hall" class="w-full h-80 object-cover">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 5. OUR LATEST POSTS ════ -->
    <section class="py-24 bg-[#0B0B0B] border-t border-[#C5A880]/15">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="text-center space-y-1 mb-16">
                <p class="font-script text-3xl text-[#C5A880]">Post</p>
                <h2 class="font-serif text-3xl font-bold text-white tracking-tight">Our Latest Post</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($blogs as $blog)
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group hover:border-[#C5A880] transition-all">
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-5 chamfer-top-right bg-[#181818] -mt-4 relative z-10 m-2.5 rounded-xl border border-[#C5A880]/15">
                        <h3 class="font-serif text-sm font-bold text-white mb-2 line-clamp-1">{{ $blog['title'] }}</h3>
                        <p class="text-[11px] text-[#8C7D73] line-clamp-2 mb-4 leading-relaxed">{{ $blog['excerpt'] }}</p>
                        <div class="flex items-center gap-2 text-[10px] text-[#C5A880]">
                            <div class="w-5 h-5 rounded-full bg-[#C5A880]/20 flex items-center justify-center font-bold">{{ substr($blog['author'], 0, 1) }}</div>
                            <span>By {{ $blog['author'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
