@extends('landing.layout')

@section('title', 'Shop & Dining Packages — ' . ($branch->restaurant_name ?? 'Lezzatos'))

@section('content')

    <!-- ════ PAGE HEADER BANNER ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20" data-aos="fade-down">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                Shop
            </h1>
        </div>
    </section>

    <!-- ════ 1. OUR RECOMMENDED MENU (6 FOOD GRID) ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            
            <div class="text-center space-y-1 mb-16" data-aos="fade-up">
                <p class="font-script text-3xl text-[#C5A880]">Discover</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Our Recommended Menu
                </h2>
            </div>

            <!-- 6 Food Grid (3x2) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                
                <!-- Dish 1 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group shadow-xl luxury-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="luxury-img-zoom h-64">
                        <img src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=600&q=80" 
                             alt="Spicy Soup" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Dish 2 (Active Gold Card) -->
                <div class="bg-[#D1A568] rounded-2xl p-8 chamfer-top-right flex flex-col items-center justify-center text-center text-black shadow-2xl space-y-3 luxury-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex text-yellow-900 text-xs gap-1">
                        ★★★★★
                    </div>
                    <h3 class="font-serif text-xl font-bold">{{ $sundayOffers[0]['title'] ?? 'Chicken Curry Special' }}</h3>
                    <p class="text-2xl font-bold">{{ $sundayOffers[0]['price'] ?? '$22' }}</p>
                    <a href="{{ route('reservation') }}" class="px-6 py-2 rounded bg-white text-black font-bold text-xs uppercase tracking-wider hover:bg-gray-100 transition-all shadow gold-glow-btn">
                        Order Now
                    </a>
                </div>

                <!-- Dish 3 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group shadow-xl luxury-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="luxury-img-zoom h-64">
                        <img src="https://images.unsplash.com/photo-1541696432-82c6da8ce7bf?auto=format&fit=crop&w=600&q=80" 
                             alt="Dumplings" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Dish 4 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group shadow-xl luxury-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="luxury-img-zoom h-64">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80" 
                             alt="Roast Chicken" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Dish 5 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group shadow-xl luxury-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="luxury-img-zoom h-64">
                        <img src="https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=600&q=80" 
                             alt="Vegetables Soup" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Dish 6 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group shadow-xl luxury-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="luxury-img-zoom h-64">
                        <img src="https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=600&q=80" 
                             alt="Shrimp Pasta" class="w-full h-full object-cover">
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ════ 2. SPECIAL ON THIS DAY (DOTTED MENU + FOOD COLLAGE) ════ -->
    <section class="py-20 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: White Card with Dotted Prices -->
                <div class="lg:col-span-5 bg-white text-[#1A1A1A] p-8 sm:p-10 chamfer-top-right shadow-2xl space-y-4 luxury-card" data-aos="fade-right">
                    <p class="font-script text-2xl text-[#C5A880] mb-2">Special on this day</p>

                    @foreach($dottedMenus['specials'] ?? [] as $spec)
                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">{{ $spec['name'] }}</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">{{ $spec['price'] }}</span>
                        </div>
                        <p class="text-[10px] text-gray-500">{{ $spec['desc'] }}</p>
                    </div>
                    @endforeach
                </div>

                <!-- Right: 3 Food Photos Collage -->
                <div class="lg:col-span-7 grid grid-cols-2 gap-3" data-aos="fade-left">
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20 luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80" alt="Salad" class="w-full h-48 object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20 luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=600&q=80" alt="Pasta" class="w-full h-48 object-cover">
                    </div>
                    <div class="col-span-2 rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20 luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1541696432-82c6da8ce7bf?auto=format&fit=crop&w=800&q=80" alt="Dumplings" class="w-full h-48 object-cover">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 3. SOME BEST CATEGORY FOR YOU ════ -->
    <section class="py-24 bg-[#0B0B0B] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-5 space-y-4" data-aos="fade-right">
                    <p class="font-script text-3xl text-[#C5A880]">Category</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                        Some Best Category for You
                    </h2>
                    <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed">
                        Explore our handcrafted cuisine sections and choose from imperial appetizers, hearty entrees, gourmet grills, and dessert masterworks.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('our-menu') }}" class="inline-block px-8 py-3 rounded bg-[#D1A568] hover:bg-[#C5A880] text-black font-bold text-xs uppercase tracking-wider transition-all gold-glow-btn">
                            DISCOVER
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4" data-aos="fade-left">
                    <div class="bg-[#141414] p-6 chamfer-top-right border border-[#C5A880]/20 text-center luxury-card">
                        <i data-lucide="soup" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Main Course</h4>
                        <p class="text-[11px] text-[#8C7D73]">Prime steaks, curries & platters</p>
                    </div>
                    <div class="bg-[#141414] p-6 chamfer-top-right border border-[#C5A880]/20 text-center luxury-card">
                        <i data-lucide="utensils" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Appetizer</h4>
                        <p class="text-[11px] text-[#8C7D73]">Salads, croquettes & soups</p>
                    </div>
                    <div class="bg-[#141414] p-6 chamfer-top-right border border-[#C5A880]/20 text-center luxury-card">
                        <i data-lucide="coffee" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Beverage</h4>
                        <p class="text-[11px] text-[#8C7D73]">Artisan coffee & fresh mocktails</p>
                    </div>
                    <div class="bg-[#141414] p-6 chamfer-top-right border border-[#C5A880]/20 text-center luxury-card">
                        <i data-lucide="cake" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Dessert</h4>
                        <p class="text-[11px] text-[#8C7D73]">Pancakes, cakes & gelato</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 4. SPECIAL PACKAGE FOR CUSTOMERS (PRICING TABLE) ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="text-center space-y-1 mb-16" data-aos="fade-up">
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Special Package for Customers
                </h2>
                <p class="text-xs text-[#8C7D73] max-w-lg mx-auto">
                    Choose from our specially designed multi-course dining packages tailored for individuals, romantic couples, and family celebrations.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($packages as $idx => $pkg)
                <div class="{{ !empty($pkg['is_featured']) ? 'bg-[#D1A568] text-black shadow-2xl' : 'bg-[#141414] text-white border border-[#C5A880]/20' }} rounded-3xl p-8 chamfer-top-right space-y-6 flex flex-col justify-between luxury-card" data-aos="fade-up" data-aos-delay="{{ ($idx + 1) * 100 }}">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-full {{ !empty($pkg['is_featured']) ? 'bg-black/10 text-black' : 'border border-[#C5A880]/40 text-[#C5A880]' }} flex items-center justify-center">
                            <i data-lucide="{{ $pkg['id'] === 'single' ? 'user' : ($pkg['id'] === 'couple' ? 'heart' : 'users') }}" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-serif text-xl font-bold">{{ $pkg['name'] }}</h3>
                        <p class="text-3xl font-serif font-bold">{{ $pkg['price'] }} <span class="text-xs font-sans {{ !empty($pkg['is_featured']) ? 'text-black/70' : 'text-gray-400' }} font-normal">{{ $pkg['billing'] ?? '/ day' }}</span></p>
                        <ul class="space-y-2 text-xs {{ !empty($pkg['is_featured']) ? 'text-black/80 border-black/10' : 'text-[#8C7D73] border-gray-800' }} pt-4 border-t">
                            @foreach($pkg['features'] ?? [] as $feat)
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 {{ !empty($pkg['is_featured']) ? 'text-black' : 'text-[#C5A880]' }}"></i> {{ $feat }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <a href="{{ route('reservation') }}" class="w-full py-3 rounded {{ !empty($pkg['is_featured']) ? 'bg-white text-black hover:bg-gray-100 shadow gold-glow-btn' : 'border border-[#C5A880] text-[#C5A880] hover:bg-[#C5A880] hover:text-black' }} font-bold text-xs uppercase tracking-wider text-center transition-all block">
                        ORDER NOW
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
