<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lazzat — The Authentic Restaurant & Cafe</title>
    
    <!-- Google Fonts for Luxury Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@600;700;800&family=Great+Vibes&family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            light: '#E5C07B',
                            DEFAULT: '#C5A880',
                            bright: '#D4AF37',
                            sand: '#D1A568',
                            dark: '#8C6D37',
                        },
                        dark: {
                            base: '#0B0B0B',
                            card: '#141414',
                            cardAlt: '#181818',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'Cinzel', 'serif'],
                        script: ['"Great Vibes"', '"Alex Brush"', 'cursive'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js & Lucide Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background-color: #0B0B0B;
            color: #C2B5A8;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        .font-script {
            font-family: 'Great Vibes', 'Alex Brush', cursive;
        }

        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* Diagonal Luxury Gold Corner Patterns (matching screenshot) */
        .gold-diagonal-lines {
            background-image: repeating-linear-gradient(45deg, rgba(197,168,128,0.2) 0, rgba(197,168,128,0.2) 1.5px, transparent 0, transparent 10px);
        }

        .gold-diagonal-lines-subtle {
            background-image: repeating-linear-gradient(45deg, rgba(197,168,128,0.08) 0, rgba(197,168,128,0.08) 1px, transparent 0, transparent 12px);
        }

        /* Chamfered Cut-Corners (matching design screenshot) */
        .chamfer-top-right {
            clip-path: polygon(0 0, calc(100% - 32px) 0, 100% 32px, 100% 100%, 0 100%);
        }

        .chamfer-bottom-left {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 32px 100%, 0 calc(100% - 32px));
        }

        .gold-underline-btn {
            position: relative;
            display: inline-block;
            padding-bottom: 6px;
        }

        .gold-underline-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #C5A880;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0B0B0B;
        }
        ::-webkit-scrollbar-thumb {
            background: #2D2319;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #C5A880;
        }
    </style>
</head>
<body x-data="luxuryLanding()" x-init="init()" class="antialiased selection:bg-[#C5A880]/30 selection:text-[#FFF]">

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 1. LUXURY NAVBAR (MATCHING SCREENSHOT)                       -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 backdrop-blur-md"
            :class="isScrolled ? 'bg-[#0B0B0B]/95 border-b border-[#C5A880]/20 py-4 shadow-2xl' : 'bg-transparent py-6'">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 flex items-center justify-between">
            
            <!-- Left: Gold Cursive Logo -->
            <a href="{{ route('home') }}" class="group">
                <span class="font-script text-3xl sm:text-4xl text-[#C5A880] tracking-wide group-hover:brightness-125 transition-all">
                    Lazzat
                </span>
            </a>

            <!-- Right: Navigation Menu Links -->
            <nav class="hidden md:flex items-center gap-8 text-[11px] uppercase tracking-[0.25em] font-semibold text-[#A8988D]">
                <a href="#home" class="text-[#C5A880] hover:text-white transition-colors">HOME</a>
                <a href="#about" class="hover:text-[#C5A880] transition-colors">ABOUT US</a>
                <a href="#menu" class="hover:text-[#C5A880] transition-colors">SPECIAL DISH</a>
                <a href="#blog" class="hover:text-[#C5A880] transition-colors">BLOG</a>
                <a href="{{ route('login') }}" class="hover:text-[#C5A880] transition-colors">LOGIN</a>
                <a href="{{ route('pos.index') }}" class="px-3.5 py-1.5 rounded-full border border-[#C5A880]/50 text-[#C5A880] hover:bg-[#C5A880] hover:text-black transition-all">
                    POS
                </a>
            </nav>

            <!-- Mobile Hamburger -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-[#C5A880]">
                <i :data-lucide="mobileMenuOpen ? 'x' : 'menu'" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileMenuOpen" x-cloak x-transition
             class="md:hidden bg-[#111] border-b border-[#C5A880]/20 px-6 py-6 space-y-4">
            <a @click="mobileMenuOpen=false" href="#home" class="block text-xs font-semibold tracking-widest text-[#C5A880]">HOME</a>
            <a @click="mobileMenuOpen=false" href="#about" class="block text-xs font-semibold tracking-widest text-gray-300 hover:text-[#C5A880]">ABOUT US</a>
            <a @click="mobileMenuOpen=false" href="#menu" class="block text-xs font-semibold tracking-widest text-gray-300 hover:text-[#C5A880]">SPECIAL DISH</a>
            <a @click="mobileMenuOpen=false" href="#reservation" class="block text-xs font-semibold tracking-widest text-gray-300 hover:text-[#C5A880]">RESERVATION</a>
            <a @click="mobileMenuOpen=false" href="#blog" class="block text-xs font-semibold tracking-widest text-gray-300 hover:text-[#C5A880]">BLOG</a>
            <a href="{{ route('pos.index') }}" class="block text-xs font-bold text-[#C5A880]">POS TERMINAL</a>
            <a href="{{ route('login') }}" class="block text-xs font-bold text-gray-300">STAFF LOGIN</a>
        </div>
    </header>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 2. HERO SECTION (MATCHING SCREENSHOT)                       -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="home" class="relative min-h-[92vh] pt-36 pb-20 flex items-center bg-[#0B0B0B] overflow-hidden">
        
        <div class="max-w-7xl mx-auto px-6 sm:px-10 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left 50% Text Column -->
                <div class="lg:col-span-6 space-y-6 text-left">
                    
                    <p class="font-script text-3xl sm:text-4xl text-[#C5A880]">
                        Welcome to Lazzat
                    </p>
                    
                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.18] tracking-tight">
                        The Authentic <br>
                        Restaurant & Cafe
                    </h1>

                    <p class="text-xs sm:text-sm text-[#8C7D73] max-w-md font-light leading-relaxed">
                        Experience royal culinary craftsmanship with our timeless gourmet delicacies, signature dum biryanis, sizzling kebabs, and enchanting fine dining ambiance.
                    </p>

                    <!-- Underline Action Link (Exact match to screenshot) -->
                    <div class="pt-4">
                        <a href="#menu" class="gold-underline-btn text-xs uppercase tracking-[0.25em] font-bold text-white hover:text-[#C5A880] transition-colors">
                            EXPLORE MENU
                        </a>
                    </div>
                </div>

                <!-- Right 50% Food Feast Visual Collage (Exact match to screenshot) -->
                <div class="lg:col-span-6">
                    <div class="relative w-full max-w-lg mx-auto bg-[#090909] rounded-3xl p-3 border border-[#C5A880]/30 shadow-2xl overflow-hidden">
                        
                        <!-- Food Dish Grid Collage -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- Dish 1: Butter Chicken Curry -->
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#C5A880]/20">
                                <img src="https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?auto=format&fit=crop&w=600&q=80" 
                                     alt="Curry Platter" 
                                     class="w-full h-44 sm:h-52 object-cover hover:scale-105 transition-transform duration-500">
                            </div>
                            
                            <!-- Dish 2: Royal Saffron Biryani -->
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#C5A880]/20">
                                <img src="https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=600&q=80" 
                                     alt="Dum Biryani" 
                                     class="w-full h-44 sm:h-52 object-cover hover:scale-105 transition-transform duration-500">
                            </div>

                            <!-- Dish 3: Creamy Starter -->
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#C5A880]/20">
                                <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=600&q=80" 
                                     alt="Dessert" 
                                     class="w-full h-44 sm:h-52 object-cover hover:scale-105 transition-transform duration-500">
                            </div>

                            <!-- Dish 4: Fresh Flatbread Naan -->
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#C5A880]/20">
                                <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=600&q=80" 
                                     alt="Naan Bread" 
                                     class="w-full h-44 sm:h-52 object-cover hover:scale-105 transition-transform duration-500">
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 3. SPECIALIST CUISINE (4 CARDS WITH DIAGONAL CORNER LINES)   -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="py-24 bg-[#0E0E0E] relative border-t border-[#C5A880]/15">
        
        <!-- Top Right Diagonal Pattern -->
        <div class="absolute top-4 right-4 w-28 h-28 gold-diagonal-lines opacity-40 pointer-events-none hidden md:block"></div>

        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            
            <div class="text-center space-y-1 mb-16">
                <p class="font-script text-3xl text-[#C5A880]">Discover</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Our Specialist Cuisine
                </h2>
            </div>

            <!-- 4 Specialist Cards Grid (2x2 on tablet, 4x1 on desktop) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Card 1 -->
                <div class="bg-[#141414] p-7 rounded-2xl border border-[#C5A880]/25 relative group hover:border-[#C5A880] transition-all">
                    <div class="absolute top-0 right-0 w-16 h-16 gold-diagonal-lines opacity-20 group-hover:opacity-50 transition-opacity"></div>
                    
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 border border-[#C5A880]/30"
                         style="background: rgba(197, 168, 128, 0.08);">
                        <i data-lucide="utensils" class="w-5 h-5 text-[#C5A880]"></i>
                    </div>

                    <h3 class="font-serif text-base font-bold text-white mb-2">
                        Middle East Food
                    </h3>
                    <p class="text-xs text-[#8C7D73] leading-relaxed">
                        Authentic arabic mandi, tender kebabs & fragrant biryanis infused with saffron & spices.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#141414] p-7 rounded-2xl border border-[#C5A880]/25 relative group hover:border-[#C5A880] transition-all">
                    <div class="absolute top-0 right-0 w-16 h-16 gold-diagonal-lines opacity-20 group-hover:opacity-50 transition-opacity"></div>
                    
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 border border-[#C5A880]/30"
                         style="background: rgba(197, 168, 128, 0.08);">
                        <i data-lucide="soup" class="w-5 h-5 text-[#C5A880]"></i>
                    </div>

                    <h3 class="font-serif text-base font-bold text-white mb-2">
                        Gourmet Food
                    </h3>
                    <p class="text-xs text-[#8C7D73] leading-relaxed">
                        Masterfully prepared gourmet recipes crafted by award-winning international chefs.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#141414] p-7 rounded-2xl border border-[#C5A880]/25 relative group hover:border-[#C5A880] transition-all">
                    <div class="absolute top-0 right-0 w-16 h-16 gold-diagonal-lines opacity-20 group-hover:opacity-50 transition-opacity"></div>
                    
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 border border-[#C5A880]/30"
                         style="background: rgba(197, 168, 128, 0.08);">
                        <i data-lucide="chef-hat" class="w-5 h-5 text-[#C5A880]"></i>
                    </div>

                    <h3 class="font-serif text-base font-bold text-white mb-2">
                        Delicious Food
                    </h3>
                    <p class="text-xs text-[#8C7D73] leading-relaxed">
                        Sizzling grills, slow-cooked royal delicacies & hand-crafted artisan desserts.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="bg-[#141414] p-7 rounded-2xl border border-[#C5A880]/25 relative group hover:border-[#C5A880] transition-all">
                    <div class="absolute top-0 right-0 w-16 h-16 gold-diagonal-lines opacity-20 group-hover:opacity-50 transition-opacity"></div>
                    
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 border border-[#C5A880]/30"
                         style="background: rgba(197, 168, 128, 0.08);">
                        <i data-lucide="sparkles" class="w-5 h-5 text-[#C5A880]"></i>
                    </div>

                    <h3 class="font-serif text-base font-bold text-white mb-2">
                        Fresh Natural
                    </h3>
                    <p class="text-xs text-[#8C7D73] leading-relaxed">
                        100% farm-fresh, organic ingredients and pure herbs sourced daily from local farmers.
                    </p>
                </div>

            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 4. ABOUT US / OUR STORY (CHAMFERED WHITE CARD CONTAINER)     -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="about" class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: 2 Offset Interior Dining Photos -->
                <div class="lg:col-span-6 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80" 
                             alt="Restaurant Dining Room" 
                             class="w-full h-80 object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl mt-8">
                        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=600&q=80" 
                             alt="Grand Lobby Setting" 
                             class="w-full h-80 object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                </div>

                <!-- Right: Chamfered White Card (Exact match to screenshot) -->
                <div class="lg:col-span-6">
                    <div class="bg-white text-[#1A1A1A] p-8 sm:p-12 chamfer-top-right shadow-2xl relative">
                        
                        <p class="font-script text-2xl text-[#C5A880] mb-1">
                            About Us
                        </p>

                        <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#111111] mb-4 leading-tight">
                            Our Story Make History
                        </h2>

                        <p class="text-xs sm:text-sm text-[#665D56] leading-relaxed mb-4">
                            Founded with a passion for preserving imperial gastronomy, Lazzat combines time-honored royal cooking methods with contemporary culinary finesse.
                        </p>

                        <p class="text-xs sm:text-sm text-[#665D56] leading-relaxed mb-8">
                            Every marinade is aged to perfection, every biryani pot is slow-cooked over low embers, and every guest is treated like royalty with our warm hospitality and bespoke dining reservations.
                        </p>

                        <a href="#reservation" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#C5A880] hover:text-[#111] transition-colors border-b border-[#C5A880] pb-0.5">
                            <span>Discover More</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 5. STATS COUNTER BAR (MATCHING SCREENSHOT)                  -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="py-14 bg-[#111111] border-t border-b border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                
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
                    <p class="text-[11px] uppercase tracking-widest text-[#8C7D73]">Food Menus</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 6. SPECIAL DISH & BEST RECOMMENDATION MENU                  -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="menu" class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            
            <div class="text-center space-y-1 mb-16">
                <p class="font-script text-3xl text-[#C5A880]">Special Dish</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Best Recommendation Menu
                </h2>
            </div>

            <!-- Top 3 Food Cards with Bottom Chamfered Tag -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                
                <!-- Dish 1: Salad -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80" 
                             alt="Greek Salad" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <!-- Bottom White Chamfered Badge -->
                    <div class="p-6 bg-white text-[#1A1A1A] chamfer-top-right -mt-6 relative z-10 m-3 rounded-xl shadow-2xl">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-serif font-bold text-sm text-[#111]">Royal Greek Salad</h3>
                            <span class="font-bold text-xs text-[#C5A880]">$12.00</span>
                        </div>
                        <p class="text-[11px] text-[#665D56] mb-3">Fresh feta cheese, Kalamata olives & crisp romaine</p>
                        <a href="#reservation" class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-[#C5A880]">
                            <span>Order Dish</span>
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>

                <!-- Dish 2: Pasta -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=600&q=80" 
                             alt="Fettuccine Pasta" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6 bg-white text-[#1A1A1A] chamfer-top-right -mt-6 relative z-10 m-3 rounded-xl shadow-2xl">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-serif font-bold text-sm text-[#111]">Fettuccine Alfredo</h3>
                            <span class="font-bold text-xs text-[#C5A880]">$18.00</span>
                        </div>
                        <p class="text-[11px] text-[#665D56] mb-3">Rich parmesan cream sauce, truffle oil & herbs</p>
                        <a href="#reservation" class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-[#C5A880]">
                            <span>Order Dish</span>
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>

                <!-- Dish 3: Pancakes -->
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=600&q=80" 
                             alt="Pancakes" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6 bg-white text-[#1A1A1A] chamfer-top-right -mt-6 relative z-10 m-3 rounded-xl shadow-2xl">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-serif font-bold text-sm text-[#111]">Berry Pancakes</h3>
                            <span class="font-bold text-xs text-[#C5A880]">$14.00</span>
                        </div>
                        <p class="text-[11px] text-[#665D56] mb-3">Fluffy buttermilk stack, raspberry glaze & walnuts</p>
                        <a href="#reservation" class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-[#C5A880]">
                            <span>Order Dish</span>
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bottom 2-Column: Left Dotted Menu List, Right Chicken Dish Photo -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-[#111111] p-6 sm:p-10 rounded-3xl border border-[#C5A880]/20 relative">
                
                <!-- Corner Diagonal Accents -->
                <div class="absolute top-2 left-2 w-20 h-20 gold-diagonal-lines opacity-20 hidden md:block"></div>

                <!-- Left: White Card with Dotted Prices -->
                <div class="lg:col-span-6 bg-white text-[#1A1A1A] p-6 sm:p-8 rounded-2xl shadow-2xl space-y-4">
                    
                    <div class="border-b border-gray-100 pb-2">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Greek Salad</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$12</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Fresh lettuce, cucumber, kalamata olives & feta</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Chicken Spring Soup</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$15</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Slow-simmered chicken broth with fragrant herbs</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Salmon Salad</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$18</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Smoked Norwegian salmon slices over tossed greens</p>
                    </div>

                    <div class="border-b border-gray-100 pb-2">
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Classic Roast Chicken</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$22</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Oven roasted quarter chicken with herb butter glaze</p>
                    </div>

                    <div>
                        <div class="flex items-baseline justify-between gap-2">
                            <span class="font-serif font-bold text-xs sm:text-sm text-[#111]">Bitter Ball</span>
                            <span class="flex-1 border-b border-dotted border-gray-300 mx-2"></span>
                            <span class="font-bold text-xs text-[#C5A880]">$09</span>
                        </div>
                        <p class="text-[10px] text-gray-500">Crispy dutch-style savoury croquettes with mustard dip</p>
                    </div>

                </div>

                <!-- Right: Platter Photo -->
                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80" 
                             alt="Signature Meat Platter" 
                             class="w-full h-80 sm:h-96 object-cover">
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 7. TABLE RESERVATION (CHAMFERED GOLD/SAND CARD)             -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="reservation" class="py-24 bg-[#090909] relative border-t border-[#C5A880]/15">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: Text Info -->
                <div class="lg:col-span-5 space-y-4 text-left">
                    <p class="font-script text-3xl text-[#C5A880]">Reservation</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                        Feel Happiness by Making a Reservation
                    </h2>
                    <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed">
                        Reserve your royal dining table in advance for birthdays, family gatherings, corporate dinners, or intimate romantic evenings.
                    </p>
                </div>

                <!-- Right: Chamfered Sand / Gold Card (Exact match to screenshot) -->
                <div class="lg:col-span-7">
                    <div class="bg-[#D1A568] p-8 sm:p-10 chamfer-top-right shadow-2xl text-[#1A1105]">
                        
                        <div class="text-center mb-6">
                            <h3 class="font-serif text-2xl sm:text-3xl font-bold text-white">
                                Book Table
                            </h3>
                        </div>

                        <form @submit.prevent="submitReservation()" class="space-y-3.5">
                            
                            <!-- Name & Phone -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <input type="text" x-model="form.customer_name" required placeholder="Name"
                                           class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none">
                                </div>
                                <div>
                                    <input type="text" x-model="form.customer_phone" required placeholder="Phone / Email"
                                           class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none">
                                </div>
                            </div>

                            <!-- Date & Time Slot -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <input type="date" x-model="form.reservation_date" required min="{{ date('Y-m-d') }}"
                                           class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] focus:outline-none">
                                </div>
                                <div>
                                    <select x-model="form.reservation_time" required
                                            class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] focus:outline-none">
                                        <option value="01:00 PM">01:00 PM (Lunch)</option>
                                        <option value="02:00 PM">02:00 PM (Lunch)</option>
                                        <option value="07:30 PM">07:30 PM (Dinner)</option>
                                        <option value="08:30 PM" selected>08:30 PM (Prime Dinner)</option>
                                        <option value="09:30 PM">09:30 PM (Late Dinner)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Persons & Table -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <select x-model.number="form.guest_count" required
                                            class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] focus:outline-none">
                                        <option value="1">1 Person</option>
                                        <option value="2" selected>2 Persons</option>
                                        <option value="4">4 Persons</option>
                                        <option value="6">6 Persons</option>
                                        <option value="8">8 Persons</option>
                                        <option value="12">12+ Persons</option>
                                    </select>
                                </div>
                                <div>
                                    <select x-model="form.table_id"
                                            class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] focus:outline-none">
                                        <option value="">Select Table</option>
                                        @foreach($tables as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->floor_name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button type="submit" :disabled="isSubmitting"
                                        class="w-full py-3.5 rounded bg-white hover:bg-gray-100 text-[#111] font-bold text-xs uppercase tracking-widest transition-all shadow-xl active:scale-98">
                                    <span x-text="isSubmitting ? 'RESERVING...' : 'BOOK A TABLE'"></span>
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 8. CUSTOMER REVIEWS / TESTIMONIALS (MATCHING SCREENSHOT)    -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="py-20 bg-[#0E0E0E] border-t border-[#C5A880]/15">
        <div class="max-w-3xl mx-auto px-6 text-center space-y-4">
            
            <p class="font-script text-3xl text-[#C5A880]">Testimonials</p>
            <h2 class="font-serif text-3xl font-bold text-white tracking-tight">
                Customer Reviews
            </h2>

            <div class="pt-6 relative">
                <!-- Navigation Arrows Left & Right -->
                <div class="flex items-center justify-between">
                    <button @click="prevTestimonial()" class="text-[#C5A880] hover:text-white transition-colors p-2">
                        <i data-lucide="chevron-left" class="w-6 h-6"></i>
                    </button>

                    <!-- Center Review Quote -->
                    <div class="max-w-xl mx-auto px-4">
                        <p class="text-xs sm:text-sm text-[#A8988D] italic leading-relaxed"
                           x-text="testimonials[activeTestimonial].quote"></p>
                        
                        <!-- Big Quotation Mark -->
                        <div class="text-[#C5A880] text-4xl font-serif mt-3 mb-1">“</div>
                        
                        <p class="font-bold text-xs uppercase tracking-wider text-white" 
                           x-text="testimonials[activeTestimonial].name"></p>
                    </div>

                    <button @click="nextTestimonial()" class="text-[#C5A880] hover:text-white transition-colors p-2">
                        <i data-lucide="chevron-right" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 9. VIDEO AMBIENCE BANNER WITH GOLD PLAY BUTTON              -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="relative h-80 sm:h-96 flex items-center justify-center overflow-hidden border-t border-b border-[#C5A880]/20">
        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=1600&q=80" 
             alt="Ambience" 
             class="absolute inset-0 w-full h-full object-cover brightness-50">
        
        <div class="relative z-10 text-center space-y-3 px-4">
            <button @click="videoModalOpen = true"
                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-[#C5A880] flex items-center justify-center text-[#C5A880] hover:scale-110 hover:bg-[#C5A880] hover:text-black transition-all shadow-[0_0_30px_rgba(197,168,128,0.4)] mx-auto">
                <i data-lucide="play" class="w-6 h-6 fill-current ml-1"></i>
            </button>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 10. BLOG / TIPS & TRICKS (3 CARDS MATCHING SCREENSHOT)      -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="blog" class="py-24 bg-[#0B0B0B]">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            
            <div class="text-center space-y-1 mb-16">
                <p class="font-script text-3xl text-[#C5A880]">Blog Post</p>
                <h2 class="font-serif text-3xl font-bold text-white tracking-tight">
                    Tips & Tricks
                </h2>
            </div>

            <!-- 3 Blog Cards (Salad, Steak, Pasta) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($blogs as $blog)
                <div class="bg-[#141414] rounded-2xl border border-[#C5A880]/20 overflow-hidden group hover:border-[#C5A880] transition-all">
                    
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <!-- Chamfered Top-Right Content Box -->
                    <div class="p-5 chamfer-top-right bg-[#181818] -mt-4 relative z-10 m-2.5 rounded-xl border border-[#C5A880]/15">
                        <h3 class="font-serif text-sm font-bold text-white mb-2 line-clamp-1">
                            {{ $blog['title'] }}
                        </h3>
                        <p class="text-[11px] text-[#8C7D73] line-clamp-2 mb-4 leading-relaxed">
                            {{ $blog['excerpt'] }}
                        </p>
                        <div class="flex items-center gap-2 text-[10px] text-[#C5A880]">
                            <div class="w-5 h-5 rounded-full bg-[#C5A880]/20 flex items-center justify-center font-bold">
                                {{ substr($blog['author'], 0, 1) }}
                            </div>
                            <span>By {{ $blog['author'] }}</span>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 11. SUBSCRIBE NEWSLETTER (MATCHING SCREENSHOT)              -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="py-14 bg-[#111111] border-t border-b border-[#C5A880]/15 relative">
        <div class="max-w-4xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left space-y-0.5">
                <p class="font-script text-2xl text-[#C5A880]">Stay In Touch</p>
                <h3 class="font-serif text-2xl font-bold text-white">Subscribe Now !</h3>
            </div>
            
            <form @submit.prevent="alert('Thank you for subscribing to Lazzat VIP Club!')" 
                  class="flex w-full md:w-auto flex-1 max-w-md gap-2">
                <input type="email" required placeholder="Email Address..."
                       class="flex-1 px-4 py-2.5 rounded bg-[#181818] border border-[#C5A880]/30 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-[#C5A880]">
                <button type="submit" 
                        class="px-6 py-2.5 rounded bg-[#D1A568] hover:bg-[#C5A880] text-black font-bold text-xs uppercase tracking-wider transition-all shrink-0">
                    SUBSCRIBE
                </button>
            </form>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 12. FOOTER (MATCHING SCREENSHOT)                            -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <footer class="bg-[#080808] text-[#8C7D73] pt-14 pb-8">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            
            <div class="space-y-3">
                <a href="{{ route('home') }}" class="font-script text-3xl text-[#C5A880] block">
                    Lazzat
                </a>
                <p class="text-xs leading-relaxed text-[#6B5F57]">
                    Where culinary royalty meets contemporary gourmet excellence. Experience unmatched flavours crafted with passionate artistry.
                </p>
                <div class="flex items-center gap-3 pt-1">
                    <a href="#" class="w-7 h-7 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] hover:border-[#C5A880]"><i data-lucide="facebook" class="w-3.5 h-3.5"></i></a>
                    <a href="#" class="w-7 h-7 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] hover:border-[#C5A880]"><i data-lucide="instagram" class="w-3.5 h-3.5"></i></a>
                    <a href="#" class="w-7 h-7 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] hover:border-[#C5A880]"><i data-lucide="twitter" class="w-3.5 h-3.5"></i></a>
                </div>
            </div>

            <div class="space-y-2">
                <h4 class="font-serif text-xs font-bold uppercase tracking-widest text-white">MENU</h4>
                <ul class="space-y-1.5 text-xs">
                    <li><a href="#home" class="hover:text-[#C5A880]">Home</a></li>
                    <li><a href="#about" class="hover:text-[#C5A880]">About Us</a></li>
                    <li><a href="#menu" class="hover:text-[#C5A880]">Special Dish</a></li>
                    <li><a href="#reservation" class="hover:text-[#C5A880]">Reservation</a></li>
                </ul>
            </div>

            <div class="space-y-2">
                <h4 class="font-serif text-xs font-bold uppercase tracking-widest text-white">HOURS</h4>
                <ul class="space-y-1.5 text-xs">
                    <li>Monday - Sunday</li>
                    <li><strong>11:00 AM - 11:30 PM</strong></li>
                    <li><a href="#reservation" class="text-[#C5A880] hover:underline">Book A Table</a></li>
                </ul>
            </div>

            <div class="space-y-2">
                <h4 class="font-serif text-xs font-bold uppercase tracking-widest text-white">CONTACT</h4>
                <ul class="space-y-1.5 text-xs">
                    <li>Gulshan Avenue, Dhaka, Bangladesh</li>
                    <li>+880 1700-000000</li>
                    <li>info@lazzatdine.com</li>
                </ul>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-6 sm:px-10 pt-6 border-t border-[#C5A880]/10 text-center text-[10px] text-[#554B44]">
            <p>Copyright © {{ date('Y') }} Lazzat. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- MODAL: RESERVATION CONFIRMATION -->
    <div x-show="reservationSuccessModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.outside="reservationSuccessModal = false"
             class="w-full max-w-md bg-[#141414] rounded-3xl p-6 sm:p-8 border border-[#C5A880] text-center space-y-4 shadow-2xl relative">
            <div class="w-14 h-14 rounded-full bg-[#D1A568] mx-auto flex items-center justify-center text-black shadow-lg">
                <i data-lucide="check" class="w-7 h-7 stroke-[3]"></i>
            </div>
            
            <h3 class="font-serif text-2xl font-bold text-white">Table Reserved Successfully!</h3>
            
            <div class="bg-[#1C1C1C] p-4 rounded-2xl border border-[#C5A880]/20 text-xs text-left space-y-1 text-[#E0D4CF]">
                <p>Guest Name: <strong class="text-white" x-text="confirmedData?.customer_name"></strong></p>
                <p>Guests: <strong class="text-[#C5A880]" x-text="confirmedData?.guest_count + ' Persons'"></strong></p>
                <p>Date & Time: <strong class="text-white" x-text="confirmedData?.date + ' at ' + confirmedData?.time"></strong></p>
                <p>Assigned Area: <strong class="text-[#C5A880]" x-text="confirmedData?.table_name"></strong></p>
            </div>

            <button @click="reservationSuccessModal = false"
                    class="w-full py-3 rounded bg-[#D1A568] text-black font-bold text-xs uppercase tracking-wider hover:brightness-110 transition-all">
                Close & Continue
            </button>
        </div>
    </div>

    <!-- MODAL: VIDEO PREVIEW -->
    <div x-show="videoModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md">
        <div @click.outside="videoModalOpen = false" class="w-full max-w-3xl bg-[#111] rounded-3xl overflow-hidden border border-[#C5A880] relative">
            <div class="p-3 border-b border-[#C5A880]/20 flex justify-between items-center bg-[#181818]">
                <span class="font-serif text-xs text-[#C5A880] font-bold">Restaurant Ambience Video</span>
                <button @click="videoModalOpen = false" class="text-gray-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="aspect-video w-full">
                <iframe class="w-full h-full" src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=0" title="Restaurant Tour" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <script>
    function luxuryLanding() {
        return {
            isScrolled: false,
            mobileMenuOpen: false,
            activeTestimonial: 0,
            testimonials: @json($testimonials),
            videoModalOpen: false,
            reservationSuccessModal: false,
            isSubmitting: false,
            confirmedData: null,
            form: {
                customer_name: '',
                customer_phone: '',
                customer_email: '',
                guest_count: 2,
                reservation_date: '{{ date('Y-m-d') }}',
                reservation_time: '08:30 PM',
                table_id: '',
                special_requests: ''
            },

            init() {
                window.addEventListener('scroll', () => {
                    this.isScrolled = window.scrollY > 40;
                });
                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                });
            },

            nextTestimonial() {
                this.activeTestimonial = (this.activeTestimonial + 1) % this.testimonials.length;
            },

            prevTestimonial() {
                this.activeTestimonial = (this.activeTestimonial - 1 + this.testimonials.length) % this.testimonials.length;
            },

            async submitReservation() {
                if (!this.form.customer_name || !this.form.customer_phone) return;
                this.isSubmitting = true;
                try {
                    const res = await fetch('{{ route('reservation.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.form)
                    });
                    const data = await res.json();
                    if (res.ok && data.success) {
                        this.confirmedData = data.reservation;
                        this.reservationSuccessModal = true;
                        this.form = {
                            customer_name: '',
                            customer_phone: '',
                            customer_email: '',
                            guest_count: 2,
                            reservation_date: '{{ date('Y-m-d') }}',
                            reservation_time: '08:30 PM',
                            table_id: '',
                            special_requests: ''
                        };
                    } else {
                        alert(data.message || 'Error making reservation');
                    }
                } catch (e) {
                    alert('Error: ' + e.message);
                } finally {
                    this.isSubmitting = false;
                }
            }
        };
    }
    </script>
</body>
</html>
