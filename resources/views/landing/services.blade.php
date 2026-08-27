@extends('landing.layout')

@section('title', 'Our Services — Lezzatos Luxury Dining')

@section('content')

    <!-- ════ PAGE HEADER BANNER ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20" data-aos="fade-down">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                Our Service
            </h1>
        </div>
    </section>

    <!-- ════ 1. OUR SPECIALIST CUISINE (4 VERTICAL CARDS) ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            
            <div class="text-center space-y-1 mb-16" data-aos="fade-up">
                <p class="font-script text-3xl text-[#C5A880]">Discover</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Our Specialist Cuisine
                </h2>
            </div>

            <!-- 4 Vertical Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1: Noodle -->
                <div class="bg-[#141414] p-7 chamfer-top-right border border-[#C5A880]/20 space-y-4 group hover:border-[#C5A880] transition-all luxury-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center border border-[#C5A880]/30 group-hover:scale-110 transition-transform" style="background: rgba(197,168,128,0.08);">
                        <i data-lucide="soup" class="w-6 h-6 text-[#C5A880]"></i>
                    </div>
                    <h3 class="font-serif text-base font-bold text-white">Noodle</h3>
                    <p class="text-xs text-[#8C7D73] leading-relaxed">Handmade artisan noodles served with slow-cooked aromatic broth.</p>
                    <a href="{{ route('our-menu') }}" class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-[#C5A880] hover:translate-x-1 transition-transform">
                        <span>View More</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <!-- Card 2: Chicken -->
                <div class="bg-[#141414] p-7 chamfer-top-right border border-[#C5A880]/20 space-y-4 group hover:border-[#C5A880] transition-all luxury-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center border border-[#C5A880]/30 group-hover:scale-110 transition-transform" style="background: rgba(197,168,128,0.08);">
                        <i data-lucide="utensils" class="w-6 h-6 text-[#C5A880]"></i>
                    </div>
                    <h3 class="font-serif text-base font-bold text-white">Chicken</h3>
                    <p class="text-xs text-[#8C7D73] leading-relaxed">Flame-grilled roasts & imperial dum cooked tender delicacies.</p>
                    <a href="{{ route('our-menu') }}" class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-[#C5A880] hover:translate-x-1 transition-transform">
                        <span>View More</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <!-- Card 3: Cake -->
                <div class="bg-[#D1A568] p-7 chamfer-top-right text-black space-y-4 shadow-2xl luxury-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-black/10">
                        <i data-lucide="cake" class="w-6 h-6 text-black"></i>
                    </div>
                    <h3 class="font-serif text-base font-bold text-black">Cake</h3>
                    <p class="text-xs text-black/80 leading-relaxed">Artisan pastries, rich cheesecakes and delightful dessert delicacies.</p>
                    <a href="{{ route('our-menu') }}" class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-black font-bold hover:translate-x-1 transition-transform">
                        <span>View More</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <!-- Card 4: Coffee -->
                <div class="bg-[#141414] p-7 chamfer-top-right border border-[#C5A880]/20 space-y-4 group hover:border-[#C5A880] transition-all luxury-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center border border-[#C5A880]/30 group-hover:scale-110 transition-transform" style="background: rgba(197,168,128,0.08);">
                        <i data-lucide="coffee" class="w-6 h-6 text-[#C5A880]"></i>
                    </div>
                    <h3 class="font-serif text-base font-bold text-white">Coffee</h3>
                    <p class="text-xs text-[#8C7D73] leading-relaxed">Single-origin espresso beans brewed by master baristas.</p>
                    <a href="{{ route('our-menu') }}" class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-[#C5A880] hover:translate-x-1 transition-transform">
                        <span>View More</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

            </div>

        </div>
    </section>

    <!-- ════ 2. STATS COUNTER BAR ════ -->
    <section class="py-14 bg-[#111111] border-t border-b border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center" data-aos="fade-up">
                <div class="space-y-1">
                    <p class="font-serif text-3xl sm:text-4xl font-bold text-white">12</p>
                    <p class="text-[11px] uppercase tracking-widest text-[#8C7D73]">Restaurants</p>
                </div>
                <div class="space-y-1">
                    <p class="font-serif text-3xl sm:text-4xl font-bold text-white">8</p>
                    <p class="text-[11px] uppercase tracking-widest text-[#8C7D73]">Years Experience</p>
                </div>
                <div class="space-y-1">
                    <p class="font-serif text-3xl sm:text-4xl font-bold text-white">50+</p>
                    <p class="text-[11px] uppercase tracking-widest text-[#8C7D73]">Award Winner</p>
                </div>
                <div class="space-y-1">
                    <p class="font-serif text-3xl sm:text-4xl font-bold text-white">200+</p>
                    <p class="text-[11px] uppercase tracking-widest text-[#8C7D73]">Customers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════ 3. OUR SERVICE FACILITIES ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-6 space-y-6" data-aos="fade-right">
                    <p class="font-script text-3xl text-[#C5A880]">Our Service Facilities</p>
                    
                    <div class="space-y-5 pt-2">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center border border-[#C5A880]/30 shrink-0" style="background: rgba(197,168,128,0.08);">
                                <i data-lucide="soup" class="w-5 h-5 text-[#C5A880]"></i>
                            </div>
                            <div>
                                <h3 class="font-serif text-base font-bold text-white mb-1">New Weekly Menu</h3>
                                <p class="text-xs text-[#8C7D73]">Exciting seasonal chef creations introduced every single week.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center border border-[#C5A880]/30 shrink-0" style="background: rgba(197,168,128,0.08);">
                                <i data-lucide="chef-hat" class="w-5 h-5 text-[#C5A880]"></i>
                            </div>
                            <div>
                                <h3 class="font-serif text-base font-bold text-white mb-1">Professional Chef</h3>
                                <p class="text-xs text-[#8C7D73]">Culinary masters with Michelin-level kitchen precision and hygiene.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center border border-[#C5A880]/30 shrink-0" style="background: rgba(197,168,128,0.08);">
                                <i data-lucide="truck" class="w-5 h-5 text-[#C5A880]"></i>
                            </div>
                            <div>
                                <h3 class="font-serif text-base font-bold text-white mb-1">Free Shipping Delivery</h3>
                                <p class="text-xs text-[#8C7D73]">Piping hot packaging delivered swiftly across the metropolitan area.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center border border-[#C5A880]/30 shrink-0" style="background: rgba(197,168,128,0.08);">
                                <i data-lucide="armchair" class="w-5 h-5 text-[#C5A880]"></i>
                            </div>
                            <div>
                                <h3 class="font-serif text-base font-bold text-white mb-1">Comfortable Dining Room</h3>
                                <p class="text-xs text-[#8C7D73]">Ambient lighting, private acoustic alcoves, and luxurious comfort.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6" data-aos="fade-left">
                    <div class="rounded-3xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=800&q=80" 
                             alt="Food Service" class="w-full h-96 object-cover">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 4. THE MOST COMFORTABLE RESTAURANT (TOUR & GALLERY) ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 text-center">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 space-y-8">
            <div class="space-y-1" data-aos="fade-up">
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    The Most Comfortable Restaurant
                </h2>
                <p class="text-xs text-[#8C7D73] max-w-lg mx-auto">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.
                </p>
            </div>

            <!-- Top Large Video Tour -->
            <div class="relative h-96 rounded-3xl overflow-hidden border border-[#C5A880]/20 shadow-2xl flex items-center justify-center" data-aos="zoom-in">
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1600&q=80" 
                     alt="Dining Room" class="absolute inset-0 w-full h-full object-cover brightness-50">
                <button @click="videoModalOpen = true" 
                        class="relative z-10 w-20 h-20 rounded-full border-2 border-[#C5A880] flex items-center justify-center text-[#C5A880] hover:scale-110 hover:bg-[#C5A880] hover:text-black transition-all shadow-2xl cursor-pointer">
                    <i data-lucide="play" class="w-8 h-8 fill-current ml-1"></i>
                </button>
            </div>

            <!-- Bottom 3 Photos -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" data-aos="fade-up">
                <div class="rounded-2xl overflow-hidden shadow-xl border border-[#C5A880]/20 luxury-img-zoom">
                    <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=600&q=80" alt="Dining" class="w-full h-48 object-cover">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xl border border-[#C5A880]/20 luxury-img-zoom">
                    <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80" alt="Lounge" class="w-full h-48 object-cover">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xl border border-[#C5A880]/20 luxury-img-zoom">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80" alt="Tables" class="w-full h-48 object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- ════ 5. RESERVATION BANNER ════ -->
    <section class="py-20 bg-[#0B0B0B] border-t border-[#C5A880]/15" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-[#111111] p-8 sm:p-12 rounded-3xl border border-[#C5A880]/20">
                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&w=800&q=80" alt="Servers" class="w-full h-72 object-cover">
                    </div>
                </div>
                <div class="lg:col-span-6 space-y-4">
                    <p class="font-script text-3xl text-[#C5A880]">Reservation</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">We will Serve You Better</h2>
                    <p class="text-xs sm:text-sm text-[#8C7D73]">Reserve your luxury table ahead of time and experience first-class dining with dedicated personal hosts.</p>
                    <div class="pt-2">
                        <a href="{{ route('reservation') }}" class="inline-block px-8 py-3 rounded bg-[#D1A568] hover:bg-[#C5A880] text-black font-bold text-xs uppercase tracking-wider transition-all gold-glow-btn">
                            BOOK A TABLE
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
