@extends('landing.layout')

@section('title', 'News & Stories — Lezzatos Luxury Dining')

@section('content')

    <!-- ════ PAGE HEADER BANNER ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20" data-aos="fade-down">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                News
            </h1>
        </div>
    </section>

    <!-- ════ 1. FEATURED TOP NEWS SECTION ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Left: Big Featured Story -->
                <div class="lg:col-span-7 bg-[#141414] rounded-3xl border border-[#C5A880]/20 overflow-hidden shadow-2xl group luxury-card" data-aos="fade-right">
                    <div class="relative h-80 overflow-hidden luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=1000&q=80" 
                             alt="Featured News" class="w-full h-full object-cover">
                    </div>
                    <div class="p-8 space-y-3">
                        <span class="inline-block px-3 py-1 rounded bg-[#D1A568] text-black font-bold text-[10px] uppercase tracking-wider">Featured</span>
                        <h2 class="font-serif text-2xl font-bold text-white leading-tight group-hover:text-[#C5A880] transition-colors">
                            Our CFO, Andrew Jonshan Announces Expansion Plans
                        </h2>
                        <p class="text-xs text-[#8C7D73] leading-relaxed">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip.
                        </p>
                    </div>
                </div>

                <!-- Right: 3 Stacked Mini-News Cards -->
                <div class="lg:col-span-5 space-y-4" data-aos="fade-left">
                    
                    <div class="bg-[#141414] p-4 rounded-2xl border border-[#C5A880]/20 flex gap-4 items-center group hover:border-[#C5A880] transition-all luxury-card">
                        <div class="luxury-img-zoom w-24 h-24 rounded-xl shrink-0">
                            <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&w=300&q=80" alt="Chefs" class="w-full h-full object-cover">
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] text-[#C5A880] font-bold uppercase tracking-wider">7 MINS AGO</span>
                            <h3 class="font-serif text-xs font-bold text-white group-hover:text-[#C5A880] transition-colors line-clamp-2">Our Chef Win National Cooking Award</h3>
                            <p class="text-[11px] text-[#8C7D73] line-clamp-1">Celebrating our culinary triumphs in culinary artistry.</p>
                        </div>
                    </div>

                    <div class="bg-[#141414] p-4 rounded-2xl border border-[#C5A880]/20 flex gap-4 items-center group hover:border-[#C5A880] transition-all luxury-card">
                        <div class="luxury-img-zoom w-24 h-24 rounded-xl shrink-0">
                            <img src="https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=300&q=80" alt="Rice" class="w-full h-full object-cover">
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] text-[#C5A880] font-bold uppercase tracking-wider">2 HOURS AGO</span>
                            <h3 class="font-serif text-xs font-bold text-white group-hover:text-[#C5A880] transition-colors line-clamp-2">New Menu: Kebab Rice in the House</h3>
                            <p class="text-[11px] text-[#8C7D73] line-clamp-1">Try our royal saffron-infused spiced basmati bowl.</p>
                        </div>
                    </div>

                    <div class="bg-[#141414] p-4 rounded-2xl border border-[#C5A880]/20 flex gap-4 items-center group hover:border-[#C5A880] transition-all luxury-card">
                        <div class="luxury-img-zoom w-24 h-24 rounded-xl shrink-0">
                            <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=300&q=80" alt="Dining" class="w-full h-full object-cover">
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] text-[#C5A880] font-bold uppercase tracking-wider">1 DAY AGO</span>
                            <h3 class="font-serif text-xs font-bold text-white group-hover:text-[#C5A880] transition-colors line-clamp-2">Comfortable Place for Candle Light Dining</h3>
                            <p class="text-[11px] text-[#8C7D73] line-clamp-1">Romantic atmosphere for your private moments.</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- ════ 2. FOOD CRITICS VIDEO BANNER ════ -->
    <section class="py-20 bg-[#0E0E0E] border-t border-[#C5A880]/15 text-center" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 space-y-8">
            <div class="space-y-1">
                <p class="font-script text-3xl text-[#C5A880]">Discover</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Some Critickers are Tasting Our Food
                </h2>
            </div>

            <div class="relative h-96 rounded-3xl overflow-hidden border border-[#C5A880]/20 shadow-2xl flex items-center justify-center">
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1600&q=80" 
                     alt="Critics" class="absolute inset-0 w-full h-full object-cover brightness-50">
                <button @click="videoModalOpen = true" 
                        class="relative z-10 w-20 h-20 rounded-full border-2 border-[#C5A880] flex items-center justify-center text-[#C5A880] hover:scale-110 hover:bg-[#C5A880] hover:text-black transition-all shadow-2xl cursor-pointer">
                    <i data-lucide="play" class="w-8 h-8 fill-current ml-1"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- ════ 3. RECIPES / TIPS & TRICKS GRID ════ -->
    <section class="py-24 bg-[#0B0B0B] border-t border-[#C5A880]/15">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="text-center space-y-1 mb-16" data-aos="fade-up">
                <p class="font-script text-3xl text-[#C5A880]">Recipes</p>
                <h2 class="font-serif text-3xl font-bold text-white tracking-tight">Tips & Tricks</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Recipe 1 -->
                <div class="bg-[#141414] rounded-3xl border border-[#C5A880]/20 overflow-hidden group luxury-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-6 pb-3 flex items-center justify-between border-b border-[#C5A880]/10">
                        <h3 class="font-serif text-base font-bold text-white">Korean Spicy Soup</h3>
                        <span class="text-[10px] px-2.5 py-0.5 rounded bg-[#C5A880]/20 text-[#C5A880] uppercase font-bold">Hot Food</span>
                    </div>
                    <div class="h-60 overflow-hidden luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=800&q=80" alt="Soup" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <p class="text-xs text-[#8C7D73] leading-relaxed mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <a href="{{ route('our-menu') }}" class="text-xs font-bold text-[#C5A880] hover:underline">Read More →</a>
                    </div>
                </div>

                <!-- Recipe 2 -->
                <div class="bg-[#141414] rounded-3xl border border-[#C5A880]/20 overflow-hidden group luxury-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-6 pb-3 flex items-center justify-between border-b border-[#C5A880]/10">
                        <h3 class="font-serif text-base font-bold text-white">American Fruit Pancake</h3>
                        <span class="text-[10px] px-2.5 py-0.5 rounded bg-[#C5A880]/20 text-[#C5A880] uppercase font-bold">Dessert</span>
                    </div>
                    <div class="h-60 overflow-hidden luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=800&q=80" alt="Pancake" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <p class="text-xs text-[#8C7D73] leading-relaxed mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <a href="{{ route('our-menu') }}" class="text-xs font-bold text-[#C5A880] hover:underline">Read More →</a>
                    </div>
                </div>

                <!-- Recipe 3 -->
                <div class="bg-[#141414] rounded-3xl border border-[#C5A880]/20 overflow-hidden group luxury-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-6 pb-3 flex items-center justify-between border-b border-[#C5A880]/10">
                        <h3 class="font-serif text-base font-bold text-white">Healthy Salad</h3>
                        <span class="text-[10px] px-2.5 py-0.5 rounded bg-[#C5A880]/20 text-[#C5A880] uppercase font-bold">Appetizer</span>
                    </div>
                    <div class="h-60 overflow-hidden luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80" alt="Salad" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <p class="text-xs text-[#8C7D73] leading-relaxed mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <a href="{{ route('our-menu') }}" class="text-xs font-bold text-[#C5A880] hover:underline">Read More →</a>
                    </div>
                </div>

                <!-- Recipe 4 -->
                <div class="bg-[#141414] rounded-3xl border border-[#C5A880]/20 overflow-hidden group luxury-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-6 pb-3 flex items-center justify-between border-b border-[#C5A880]/10">
                        <h3 class="font-serif text-base font-bold text-white">Chicken Curry Special</h3>
                        <span class="text-[10px] px-2.5 py-0.5 rounded bg-[#C5A880]/20 text-[#C5A880] uppercase font-bold">Main Course</span>
                    </div>
                    <div class="h-60 overflow-hidden luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80" alt="Chicken" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <p class="text-xs text-[#8C7D73] leading-relaxed mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <a href="{{ route('our-menu') }}" class="text-xs font-bold text-[#C5A880] hover:underline">Read More →</a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 4. UPCOMING EVENTS ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-6" data-aos="fade-right">
                    <div class="rounded-3xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=800&q=80" 
                             alt="Anniversary Dining" class="w-full h-80 sm:h-96 object-cover">
                    </div>
                </div>

                <div class="lg:col-span-6" data-aos="fade-left">
                    <div class="bg-white text-[#1A1A1A] p-8 sm:p-12 chamfer-top-right shadow-2xl space-y-4 luxury-card">
                        <p class="font-script text-2xl text-[#C5A880]">Upcoming Events</p>
                        <h2 class="font-serif text-3xl font-bold text-[#111]">Lezzatos 3rd Anniversary</h2>
                        <p class="text-xs sm:text-sm text-[#665D56] leading-relaxed">
                            Join us for an exclusive 5-course gala dinner celebration featuring master chef guest appearances, live acoustic jazz, and complimentary dessert pairings.
                        </p>
                        <a href="{{ route('reservation') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#C5A880] hover:text-[#111] transition-colors border-b border-[#C5A880] pb-0.5">
                            <span>Book Now</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 5. SOCIAL MEDIA ICONS BAR ════ -->
    <section class="py-8 bg-[#111111] border-t border-b border-[#C5A880]/15" data-aos="fade-up">
        <div class="max-w-4xl mx-auto px-6 flex items-center justify-center gap-8 text-[#C5A880]">
            <a href="#" class="hover:text-white transition-colors hover:scale-125 transform"><i data-lucide="facebook" class="w-6 h-6"></i></a>
            <a href="#" class="hover:text-white transition-colors hover:scale-125 transform"><i data-lucide="twitter" class="w-6 h-6"></i></a>
            <a href="#" class="hover:text-white transition-colors hover:scale-125 transform"><i data-lucide="instagram" class="w-6 h-6"></i></a>
            <a href="#" class="hover:text-white transition-colors hover:scale-125 transform"><i data-lucide="youtube" class="w-6 h-6"></i></a>
            <a href="#" class="hover:text-white transition-colors hover:scale-125 transform"><i data-lucide="message-circle" class="w-6 h-6"></i></a>
        </div>
    </section>

@endsection
