@extends('landing.layout')

@section('title', 'Our Menu — Lezzatos Luxury Dining')

@section('content')

    <!-- ════ PAGE HEADER BANNER (MATCHING SCREENSHOT) ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                Our Menu
            </h1>
        </div>
    </section>

    <!-- ════ 1. SPECIAL OFFER ON SUNDAY (HOT MENU) ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <!-- Top Right Diagonal Pattern -->
        <div class="absolute top-4 right-4 w-28 h-28 gold-diagonal-lines opacity-30 pointer-events-none hidden md:block"></div>

        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="text-center space-y-1 mb-16">
                <p class="font-script text-3xl text-[#C5A880]">Hot Menu</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Special Offer on Sunday
                </h2>
            </div>

            <!-- 4 Offer Cards with 20% OFF Tag -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group text-center p-3">
                    <div class="relative h-44 overflow-hidden rounded-xl">
                        <span class="absolute top-2 left-2 z-10 px-2 py-0.5 rounded bg-[#D1A568] text-black font-black text-[9px] uppercase tracking-wider shadow">20% OFF</span>
                        <img src="https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=500&q=80" 
                             alt="Sauce Spicy Soup" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="pt-4 pb-2">
                        <h3 class="font-serif text-sm font-bold text-white mb-1">Sauce Spicy Soup</h3>
                        <p class="font-bold text-xs text-[#C5A880]">$18</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group text-center p-3">
                    <div class="relative h-44 overflow-hidden rounded-xl">
                        <span class="absolute top-2 left-2 z-10 px-2 py-0.5 rounded bg-[#D1A568] text-black font-black text-[9px] uppercase tracking-wider shadow">20% OFF</span>
                        <img src="https://images.unsplash.com/photo-1543339308-43e59d6b73a6?auto=format&fit=crop&w=500&q=80" 
                             alt="Vegetables Soup" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="pt-4 pb-2">
                        <h3 class="font-serif text-sm font-bold text-white mb-1">Vegetables Soup</h3>
                        <p class="font-bold text-xs text-[#C5A880]">$20</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group text-center p-3">
                    <div class="relative h-44 overflow-hidden rounded-xl">
                        <span class="absolute top-2 left-2 z-10 px-2 py-0.5 rounded bg-[#D1A568] text-black font-black text-[9px] uppercase tracking-wider shadow">20% OFF</span>
                        <img src="https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=500&q=80" 
                             alt="Salmon Pasta" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="pt-4 pb-2">
                        <h3 class="font-serif text-sm font-bold text-white mb-1">Salmon Pasta</h3>
                        <p class="font-bold text-xs text-[#C5A880]">$22</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group text-center p-3">
                    <div class="relative h-44 overflow-hidden rounded-xl">
                        <span class="absolute top-2 left-2 z-10 px-2 py-0.5 rounded bg-[#D1A568] text-black font-black text-[9px] uppercase tracking-wider shadow">20% OFF</span>
                        <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=500&q=80" 
                             alt="Salad Box" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="pt-4 pb-2">
                        <h3 class="font-serif text-sm font-bold text-white mb-1">Salad Box</h3>
                        <p class="font-bold text-xs text-[#C5A880]">$15</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 2. MENU CATEGORY: APPETIZER ════ -->
    <section class="py-20 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: White Card with Dotted Prices (Appetizer) -->
                <div class="lg:col-span-5 bg-white text-[#1A1A1A] p-8 sm:p-10 chamfer-top-right shadow-2xl space-y-4">
                    <p class="font-script text-2xl text-[#C5A880] mb-2">Appetizer</p>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Pastel</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$20</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Crispy pastry pockets filled with spiced minced chicken</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Croquette</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$22</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Golden potato & cheese croquettes with garlic dip</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Ravioles</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$18</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Handmade ricotta stuffed pasta in sage butter</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Canapes</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$15</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Bite-sized toasted baguettes with gourmet toppings</p>
                    </div>

                    <div>
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Agro Dolce</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$18</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Sweet and sour glazed appetizer meatballs</p>
                    </div>
                </div>

                <!-- Right: 3 Food Photos Collage -->
                <div class="lg:col-span-7 grid grid-cols-2 gap-3">
                    <div class="col-span-2 rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1541696432-82c6da8ce7bf?auto=format&fit=crop&w=800&q=80" 
                             alt="Dumplings" class="w-full h-48 object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=600&q=80" 
                             alt="Pasta" class="w-full h-48 object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80" 
                             alt="Salad" class="w-full h-48 object-cover">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 3. MENU CATEGORY: MAIN COURSE ════ -->
    <section class="py-20 bg-[#0B0B0B] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: 3 Food Photos Collage -->
                <div class="lg:col-span-7 grid grid-cols-2 gap-3 order-2 lg:order-1">
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80" 
                             alt="Roast Chicken" class="w-full h-48 object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1558030006-450675393462?auto=format&fit=crop&w=600&q=80" 
                             alt="Prime Steak" class="w-full h-48 object-cover">
                    </div>
                    <div class="col-span-2 rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=800&q=80" 
                             alt="Butter Chicken Feast" class="w-full h-48 object-cover">
                    </div>
                </div>

                <!-- Right: White Card with Dotted Prices (Main Course) -->
                <div class="lg:col-span-5 bg-white text-[#1A1A1A] p-8 sm:p-10 chamfer-top-right shadow-2xl space-y-4 order-1 lg:order-2">
                    <p class="font-script text-2xl text-[#C5A880] mb-2">Main Course</p>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Sirloin Steak</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$32</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Flame-grilled prime sirloin with truffle potato mash</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Parmesan Spicy Soup</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$28</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Rich parmesan broth with tender meat slices & chili oil</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Salmon Pasta</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$35</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Pan-seared salmon fillet over creamy fettuccine</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Chicken Curry Special</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$24</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Slow-cooked chicken in fragrant royal Mughlai gravy</p>
                    </div>

                    <div>
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Dimsum</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$18</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Steamed artisan dumplings with spicy sesame soy dip</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 4. MENU CATEGORY: DESSERT ════ -->
    <section class="py-20 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: White Card with Dotted Prices (Dessert) -->
                <div class="lg:col-span-5 bg-white text-[#1A1A1A] p-8 sm:p-10 chamfer-top-right shadow-2xl space-y-4">
                    <p class="font-script text-2xl text-[#C5A880] mb-2">Dessert</p>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Pancakes Fresche</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$16</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Fluffy stack with wild berries & organic maple drizzle</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Ice Cream</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$12</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Artisanal Madagascar vanilla & pistachio gelato</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Cantucci</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$15</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Crunchy almond biscotti served with espresso cream</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2.5">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Arricciate Spian</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$14</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Crisp puff pastry filled with sweetened mascarpone</p>
                    </div>

                    <div>
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Cornetto</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$12</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Warm Italian butter croissant with hazelnut chocolate</p>
                    </div>
                </div>

                <!-- Right: 3 Food Photos Collage -->
                <div class="lg:col-span-7 grid grid-cols-2 gap-3">
                    <div class="col-span-2 rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=800&q=80" 
                             alt="Cream Puffs" class="w-full h-48 object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=600&q=80" 
                             alt="Pancakes" class="w-full h-48 object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-[#C5A880]/20">
                        <img src="https://images.unsplash.com/photo-1541696432-82c6da8ce7bf?auto=format&fit=crop&w=600&q=80" 
                             alt="Steamed Sweets" class="w-full h-48 object-cover">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 5. THE BEST INGREDIENTS VIDEO BANNER ════ -->
    <section class="py-20 bg-[#0B0B0B] border-t border-[#C5A880]/15 text-center">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 space-y-8">
            <div>
                <p class="font-script text-3xl text-[#C5A880]">Discover</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">The Best Ingredients</h2>
            </div>

            <div class="relative h-96 rounded-3xl overflow-hidden border border-[#C5A880]/20 shadow-2xl flex items-center justify-center">
                <img src="https://images.unsplash.com/photo-1509358271058-acd22cc93898?auto=format&fit=crop&w=1600&q=80" 
                     alt="Dry Ingredients" class="absolute inset-0 w-full h-full object-cover brightness-50">
                <button @click="videoModalOpen = true" 
                        class="relative z-10 w-20 h-20 rounded-full border-2 border-[#C5A880] flex items-center justify-center text-[#C5A880] hover:scale-110 hover:bg-[#C5A880] hover:text-black transition-all shadow-2xl">
                    <i data-lucide="play" class="w-8 h-8 fill-current ml-1"></i>
                </button>
            </div>
        </div>
    </section>

@endsection
