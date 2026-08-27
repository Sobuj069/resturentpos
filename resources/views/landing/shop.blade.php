@extends('landing.layout')

@section('title', 'Shop & Dining Packages — Lezzatos Luxury Dining')

@section('content')

    <!-- ════ PAGE HEADER BANNER ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                Shop
            </h1>
        </div>
    </section>

    <!-- ════ 1. OUR RECOMMENDED MENU (6 FOOD GRID) ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            
            <div class="text-center space-y-1 mb-16">
                <p class="font-script text-3xl text-[#C5A880]">Discover</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Our Recommended Menu
                </h2>
            </div>

            <!-- 6 Food Grid (3x2) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                
                <!-- Dish 1 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group shadow-xl">
                    <img src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=600&q=80" 
                         alt="Spicy Soup" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                </div>

                <!-- Dish 2 (Active Gold Card matching screenshot) -->
                <div class="bg-[#D1A568] rounded-2xl p-8 chamfer-top-right flex flex-col items-center justify-center text-center text-black shadow-2xl space-y-3">
                    <div class="flex text-yellow-900 text-xs gap-1">
                        ★★★★★
                    </div>
                    <h3 class="font-serif text-xl font-bold">Chicken Curry Special</h3>
                    <p class="text-2xl font-bold">$22</p>
                    <a href="{{ route('reservation') }}" class="px-6 py-2 rounded bg-white text-black font-bold text-xs uppercase tracking-wider hover:bg-gray-100 transition-colors shadow">
                        Order Now
                    </a>
                </div>

                <!-- Dish 3 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group shadow-xl">
                    <img src="https://images.unsplash.com/photo-1541696432-82c6da8ce7bf?auto=format&fit=crop&w=600&q=80" 
                         alt="Dumplings" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                </div>

                <!-- Dish 4 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group shadow-xl">
                    <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80" 
                         alt="Roast Chicken" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                </div>

                <!-- Dish 5 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group shadow-xl">
                    <img src="https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=600&q=80" 
                         alt="Vegetables Soup" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                </div>

                <!-- Dish 6 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group shadow-xl">
                    <img src="https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=600&q=80" 
                         alt="Shrimp Pasta" class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                </div>

            </div>

        </div>
    </section>

    <!-- ════ 2. SPECIAL ON THIS DAY (DOTTED MENU + FOOD COLLAGE) ════ -->
    <section class="py-20 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: White Card with Dotted Prices -->
                <div class="lg:col-span-5 bg-white text-[#1A1A1A] p-8 sm:p-10 chamfer-top-right shadow-2xl space-y-4">
                    <p class="font-script text-2xl text-[#C5A880] mb-2">Special on this day</p>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Salad</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$14</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Crispy mixed garden greens with herb dressing</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Croquette</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$15</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Golden mashed potato and cheese croquettes</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Samosa</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$10</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Spiced potato and peas triangular savoury pastry</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Canape</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$12</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Artisan bread bites topped with smoked salmon</p>
                    </div>

                    <div>
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Apple Jelly</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$08</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Chilled honey infused sweet apple fruit jelly</p>
                    </div>
                </div>

                <!-- Right: 3 Food Photos Collage -->
                <div class="lg:col-span-7 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80" alt="Salad" class="w-full h-48 object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=600&q=80" alt="Pasta" class="w-full h-48 object-cover">
                    </div>
                    <div class="col-span-2 rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
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
                
                <div class="lg:col-span-5 space-y-4">
                    <p class="font-script text-3xl text-[#C5A880]">Category</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                        Some Best Category for You
                    </h2>
                    <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('our-menu') }}" class="inline-block px-8 py-3 rounded bg-[#D1A568] hover:bg-[#C5A880] text-black font-bold text-xs uppercase tracking-wider transition-all">
                            DISCOVER
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-[#141414] p-6 chamfer-top-right border border-[#C5A880]/20 text-center">
                        <i data-lucide="soup" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Main Course</h4>
                        <p class="text-[11px] text-[#8C7D73]">Prime steaks, curries & platters</p>
                    </div>
                    <div class="bg-[#141414] p-6 chamfer-top-right border border-[#C5A880]/20 text-center">
                        <i data-lucide="utensils" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Appetizer</h4>
                        <p class="text-[11px] text-[#8C7D73]">Salads, croquettes & soups</p>
                    </div>
                    <div class="bg-[#141414] p-6 chamfer-top-right border border-[#C5A880]/20 text-center">
                        <i data-lucide="coffee" class="w-6 h-6 text-[#C5A880] mx-auto mb-3"></i>
                        <h4 class="font-serif text-sm font-bold text-white mb-1">Beverage</h4>
                        <p class="text-[11px] text-[#8C7D73]">Artisan coffee & fresh mocktails</p>
                    </div>
                    <div class="bg-[#141414] p-6 chamfer-top-right border border-[#C5A880]/20 text-center">
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
            <div class="text-center space-y-1 mb-16">
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Special Package for Customers
                </h2>
                <p class="text-xs text-[#8C7D73] max-w-lg mx-auto">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Package 1: Single -->
                <div class="bg-[#141414] rounded-3xl p-8 chamfer-top-right border border-[#C5A880]/20 space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-full border border-[#C5A880]/40 flex items-center justify-center text-[#C5A880]">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-serif text-xl font-bold text-white">Single</h3>
                        <p class="text-3xl font-serif font-bold text-white">$29.99 <span class="text-xs font-sans text-gray-400 font-normal">/ day</span></p>
                        <ul class="space-y-2 text-xs text-[#8C7D73] pt-4 border-t border-gray-800">
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#C5A880]"></i> 1 Signature Main Course</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#C5A880]"></i> 1 Gourmet Appetizer / Soup</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#C5A880]"></i> 1 Choice of Beverage</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#C5A880]"></i> Complimentary Dessert</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#C5A880]"></i> Reserved Priority Seating</li>
                        </ul>
                    </div>
                    <a href="{{ route('reservation') }}" class="w-full py-3 rounded border border-[#C5A880] text-[#C5A880] hover:bg-[#C5A880] hover:text-black font-bold text-xs uppercase tracking-wider text-center transition-all block">
                        ORDER NOW
                    </a>
                </div>

                <!-- Package 2: Couple (Featured Gold Card) -->
                <div class="bg-[#D1A568] rounded-3xl p-8 chamfer-top-right text-black space-y-6 flex flex-col justify-between shadow-2xl">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-full bg-black/10 flex items-center justify-center text-black">
                            <i data-lucide="heart" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-serif text-xl font-bold text-black">Couple</h3>
                        <p class="text-3xl font-serif font-bold text-black">$59.99 <span class="text-xs font-sans text-black/70 font-normal">/ day</span></p>
                        <ul class="space-y-2 text-xs text-black/80 pt-4 border-t border-black/10">
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-black"></i> 2 Signature Main Courses</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-black"></i> 2 Gourmet Appetizers</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-black"></i> 2 Special Mocktails</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-black"></i> Deluxe Dessert Platter</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-black"></i> Candle Light Table Decor</li>
                        </ul>
                    </div>
                    <a href="{{ route('reservation') }}" class="w-full py-3 rounded bg-white text-black font-bold text-xs uppercase tracking-wider text-center hover:bg-gray-100 transition-all block shadow">
                        ORDER NOW
                    </a>
                </div>

                <!-- Package 3: Family -->
                <div class="bg-[#141414] rounded-3xl p-8 chamfer-top-right border border-[#C5A880]/20 space-y-6 flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-full border border-[#C5A880]/40 flex items-center justify-center text-[#C5A880]">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <h3 class="font-serif text-xl font-bold text-white">Family</h3>
                        <p class="text-3xl font-serif font-bold text-white">$99.99 <span class="text-xs font-sans text-gray-400 font-normal">/ day</span></p>
                        <ul class="space-y-2 text-xs text-[#8C7D73] pt-4 border-t border-gray-800">
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#C5A880]"></i> 4 Signature Main Courses</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#C5A880]"></i> Family Sized Appetizer Basket</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#C5A880]"></i> 4 Mocktails / Juices</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#C5A880]"></i> Chef Special Family Cake</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-[#C5A880]"></i> Private Family Booth Reserved</li>
                        </ul>
                    </div>
                    <a href="{{ route('reservation') }}" class="w-full py-3 rounded border border-[#C5A880] text-[#C5A880] hover:bg-[#C5A880] hover:text-black font-bold text-xs uppercase tracking-wider text-center transition-all block">
                        ORDER NOW
                    </a>
                </div>

            </div>
        </div>
    </section>

@endsection
