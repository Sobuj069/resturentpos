@extends('landing.layout')

@section('title', 'About Us — Lezzatos Luxury Dining')

@section('content')

    <!-- ════ PAGE HEADER BANNER ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                About Us
            </h1>
        </div>
    </section>

    <!-- ════ 1. HIGH SERVICE FOR ALL CUSTOMER ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-12">
                <div class="lg:col-span-6 space-y-1">
                    <p class="font-script text-3xl text-[#C5A880]">About Us</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                        High Service for All Customer
                    </h2>
                </div>
                <div class="lg:col-span-6">
                    <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                    </p>
                </div>
            </div>

            <!-- Chefs Team Banner -->
            <div class="rounded-3xl overflow-hidden border border-[#C5A880]/30 shadow-2xl mb-14">
                <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&w=1600&q=80" 
                     alt="Professional Chefs Team" class="w-full h-80 sm:h-[450px] object-cover">
            </div>

            <!-- Stats Counter Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center border-t border-b border-[#C5A880]/15 py-10 bg-[#0E0E0E] rounded-2xl">
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

    <!-- ════ 2. OUR FOUNDER ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: Founder Image -->
                <div class="lg:col-span-6 relative">
                    <div class="rounded-3xl overflow-hidden border border-[#C5A880]/30 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=800&q=80" 
                             alt="Founder" class="w-full h-[450px] object-cover">
                    </div>
                </div>

                <!-- Right: White Chamfered Founder Quote Card -->
                <div class="lg:col-span-6 relative">
                    <div class="text-[#C5A880] text-6xl font-serif mb-2">“</div>
                    <div class="bg-white text-[#1A1A1A] p-8 sm:p-12 chamfer-top-right shadow-2xl relative">
                        <p class="font-script text-2xl text-[#C5A880] mb-1">Quotes</p>
                        <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#111] mb-4">Our Founder</h2>
                        <p class="text-xs sm:text-sm text-[#665D56] leading-relaxed mb-6">
                            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                        </p>
                        <p class="font-script text-2xl text-[#C5A880]">Antonio Lezzato</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 3. OUR VISION & MISSION ════ -->
    <section class="py-24 bg-[#0B0B0B] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: 3 Feature Items -->
                <div class="lg:col-span-6 space-y-6">
                    <p class="font-script text-3xl text-[#C5A880]">Our Vision & Mission</p>
                    
                    <div class="space-y-6 pt-2">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center border border-[#C5A880]/30 shrink-0" style="background: rgba(197, 168, 128, 0.08);">
                                <i data-lucide="utensils" class="w-5 h-5 text-[#C5A880]"></i>
                            </div>
                            <div>
                                <h3 class="font-serif text-base font-bold text-white mb-1">Delicious Cuisine</h3>
                                <p class="text-xs text-[#8C7D73]">Curated recipes infused with fragrant spices and organic ingredients.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center border border-[#C5A880]/30 shrink-0" style="background: rgba(197, 168, 128, 0.08);">
                                <i data-lucide="zap" class="w-5 h-5 text-[#C5A880]"></i>
                            </div>
                            <div>
                                <h3 class="font-serif text-base font-bold text-white mb-1">Fast Delivery</h3>
                                <p class="text-xs text-[#8C7D73]">Swift preparation and piping-hot doorstep delivery guaranteed.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center border border-[#C5A880]/30 shrink-0" style="background: rgba(197, 168, 128, 0.08);">
                                <i data-lucide="chef-hat" class="w-5 h-5 text-[#C5A880]"></i>
                            </div>
                            <div>
                                <h3 class="font-serif text-base font-bold text-white mb-1">Professional Chef</h3>
                                <p class="text-xs text-[#8C7D73]">Internationally acclaimed chefs with decades of fine dining artistry.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Video Frame -->
                <div class="lg:col-span-6 relative">
                    <div class="rounded-3xl overflow-hidden border border-[#C5A880]/30 shadow-2xl relative h-96 flex items-center justify-center">
                        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=800&q=80" 
                             alt="Restaurant Service" class="absolute inset-0 w-full h-full object-cover brightness-75">
                        <button @click="videoModalOpen = true" 
                                class="relative z-10 w-16 h-16 rounded-full border-2 border-[#C5A880] flex items-center justify-center text-[#C5A880] hover:scale-110 hover:bg-[#C5A880] hover:text-black transition-all shadow-2xl">
                            <i data-lucide="play" class="w-6 h-6 fill-current ml-1"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 4. SPECIALIST CUISINE (2x2 GRID + TEXT ON RIGHT) ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: 2x2 Grid -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-[#141414] p-6 rounded-2xl border border-[#C5A880]/20 text-center">
                        <i data-lucide="soup" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Gourmet Food</h4>
                        <p class="text-[11px] text-[#8C7D73]">Curated fine dining specialties</p>
                    </div>
                    <div class="bg-[#141414] p-6 rounded-2xl border border-[#C5A880]/20 text-center">
                        <i data-lucide="utensils" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Western Food</h4>
                        <p class="text-[11px] text-[#8C7D73]">Pastas, steaks & artisan burgers</p>
                    </div>
                    <div class="bg-[#141414] p-6 rounded-2xl border border-[#C5A880]/20 text-center">
                        <i data-lucide="chef-hat" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Delicious Food</h4>
                        <p class="text-[11px] text-[#8C7D73]">Slow-cooked royal recipes</p>
                    </div>
                    <div class="bg-[#141414] p-6 rounded-2xl border border-[#C5A880]/20 text-center">
                        <i data-lucide="sparkles" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Middle East Food</h4>
                        <p class="text-[11px] text-[#8C7D73]">Fragrant dum biryanis & mandi</p>
                    </div>
                </div>

                <!-- Right: Text & CTA -->
                <div class="lg:col-span-5 space-y-4">
                    <p class="font-script text-3xl text-[#C5A880]">Discover</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                        Our Specialist Cuisine
                    </h2>
                    <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('our-menu') }}" class="inline-block px-8 py-3 rounded bg-[#D1A568] hover:bg-[#C5A880] text-black font-bold text-xs uppercase tracking-wider transition-all">
                            DISCOVER
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 5. TESTIMONIALS ════ -->
    <section class="py-20 bg-[#0B0B0B] border-t border-[#C5A880]/15 text-center">
        <div class="max-w-3xl mx-auto px-6 space-y-4">
            <p class="font-script text-3xl text-[#C5A880]">Testimonials</p>
            <h2 class="font-serif text-3xl font-bold text-white tracking-tight">Customer Reviews</h2>
            <div class="pt-6 relative">
                <div class="flex items-center justify-between">
                    <button @click="prevTestimonial()" class="text-[#C5A880] hover:text-white transition-colors p-2"><i data-lucide="chevron-left" class="w-6 h-6"></i></button>
                    <div class="max-w-xl mx-auto px-4">
                        <p class="text-xs sm:text-sm text-[#A8988D] italic leading-relaxed" x-text="testimonials[activeTestimonial].quote"></p>
                        <div class="text-[#C5A880] text-4xl font-serif mt-3 mb-1">“</div>
                        <p class="font-bold text-xs uppercase tracking-wider text-white" x-text="testimonials[activeTestimonial].name"></p>
                    </div>
                    <button @click="nextTestimonial()" class="text-[#C5A880] hover:text-white transition-colors p-2"><i data-lucide="chevron-right" class="w-6 h-6"></i></button>
                </div>
            </div>
        </div>
    </section>

@endsection
