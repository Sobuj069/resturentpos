@extends('landing.layout')

@section('title', ($branch->restaurant_name ?? 'Lezzatos') . ' — The Authentic Restaurant & Cafe')

@section('content')

    <!-- ════ HERO SECTION ════ -->
    <section id="home" class="relative min-h-[92vh] pt-36 pb-20 flex items-center bg-[#0B0B0B] overflow-hidden">
        
        <!-- High-Visibility Appetizing Food Background Image with Soft Gradient Overlay -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
            <img src="{{ $hero['bg_image'] ?? 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=1920&q=80' }}" 
                 alt="Culinary Food Background" 
                 class="w-full h-full object-cover object-center opacity-70 transform scale-105 transition-transform duration-1000 ease-out filter brightness-95 contrast-110">
            <!-- Left-side soft dark gradient ensuring ultra readability -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/55 to-black/30"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-transparent to-[#0B0B0B]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 sm:px-10 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left 50% Text Column -->
                <div class="lg:col-span-6 space-y-6 text-left" data-aos="fade-right" data-aos-duration="900">
                    <p class="font-script text-3xl sm:text-4xl text-[#C5A880] tracking-wide drop-shadow-[0_2px_10px_rgba(0,0,0,0.9)]" data-aos="fade-down" data-aos-delay="100">
                        {{ $hero['tagline'] ?? 'Welcome to Lezzatos' }}
                    </p>
                    
                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.18] tracking-tight drop-shadow-[0_4px_16px_rgba(0,0,0,0.9)]" data-aos="fade-up" data-aos-delay="200">
                        {{ $hero['title_line1'] ?? 'The Authentic' }} <br>
                        {{ $hero['title_line2'] ?? 'Restaurant & Cafe' }}
                    </h1>

                    <p class="text-xs sm:text-sm text-gray-200 max-w-md font-normal leading-relaxed drop-shadow-[0_2px_8px_rgba(0,0,0,0.9)]" data-aos="fade-up" data-aos-delay="300">
                        {{ $hero['description'] ?? 'Experience royal culinary craftsmanship with our timeless gourmet delicacies, signature dum biryanis, sizzling kebabs, and enchanting fine dining ambiance.' }}
                    </p>

                    <div class="pt-4" data-aos="fade-up" data-aos-delay="400">
                        <a href="{{ $hero['btn_url'] ?? route('our-menu') }}" class="gold-underline-btn text-xs uppercase tracking-[0.25em] font-bold text-white hover:text-[#C5A880] transition-all inline-block shadow-lg">
                            {{ $hero['btn_text'] ?? 'EXPLORE MENU' }}
                        </a>
                    </div>
                </div>

                <!-- Right 50% Food Feast Visual Collage -->
                <div class="lg:col-span-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div class="relative w-full max-w-lg mx-auto bg-[#090909] rounded-3xl p-3 border border-[#C5A880]/30 shadow-2xl overflow-hidden floating-element">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#C5A880]/20 luxury-img-zoom">
                                <img src="{{ $hero['image1'] ?? 'https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=600&q=80' }}" 
                                     alt="Food 1" class="w-full h-44 sm:h-52 object-cover">
                            </div>
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#C5A880]/20 luxury-img-zoom">
                                <img src="{{ $hero['image2'] ?? 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=600&q=80' }}" 
                                     alt="Food 2" class="w-full h-44 sm:h-52 object-cover">
                            </div>
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#C5A880]/20 luxury-img-zoom">
                                <img src="{{ $hero['image3'] ?? 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=600&q=80' }}" 
                                     alt="Food 3" class="w-full h-44 sm:h-52 object-cover">
                            </div>
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#C5A880]/20 luxury-img-zoom">
                                <img src="{{ $hero['image4'] ?? 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=600&q=80' }}" 
                                     alt="Food 4" class="w-full h-44 sm:h-52 object-cover">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 2. SPECIALIST CUISINES ════ -->
    <section class="py-24 bg-[#0E0E0E] relative border-t border-[#C5A880]/15">
        <div class="absolute top-4 right-4 w-28 h-28 gold-diagonal-lines opacity-40 pointer-events-none hidden md:block"></div>
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="text-center space-y-1 mb-16" data-aos="fade-up">
                <p class="font-script text-3xl text-[#C5A880]">Discover</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Our Specialist Cuisine
                </h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($cuisines as $idx => $item)
                <div class="bg-[#141414] p-7 rounded-2xl border border-[#C5A880]/25 relative group luxury-card" data-aos="fade-up" data-aos-delay="{{ ($idx + 1) * 100 }}">
                    <div class="absolute top-0 right-0 w-16 h-16 gold-diagonal-lines opacity-20 group-hover:opacity-50 transition-opacity"></div>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 border border-[#C5A880]/30 group-hover:scale-110 transition-transform" style="background: rgba(197, 168, 128, 0.08);">
                        <i data-lucide="{{ $item['icon'] ?? 'utensils' }}" class="w-5 h-5 text-[#C5A880]"></i>
                    </div>
                    <h3 class="font-serif text-base font-bold text-white mb-2">{{ $item['title'] }}</h3>
                    <p class="text-xs text-[#8C7D73] leading-relaxed">{{ $item['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ════ 3. ABOUT US & STORY ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-6 grid grid-cols-2 gap-4" data-aos="fade-right">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="{{ $about['image1'] ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80' }}" 
                             alt="Restaurant Dining Room" class="w-full h-80 object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl mt-8 luxury-img-zoom">
                        <img src="{{ $about['image2'] ?? 'https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=600&q=80' }}" 
                             alt="Grand Lobby Setting" class="w-full h-80 object-cover">
                    </div>
                </div>

                <div class="lg:col-span-6" data-aos="fade-left">
                    <div class="bg-white text-[#1A1A1A] p-8 sm:p-12 chamfer-top-right shadow-2xl relative luxury-card">
                        <p class="font-script text-2xl text-[#C5A880] mb-1">{{ $about['tagline'] ?? 'About Us' }}</p>
                        <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#111111] mb-4 leading-tight">{{ $about['title'] ?? 'Our Story Make History' }}</h2>
                        <p class="text-xs sm:text-sm text-[#665D56] leading-relaxed mb-4">{{ $about['story_p1'] ?? '' }}</p>
                        <p class="text-xs sm:text-sm text-[#665D56] leading-relaxed mb-8">{{ $about['story_p2'] ?? '' }}</p>
                        <a href="{{ route('about-us') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#C5A880] hover:text-[#111] transition-colors border-b border-[#C5A880] pb-0.5">
                            <span>Discover More</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ════ 4. STATS COUNTER ════ -->
    <section class="py-14 bg-[#111111] border-t border-b border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center" data-aos="fade-up">
                <div class="space-y-1">
                    <p class="font-serif text-3xl sm:text-4xl font-bold text-white">{{ $stats['restaurants'] ?? '12' }}</p>
                    <p class="text-[11px] uppercase tracking-widest text-[#8C7D73]">{{ $stats['restaurants_label'] ?? 'Restaurants' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="font-serif text-3xl sm:text-4xl font-bold text-white">{{ $stats['experience_years'] ?? '8' }}</p>
                    <p class="text-[11px] uppercase tracking-widest text-[#8C7D73]">{{ $stats['experience_label'] ?? 'Years Experience' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="font-serif text-3xl sm:text-4xl font-bold text-white">{{ $stats['awards_won'] ?? '50+' }}</p>
                    <p class="text-[11px] uppercase tracking-widest text-[#8C7D73]">{{ $stats['awards_label'] ?? 'Award Winner' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="font-serif text-3xl sm:text-4xl font-bold text-white">{{ $stats['food_menus'] ?? '200+' }}</p>
                    <p class="text-[11px] uppercase tracking-widest text-[#8C7D73]">{{ $stats['menus_label'] ?? 'Food Menus' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ════ 4.5. BRAND PARTNERS & SPONSORS (INFINITE MARQUEE SCROLLER WITH SVG LOGOS) ════ -->
    <section class="py-10 bg-[#090909] border-b border-[#C5A880]/15 relative overflow-hidden" data-aos="fade-up">
        <!-- Marquee Infinite Track (Hardware-Accelerated 60FPS) -->
        <div class="marquee-container py-2">
            <div class="marquee-track">
                <!-- Group 1: Original Brand SVG Logos -->
                <div class="flex items-center gap-12 sm:gap-16 shrink-0">
                    <!-- 1. Coca-Cola -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto text-white fill-current" viewBox="0 0 1000 327" xmlns="http://www.w3.org/2000/svg"><path d="M790.6 84.1c-30.8 0-48.4 17.6-58.8 33.7-1.3-17.6-14-33.7-44.5-33.7-31.9 0-51.5 21.7-57.9 37.3-3.8-21.7-22.1-37.3-46.7-37.3-31.4 0-52 23.3-56.7 39.8-1.5-1.9-3.3-3.8-5.3-5.5-23.7-20.5-57.4-23.8-83.8-7.7-18.7-29.3-50.6-47.5-87.3-47.5-62.9 0-109.9 53.6-109.9 125.1 0 71.9 47 125.6 109.9 125.6 34.6 0 65-16.3 83.2-42.6 12 10.7 26.6 17.5 43.1 19.3-12.7 18.2-19.7 40.5-19.7 64.1 0 7.8.8 15.4 2.3 22.8 3.5 17.4 12.8 28.5 25.9 31.4 2.8.6 5.7 1 8.7 1 18.8 0 35.8-13.8 40.8-33.4 5.9-23.2 2.7-56-8.5-84.3 11.2-11.4 25.8-18.5 42-18.5 15.6 0 29.6 6.6 40.6 17.3-14.7 21-23.4 46.5-23.4 74 0 45.4 29.4 78 70.8 78 39.7 0 69.1-30.8 69.1-72.7 0-44.8-31.7-77.9-74.1-77.9-7.3 0-14.3 1-20.9 3 2.1-13.5 7.6-26.1 15.7-36.6 7.6 8.5 18.6 13.9 30.8 13.9 22.9 0 39.6-18.9 39.6-44.8 0-3.3-.3-6.5-.9-9.6 10.5 10.8 24.8 17.5 40.7 17.5 32.8 0 54.3-24.6 54.3-58.4 0-33.3-21.1-57.3-53.9-57.3zm-441 143.1c-38.3 0-66.5-35.3-66.5-81.8 0-46.1 28.2-81.4 66.5-81.4 38.8 0 67 35.3 67 81.4 0 46.5-28.2 81.8-67 81.8z"/></svg>
                    </div>

                    <!-- 2. Starbucks -->
                    <div class="partner-logo-item">
                        <svg class="h-9 sm:h-10 w-auto" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="20" r="19" stroke="#00704A" stroke-width="2" fill="#00704A"/><circle cx="20" cy="20" r="16" stroke="white" stroke-width="1.2"/><path d="M20 7L21.2 10.7H25.1L21.9 13L23.1 16.7L20 14.4L16.9 16.7L18.1 13L14.9 10.7H18.8L20 7Z" fill="white"/><path d="M14 26C14 22 17 19 20 19C23 19 26 22 26 26" stroke="white" stroke-width="1.8" stroke-linecap="round"/><circle cx="20" cy="22" r="2" fill="white"/></svg>
                    </div>

                    <!-- 3. Foodpanda -->
                    <div class="partner-logo-item">
                        <svg class="h-8 sm:h-9 w-auto" viewBox="0 0 160 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="36" rx="10" fill="#D70F64"/><path d="M12 14C12 11.8 13.8 10 16 10C17.5 10 18.8 10.8 19.5 12C20.2 10.8 21.5 10 23 10C25.2 10 27 11.8 27 14C27 15.2 26.5 16.2 25.6 17C26.5 18 27 19.4 27 21C27 24.3 24.3 27 21 27C17.7 27 15 24.3 15 21C15 19.4 15.5 18 16.4 17C15.5 16.2 15 15.2 15 14H12Z" fill="white"/><circle cx="16" cy="18" r="1.5" fill="#D70F64"/><circle cx="23" cy="18" r="1.5" fill="#D70F64"/><text x="44" y="25" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="18" fill="#D70F64" letter-spacing="-0.5">foodpanda</text></svg>
                    </div>

                    <!-- 4. Uber Eats -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 150 36" fill="none" xmlns="http://www.w3.org/2000/svg"><text x="0" y="27" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="24" fill="#FFFFFF">Uber</text><text x="64" y="27" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="24" fill="#06C167">Eats</text></svg>
                    </div>

                    <!-- 5. Pepsi -->
                    <div class="partner-logo-item">
                        <svg class="h-8 sm:h-9 w-auto" viewBox="0 0 130 36" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="18" cy="18" r="16" fill="#004B93"/><path d="M4 14C8 10 18 10 32 14C32 9 27 4 18 4C9 4 4 9 4 14Z" fill="#E32934"/><path d="M4 22C8 26 18 26 32 22C32 27 27 32 18 32C9 32 4 27 4 22Z" fill="#004B93"/><path d="M4 14C12 12 24 16 32 14C32 18 24 22 4 14Z" fill="white"/><text x="42" y="26" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="20" fill="#FFFFFF" letter-spacing="1.5">PEPSI</text></svg>
                    </div>

                    <!-- 6. Nestlé -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto text-white fill-current" viewBox="0 0 130 34" xmlns="http://www.w3.org/2000/svg"><text x="0" y="26" font-family="'Georgia', serif" font-weight="bold" font-size="26" fill="#C5A880" letter-spacing="1.5">Nestlé</text></svg>
                    </div>

                    <!-- 7. McDonald's -->
                    <div class="partner-logo-item">
                        <svg class="h-8 sm:h-9 w-auto" viewBox="0 0 38 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 7C5 7 2.5 14.5 2.5 23.5C2.5 25.8 2.8 28 3.5 29.8H7.5C7.2 28.2 7 26 7 23.5C7 16.5 8.5 11 10.5 11C12.5 11 14 16.5 14 23.5C14 26 13.8 28.2 13.5 29.8H17.8C17.5 28.2 17.3 26 17.3 23.5C17.3 16.5 18.8 11 20.8 11C22.8 11 24.3 16.5 24.3 23.5C24.3 26 24.1 28.2 23.8 29.8H34.5C35.2 28 35.5 25.8 35.5 23.5C35.5 14.5 33 7 30 7C26.5 7 23.6 16.5 22.8 23.8C22 17 19.2 7 15.8 7C12.4 7 9.6 17 8.8 23.8C8 16.5 10.5 7 8 7Z" fill="#FFBC0D"/></svg>
                    </div>

                    <!-- 8. Lavazza -->
                    <div class="partner-logo-item">
                        <svg class="h-6 sm:h-7 w-auto" viewBox="0 0 135 32" fill="none" xmlns="http://www.w3.org/2000/svg"><text x="0" y="24" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="22" fill="#FFFFFF" letter-spacing="2.5">LAVAZZA</text></svg>
                    </div>

                    <!-- 9. Heineken -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 145 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L14.5 9.5L20.5 10.4L16.2 14.6L17.2 20.6L12 17.9L6.8 20.6L7.8 14.6L3.5 10.4L9.5 9.5L12 4Z" fill="#E31837"/><text x="28" y="23" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="20" fill="#008200" letter-spacing="1">Heineken</text></svg>
                    </div>

                    <!-- 10. Red Bull -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 145 32" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="13" fill="#FFCC00"/><path d="M6 20C8 16 14 14 19 16C21 17 23 19 25 21C21 20 17 20 13 22C10 23 8 22 6 20Z" fill="#CC0000"/><text x="36" y="23" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="18" fill="#FFFFFF" letter-spacing="0.5">RedBull</text></svg>
                    </div>

                    <!-- 11. San Pellegrino -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 180 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 5L12.5 10L18 10.8L14 14.7L15 20.2L10 17.5L5 20.2L6 14.7L2 10.8L7.5 10L10 5Z" fill="#C5A880"/><text x="26" y="22" font-family="'Georgia', serif" font-weight="bold" font-size="16" fill="#FFFFFF" letter-spacing="2">S.PELLEGRINO</text></svg>
                    </div>

                    <!-- 12. Mastercard -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 54 36" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="18" cy="18" r="16" fill="#EB001B"/><circle cx="36" cy="18" r="16" fill="#F79E1B" fill-opacity="0.88"/></svg>
                    </div>

                    <!-- 13. Michelin -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 140 32" fill="none" xmlns="http://www.w3.org/2000/svg"><text x="0" y="23" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="20" fill="#2B72D7" letter-spacing="1.5">MICHELIN</text></svg>
                    </div>
                </div>

                <!-- Group 2: Seamless Infinite Marquee Loop Duplicate -->
                <div class="flex items-center gap-12 sm:gap-16 shrink-0" aria-hidden="true">
                    <!-- 1. Coca-Cola -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto text-white fill-current" viewBox="0 0 1000 327" xmlns="http://www.w3.org/2000/svg"><path d="M790.6 84.1c-30.8 0-48.4 17.6-58.8 33.7-1.3-17.6-14-33.7-44.5-33.7-31.9 0-51.5 21.7-57.9 37.3-3.8-21.7-22.1-37.3-46.7-37.3-31.4 0-52 23.3-56.7 39.8-1.5-1.9-3.3-3.8-5.3-5.5-23.7-20.5-57.4-23.8-83.8-7.7-18.7-29.3-50.6-47.5-87.3-47.5-62.9 0-109.9 53.6-109.9 125.1 0 71.9 47 125.6 109.9 125.6 34.6 0 65-16.3 83.2-42.6 12 10.7 26.6 17.5 43.1 19.3-12.7 18.2-19.7 40.5-19.7 64.1 0 7.8.8 15.4 2.3 22.8 3.5 17.4 12.8 28.5 25.9 31.4 2.8.6 5.7 1 8.7 1 18.8 0 35.8-13.8 40.8-33.4 5.9-23.2 2.7-56-8.5-84.3 11.2-11.4 25.8-18.5 42-18.5 15.6 0 29.6 6.6 40.6 17.3-14.7 21-23.4 46.5-23.4 74 0 45.4 29.4 78 70.8 78 39.7 0 69.1-30.8 69.1-72.7 0-44.8-31.7-77.9-74.1-77.9-7.3 0-14.3 1-20.9 3 2.1-13.5 7.6-26.1 15.7-36.6 7.6 8.5 18.6 13.9 30.8 13.9 22.9 0 39.6-18.9 39.6-44.8 0-3.3-.3-6.5-.9-9.6 10.5 10.8 24.8 17.5 40.7 17.5 32.8 0 54.3-24.6 54.3-58.4 0-33.3-21.1-57.3-53.9-57.3zm-441 143.1c-38.3 0-66.5-35.3-66.5-81.8 0-46.1 28.2-81.4 66.5-81.4 38.8 0 67 35.3 67 81.4 0 46.5-28.2 81.8-67 81.8z"/></svg>
                    </div>

                    <!-- 2. Starbucks -->
                    <div class="partner-logo-item">
                        <svg class="h-9 sm:h-10 w-auto" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="20" cy="20" r="19" stroke="#00704A" stroke-width="2" fill="#00704A"/><circle cx="20" cy="20" r="16" stroke="white" stroke-width="1.2"/><path d="M20 7L21.2 10.7H25.1L21.9 13L23.1 16.7L20 14.4L16.9 16.7L18.1 13L14.9 10.7H18.8L20 7Z" fill="white"/><path d="M14 26C14 22 17 19 20 19C23 19 26 22 26 26" stroke="white" stroke-width="1.8" stroke-linecap="round"/><circle cx="20" cy="22" r="2" fill="white"/></svg>
                    </div>

                    <!-- 3. Foodpanda -->
                    <div class="partner-logo-item">
                        <svg class="h-8 sm:h-9 w-auto" viewBox="0 0 160 40" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="36" height="36" rx="10" fill="#D70F64"/><path d="M12 14C12 11.8 13.8 10 16 10C17.5 10 18.8 10.8 19.5 12C20.2 10.8 21.5 10 23 10C25.2 10 27 11.8 27 14C27 15.2 26.5 16.2 25.6 17C26.5 18 27 19.4 27 21C27 24.3 24.3 27 21 27C17.7 27 15 24.3 15 21C15 19.4 15.5 18 16.4 17C15.5 16.2 15 15.2 15 14H12Z" fill="white"/><circle cx="16" cy="18" r="1.5" fill="#D70F64"/><circle cx="23" cy="18" r="1.5" fill="#D70F64"/><text x="44" y="25" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="18" fill="#D70F64" letter-spacing="-0.5">foodpanda</text></svg>
                    </div>

                    <!-- 4. Uber Eats -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 150 36" fill="none" xmlns="http://www.w3.org/2000/svg"><text x="0" y="27" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="24" fill="#FFFFFF">Uber</text><text x="64" y="27" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="24" fill="#06C167">Eats</text></svg>
                    </div>

                    <!-- 5. Pepsi -->
                    <div class="partner-logo-item">
                        <svg class="h-8 sm:h-9 w-auto" viewBox="0 0 130 36" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="18" cy="18" r="16" fill="#004B93"/><path d="M4 14C8 10 18 10 32 14C32 9 27 4 18 4C9 4 4 9 4 14Z" fill="#E32934"/><path d="M4 22C8 26 18 26 32 22C32 27 27 32 18 32C9 32 4 27 4 22Z" fill="#004B93"/><path d="M4 14C12 12 24 16 32 14C32 18 24 22 4 14Z" fill="white"/><text x="42" y="26" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="20" fill="#FFFFFF" letter-spacing="1.5">PEPSI</text></svg>
                    </div>

                    <!-- 6. Nestlé -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto text-white fill-current" viewBox="0 0 130 34" xmlns="http://www.w3.org/2000/svg"><text x="0" y="26" font-family="'Georgia', serif" font-weight="bold" font-size="26" fill="#C5A880" letter-spacing="1.5">Nestlé</text></svg>
                    </div>

                    <!-- 7. McDonald's -->
                    <div class="partner-logo-item">
                        <svg class="h-8 sm:h-9 w-auto" viewBox="0 0 38 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 7C5 7 2.5 14.5 2.5 23.5C2.5 25.8 2.8 28 3.5 29.8H7.5C7.2 28.2 7 26 7 23.5C7 16.5 8.5 11 10.5 11C12.5 11 14 16.5 14 23.5C14 26 13.8 28.2 13.5 29.8H17.8C17.5 28.2 17.3 26 17.3 23.5C17.3 16.5 18.8 11 20.8 11C22.8 11 24.3 16.5 24.3 23.5C24.3 26 24.1 28.2 23.8 29.8H34.5C35.2 28 35.5 25.8 35.5 23.5C35.5 14.5 33 7 30 7C26.5 7 23.6 16.5 22.8 23.8C22 17 19.2 7 15.8 7C12.4 7 9.6 17 8.8 23.8C8 16.5 10.5 7 8 7Z" fill="#FFBC0D"/></svg>
                    </div>

                    <!-- 8. Lavazza -->
                    <div class="partner-logo-item">
                        <svg class="h-6 sm:h-7 w-auto" viewBox="0 0 135 32" fill="none" xmlns="http://www.w3.org/2000/svg"><text x="0" y="24" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="22" fill="#FFFFFF" letter-spacing="2.5">LAVAZZA</text></svg>
                    </div>

                    <!-- 9. Heineken -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 145 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 4L14.5 9.5L20.5 10.4L16.2 14.6L17.2 20.6L12 17.9L6.8 20.6L7.8 14.6L3.5 10.4L9.5 9.5L12 4Z" fill="#E31837"/><text x="28" y="23" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="20" fill="#008200" letter-spacing="1">Heineken</text></svg>
                    </div>

                    <!-- 10. Red Bull -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 145 32" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="16" cy="16" r="13" fill="#FFCC00"/><path d="M6 20C8 16 14 14 19 16C21 17 23 19 25 21C21 20 17 20 13 22C10 23 8 22 6 20Z" fill="#CC0000"/><text x="36" y="23" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="18" fill="#FFFFFF" letter-spacing="0.5">RedBull</text></svg>
                    </div>

                    <!-- 11. San Pellegrino -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 180 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 5L12.5 10L18 10.8L14 14.7L15 20.2L10 17.5L5 20.2L6 14.7L2 10.8L7.5 10L10 5Z" fill="#C5A880"/><text x="26" y="22" font-family="'Georgia', serif" font-weight="bold" font-size="16" fill="#FFFFFF" letter-spacing="2">S.PELLEGRINO</text></svg>
                    </div>

                    <!-- 12. Mastercard -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 54 36" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="18" cy="18" r="16" fill="#EB001B"/><circle cx="36" cy="18" r="16" fill="#F79E1B" fill-opacity="0.88"/></svg>
                    </div>

                    <!-- 13. Michelin -->
                    <div class="partner-logo-item">
                        <svg class="h-7 sm:h-8 w-auto" viewBox="0 0 140 32" fill="none" xmlns="http://www.w3.org/2000/svg"><text x="0" y="23" font-family="'Inter', -apple-system, sans-serif" font-weight="900" font-size="20" fill="#2B72D7" letter-spacing="1.5">MICHELIN</text></svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ════ 5. SPECIAL DISH & BEST RECOMMENDATION ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="text-center space-y-1 mb-16" data-aos="fade-up">
                <p class="font-script text-3xl text-[#C5A880]">Special Dish</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">Best Recommendation Menu</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                @foreach($recommendedDishes as $idx => $dish)
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group luxury-card" data-aos="fade-up" data-aos-delay="{{ ($idx + 1) * 100 }}">
                    <div class="relative h-64 overflow-hidden luxury-img-zoom">
                        <img src="{{ $dish['image'] }}" alt="{{ $dish['name'] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 bg-white text-[#1A1A1A] chamfer-top-right -mt-6 relative z-10 m-3 rounded-xl shadow-2xl">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-serif font-bold text-sm text-[#111]">{{ $dish['name'] }}</h3>
                            <span class="font-bold text-xs text-[#C5A880]">{{ $dish['price'] }}</span>
                        </div>
                        <p class="text-[11px] text-[#665D56] mb-3">{{ $dish['description'] }}</p>
                        <a href="{{ route('reservation') }}" class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-[#C5A880] hover:translate-x-1 transition-transform"><span>Order Dish</span><i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Bottom 2-Column: Left Dotted Menu List, Right Chicken Dish Photo -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-[#111111] p-6 sm:p-10 rounded-3xl border border-[#C5A880]/20 relative" data-aos="fade-up">
                <div class="absolute top-2 left-2 w-20 h-20 gold-diagonal-lines opacity-20 hidden md:block"></div>
                <div class="lg:col-span-6 bg-white text-[#1A1A1A] p-6 sm:p-8 rounded-2xl shadow-2xl space-y-4">
                    @foreach($dottedMenus['specials'] ?? [] as $spec)
                    <div class="border-b border-gray-100 pb-2">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">{{ $spec['name'] }}</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">{{ $spec['price'] }}</span>
                        </div>
                        <p class="text-[10px] text-gray-500">{{ $spec['desc'] }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80" 
                             alt="Signature Meat Platter" class="w-full h-80 sm:h-96 object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ════ 6. TABLE RESERVATION FORM ════ -->
    <section class="py-24 bg-[#090909] relative border-t border-[#C5A880]/15">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-5 space-y-4 text-left" data-aos="fade-right">
                    <p class="font-script text-3xl text-[#C5A880]">Reservation</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">Feel Happiness by Making a Reservation</h2>
                    <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed">Reserve your royal dining table in advance for birthdays, family gatherings, corporate dinners, or intimate romantic evenings.</p>
                </div>

                <div class="lg:col-span-7" data-aos="fade-left">
                    <div class="bg-[#D1A568] p-8 sm:p-10 chamfer-top-right shadow-2xl text-[#1A1105] luxury-card">
                        <div class="text-center mb-6">
                            <h3 class="font-serif text-2xl sm:text-3xl font-bold text-white">Book Table</h3>
                        </div>
                        <form @submit.prevent="submitReservation()" class="space-y-3.5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div><input type="text" x-model="form.customer_name" required placeholder="Name" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm"></div>
                                <div><input type="text" x-model="form.customer_phone" required placeholder="Phone / Email" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm"></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div><input type="date" x-model="form.reservation_date" required min="{{ date('Y-m-d') }}" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] focus:outline-none shadow-sm"></div>
                                <div>
                                    <select x-model="form.reservation_time" required class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] focus:outline-none shadow-sm">
                                        <option value="01:00 PM">01:00 PM (Lunch)</option>
                                        <option value="02:00 PM">02:00 PM (Lunch)</option>
                                        <option value="07:30 PM">07:30 PM (Dinner)</option>
                                        <option value="08:30 PM" selected>08:30 PM (Prime Dinner)</option>
                                        <option value="09:30 PM">09:30 PM (Late Dinner)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <select x-model.number="form.guest_count" required class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] focus:outline-none shadow-sm">
                                        <option value="1">1 Person</option>
                                        <option value="2" selected>2 Persons</option>
                                        <option value="4">4 Persons</option>
                                        <option value="6">6 Persons</option>
                                        <option value="8">8 Persons</option>
                                    </select>
                                </div>
                                <div>
                                    <select x-model="form.table_id" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] focus:outline-none shadow-sm">
                                        <option value="">Select Table</option>
                                        @foreach($tables as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->floor_name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="pt-2">
                                <button type="submit" :disabled="isSubmitting" class="w-full py-3.5 rounded bg-white hover:bg-gray-100 text-[#111] font-bold text-xs uppercase tracking-widest transition-all shadow-xl active:scale-98 gold-glow-btn">
                                    <span x-text="isSubmitting ? 'RESERVING...' : 'BOOK A TABLE'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ════ 7. CUSTOMER REVIEWS (AUTO-SLIDING SLIDER) ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative overflow-hidden">
        <div class="max-w-3xl mx-auto px-6 text-center space-y-4 relative z-10" data-aos="fade-up">
            <p class="font-script text-3xl text-[#C5A880]">Testimonials</p>
            <h2 class="font-serif text-3xl font-bold text-white tracking-tight">Customer Reviews</h2>
            
            <div class="pt-6 relative">
                <div class="flex items-center justify-between gap-4">
                    <button @click="prevTestimonial()" class="text-[#C5A880] hover:text-white transition-all p-2 hover:scale-125 transform cursor-pointer shrink-0">
                        <i data-lucide="chevron-left" class="w-6 h-6"></i>
                    </button>
                    
                    <div class="max-w-xl mx-auto px-4 min-h-[160px] flex flex-col justify-center items-center">
                        <template x-if="testimonials.length > 0 && testimonials[activeTestimonial]">
                            <div class="space-y-3 transition-all duration-500 ease-in-out">
                                <p class="text-xs sm:text-sm text-[#D8CDC4] italic leading-relaxed" 
                                   x-text="'“' + testimonials[activeTestimonial].quote + '”'"></p>
                                
                                <div class="text-[#C5A880] text-sm tracking-widest">
                                    ★★★★★
                                </div>

                                <div>
                                    <p class="font-serif font-bold text-sm uppercase tracking-widest text-white" 
                                       x-text="testimonials[activeTestimonial].name"></p>
                                    <p class="text-[11px] text-[#C5A880] font-medium mt-0.5" 
                                       x-text="testimonials[activeTestimonial].role || 'Guest & Connoisseur'"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button @click="nextTestimonial()" class="text-[#C5A880] hover:text-white transition-all p-2 hover:scale-125 transform cursor-pointer shrink-0">
                        <i data-lucide="chevron-right" class="w-6 h-6"></i>
                    </button>
                </div>

                <!-- Slide Dots Indicators -->
                <div class="flex items-center justify-center gap-2 mt-6">
                    <template x-for="(item, idx) in testimonials" :key="idx">
                        <button @click="activeTestimonial = idx" 
                                class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                                :class="activeTestimonial === idx ? 'bg-[#C5A880] w-7' : 'bg-white/25 hover:bg-white/50 w-2'"></button>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- ════ 8. VIDEO AMBIENCE ════ -->
    <section class="relative h-80 sm:h-96 flex items-center justify-center overflow-hidden border-t border-b border-[#C5A880]/20" data-aos="zoom-in">
        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=1600&q=80" alt="Ambience" class="absolute inset-0 w-full h-full object-cover brightness-50">
        <div class="relative z-10 text-center space-y-3 px-4">
            <button @click="videoModalOpen = true" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-[#C5A880] flex items-center justify-center text-[#C5A880] hover:scale-110 hover:bg-[#C5A880] hover:text-black transition-all shadow-[0_0_30px_rgba(197,168,128,0.4)] mx-auto cursor-pointer">
                <i data-lucide="play" class="w-6 h-6 fill-current ml-1"></i>
            </button>
        </div>
    </section>

    <!-- ════ 9. BLOG / TIPS & TRICKS ════ -->
    <section class="py-24 bg-[#0B0B0B]">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="text-center space-y-1 mb-16" data-aos="fade-up">
                <p class="font-script text-3xl text-[#C5A880]">Blog Post</p>
                <h2 class="font-serif text-3xl font-bold text-white tracking-tight">Tips & Tricks</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($blogs as $idx => $blog)
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group hover:border-[#C5A880] transition-all luxury-card" data-aos="fade-up" data-aos-delay="{{ ($idx + 1) * 100 }}">
                    <div class="relative h-52 overflow-hidden luxury-img-zoom">
                        <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5 chamfer-top-right bg-[#181818] -mt-4 relative z-10 m-2.5 rounded-xl border border-[#C5A880]/15">
                        <h3 class="font-serif text-sm font-bold text-white mb-2 line-clamp-1 group-hover:text-[#C5A880] transition-colors">{{ $blog['title'] }}</h3>
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
