<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $branch->restaurant_name ?? "Lazzat" }} — The Authentic Restaurant & Cafe</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@600;700;800;900&family=Great+Vibes&family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN for 100% guaranteed rendering -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50: '#FAF6EF',
                            100: '#F5EEDF',
                            200: '#EADBBF',
                            300: '#DFC79F',
                            400: '#D4B47F',
                            500: '#C5A880',
                            600: '#D4AF37',
                            700: '#B8922A',
                            800: '#8C6D37',
                            900: '#5C441E',
                        },
                        dark: {
                            900: '#080808',
                            800: '#0E0E0E',
                            700: '#141414',
                            600: '#1A1A1A',
                            500: '#222222',
                        }
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'Cinzel', 'serif'],
                        script: ['"Great Vibes"', '"Alex Brush"', 'cursive'],
                        sans: ['Inter', '"Hind Siliguri"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js & Lucide Icons -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body {
            background-color: #080808;
            color: #E2D9D2;
            font-family: 'Inter', 'Hind Siliguri', sans-serif;
            overflow-x: hidden;
        }

        .gold-gradient-text {
            background: linear-gradient(135deg, #FFF0D0 0%, #D4AF37 50%, #A47922 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gold-gradient-bg {
            background: linear-gradient(135deg, #D4AF37 0%, #C5A880 50%, #8C6D37 100%);
        }

        .gold-border {
            border-color: rgba(212, 175, 55, 0.25);
        }

        .gold-border-glow:hover {
            border-color: rgba(212, 175, 55, 0.7);
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.15);
        }

        /* Chamfered Cut-Corners (matching design screenshot) */
        .chamfer-top-right {
            clip-path: polygon(0 0, calc(100% - 36px) 0, 100% 36px, 100% 100%, 0 100%);
        }

        .chamfer-bottom-left {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 36px 100%, 0 calc(100% - 36px));
        }

        /* Diagonal Luxury Gold Pattern */
        .diagonal-pattern {
            background-image: repeating-linear-gradient(45deg, rgba(212,175,55,0.05) 0, rgba(212,175,55,0.05) 1px, transparent 0, transparent 12px);
        }
    </style>
</head>
<body x-data="luxuryLanding()" x-init="init()" class="antialiased selection:bg-[#D4AF37]/30 selection:text-[#FFF0D0]">

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 1. NAVBAR                                                    -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 backdrop-blur-md border-b"
            :class="isScrolled ? 'bg-[#080808]/95 border-[#D4AF37]/20 py-3 shadow-2xl' : 'bg-[#080808]/80 border-[#D4AF37]/10 py-5'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <span class="font-script text-3xl sm:text-4xl text-[#D4AF37] group-hover:scale-105 transition-transform">
                    {{ $branch->restaurant_name ?? "Lazzat" }}
                </span>
            </a>

            <!-- Desktop Nav Links (Centered) -->
            <nav class="hidden lg:flex items-center gap-8 text-xs uppercase tracking-[0.2em] font-medium text-[#C2B4AA]">
                <a href="#home" class="hover:text-[#D4AF37] transition-colors py-1">HOME</a>
                <a href="#about" class="hover:text-[#D4AF37] transition-colors py-1">ABOUT US</a>
                <a href="#menu" class="hover:text-[#D4AF37] transition-colors py-1">SPECIAL DISH</a>
                <a href="#reservation" class="hover:text-[#D4AF37] transition-colors py-1">RESERVATION</a>
                <a href="#blog" class="hover:text-[#D4AF37] transition-colors py-1">BLOG</a>
                <a href="{{ route('pos.index') }}" class="hover:text-[#D4AF37] transition-colors py-1 text-[#D4AF37]">POS</a>
                <a href="{{ route('login') }}" class="hover:text-[#D4AF37] transition-colors py-1">LOGIN</a>
            </nav>

            <!-- Action: Book Table -->
            <div class="flex items-center gap-3">
                <a href="#reservation"
                   class="gold-gradient-bg text-[#080808] px-5 py-2.5 rounded-full text-xs font-extrabold uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all shadow-lg flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    <span>BOOK TABLE</span>
                </a>

                <!-- Mobile Hamburger -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-[#D4AF37] focus:outline-none">
                    <i :data-lucide="mobileMenuOpen ? 'x' : 'menu'" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileMenuOpen" x-cloak x-transition
             class="lg:hidden bg-[#0E0E0E] border-b border-[#D4AF37]/20 px-6 py-6 space-y-4">
            <a @click="mobileMenuOpen=false" href="#home" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">HOME</a>
            <a @click="mobileMenuOpen=false" href="#about" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">ABOUT US</a>
            <a @click="mobileMenuOpen=false" href="#menu" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">SPECIAL DISH & MENU</a>
            <a @click="mobileMenuOpen=false" href="#reservation" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">RESERVATION</a>
            <a @click="mobileMenuOpen=false" href="#blog" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">BLOG</a>
            <a href="{{ route('pos.index') }}" class="block text-sm font-bold text-[#D4AF37]">POS BILLING TERMINAL</a>
            <a href="{{ route('login') }}" class="block text-sm font-bold text-gray-300">STAFF / ADMIN LOGIN</a>
        </div>
    </header>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 2. HERO SECTION (2 COLUMNS: LEFT TEXT, RIGHT FOOD COLLAGE)  -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="home" class="relative min-h-[90vh] pt-32 sm:pt-36 pb-20 flex items-center diagonal-pattern">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left 50% Text Column (Matching Screenshot Left side) -->
                <div class="lg:col-span-6 space-y-6 text-left">
                    <p class="font-script text-3xl sm:text-4xl md:text-5xl text-[#D4AF37] leading-tight">
                        Welcome to {{ $branch->restaurant_name ?? "Lazzat" }}
                    </p>
                    
                    <h1 class="font-serif text-4xl sm:text-5xl md:text-6xl font-bold tracking-tight text-white leading-[1.15]">
                        The Authentic <br>
                        <span class="text-[#D4AF37]">Restaurant & Cafe</span>
                    </h1>

                    <p class="text-xs sm:text-sm text-[#A8988D] max-w-lg font-light leading-relaxed">
                        Experience royal culinary craftsmanship with our timeless gourmet delicacies, signature dum biryanis, sizzling kebabs, and enchanting fine dining ambiance.
                    </p>

                    <!-- CTA Button (Underline / Gold Border style matching screenshot) -->
                    <div class="pt-4 flex items-center gap-5">
                        <a href="#menu"
                           class="inline-block px-8 py-3 rounded-full gold-gradient-bg text-[#080808] font-black text-xs uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all shadow-xl">
                            EXPLORE MENU
                        </a>
                        <a href="#reservation"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-[#D4AF37]/40 hover:border-[#D4AF37] text-white hover:text-[#D4AF37] text-xs font-bold uppercase tracking-wider transition-all">
                            <span>BOOK A TABLE</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>

                    <div class="pt-6 border-t border-[#D4AF37]/15 flex items-center gap-8 text-xs text-[#8E8075]">
                        <span class="flex items-center gap-1.5"><i data-lucide="check-circle" class="w-4 h-4 text-[#D4AF37]"></i> 100% Halal & Fresh</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="shield-check" class="w-4 h-4 text-[#D4AF37]"></i> NBR Mushak 6.3 Compliant</span>
                    </div>
                </div>

                <!-- Right 50% Image Column (Food Dish Collage on Dark Stone Texture) -->
                <div class="lg:col-span-6">
                    <div class="relative w-full max-w-lg mx-auto bg-[#101010] p-4 rounded-3xl border border-[#D4AF37]/30 shadow-2xl overflow-hidden">
                        
                        <!-- Food Dish Grid Collage -->
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#D4AF37]/20">
                                <img src="https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=600&q=80" 
                                     alt="Dum Biryani" 
                                     class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#D4AF37]/20">
                                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80" 
                                     alt="Royal Kebabs" 
                                     class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#D4AF37]/20">
                                <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=600&q=80" 
                                     alt="Starters" 
                                     class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="rounded-2xl overflow-hidden shadow-lg border border-[#D4AF37]/20">
                                <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=600&q=80" 
                                     alt="Desserts" 
                                     class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500">
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 3. SPECIALIST CUISINES (4 FEATURE CARDS)                    -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="py-20 bg-[#0B0B0B] border-t border-b border-[#D4AF37]/15">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center space-y-1.5 mb-14">
                <p class="font-script text-3xl text-[#D4AF37]">Discover</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Our Specialist Cuisine
                </h2>
                <div class="w-12 h-0.5 mx-auto gold-gradient-bg mt-2"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($specialistCuisines as $cuisine)
                <div class="bg-[#121212] p-7 rounded-2xl border gold-border gold-border-glow transition-all duration-300 relative group overflow-hidden">
                    <div class="absolute top-0 right-0 w-20 h-20 diagonal-pattern opacity-30 group-hover:opacity-70 transition-opacity pointer-events-none"></div>

                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 border border-[#D4AF37]/30 group-hover:border-[#D4AF37] transition-colors"
                         style="background: rgba(212, 175, 55, 0.08);">
                        <i data-lucide="{{ $cuisine['icon'] }}" class="w-6 h-6 text-[#D4AF37]"></i>
                    </div>

                    <h3 class="font-serif text-base font-bold text-white mb-2 group-hover:text-[#D4AF37] transition-colors">
                        {{ $cuisine['title'] }}
                    </h3>

                    <p class="text-xs text-[#9E8C85] leading-relaxed">
                        {{ $cuisine['description'] }}
                    </p>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 4. ABOUT US / OUR STORY (CHAMFERED WHITE CARD)              -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="about" class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: 2 Offset Interior Dining Photos -->
                <div class="lg:col-span-6 grid grid-cols-2 gap-4">
                    <div class="rounded-3xl overflow-hidden border border-[#D4AF37]/30 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80" 
                             alt="Luxury Restaurant" 
                             class="w-full h-80 object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="rounded-3xl overflow-hidden border border-[#D4AF37]/30 shadow-2xl mt-8">
                        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=600&q=80" 
                             alt="Table Setting" 
                             class="w-full h-80 object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                </div>

                <!-- Right: Chamfered White Card (matching design screenshot) -->
                <div class="lg:col-span-6">
                    <div class="bg-[#F8F5F2] text-[#1A1A1A] p-8 sm:p-12 chamfer-top-right shadow-2xl relative border-l-4 border-[#D4AF37]">
                        
                        <span class="text-xs font-extrabold uppercase tracking-[0.25em] text-[#B8922A] block mb-2">
                            About Us
                        </span>

                        <h2 class="font-serif text-3xl sm:text-4xl font-black text-[#111111] mb-4 leading-tight">
                            Our Story Make History
                        </h2>

                        <p class="text-xs sm:text-sm text-[#554D47] leading-relaxed mb-4">
                            Founded with a passion for preserving imperial gastronomy, {{ $branch->restaurant_name ?? "Lazzat" }} combines time-honored royal cooking methods with contemporary culinary finesse.
                        </p>

                        <p class="text-xs sm:text-sm text-[#554D47] leading-relaxed mb-8">
                            Every marinade is aged to perfection, every biryani pot is slow-cooked over low embers, and every guest is treated like royalty with our warm hospitality and bespoke dining reservations.
                        </p>

                        <a href="#reservation" 
                           class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-[#8C6D37] hover:text-[#111111] transition-colors border-b-2 border-[#8C6D37] pb-1">
                            <span>Discover More</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 5. STATS COUNTER BAR                                        -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="py-12 bg-[#101010] border-t border-b border-[#D4AF37]/15">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                
                <div class="space-y-1">
                    <p class="font-serif text-4xl sm:text-5xl font-bold gold-gradient-text">{{ $stats['restaurants'] }}</p>
                    <p class="text-xs uppercase tracking-widest text-[#9E8C85]">Restaurants</p>
                </div>

                <div class="space-y-1">
                    <p class="font-serif text-4xl sm:text-5xl font-bold gold-gradient-text">{{ $stats['experience_years'] }}</p>
                    <p class="text-xs uppercase tracking-widest text-[#9E8C85]">Years Experience</p>
                </div>

                <div class="space-y-1">
                    <p class="font-serif text-4xl sm:text-5xl font-bold gold-gradient-text">{{ $stats['awards_won'] }}</p>
                    <p class="text-xs uppercase tracking-widest text-[#9E8C85]">Award Winner</p>
                </div>

                <div class="space-y-1">
                    <p class="font-serif text-4xl sm:text-5xl font-bold gold-gradient-text">{{ $stats['food_menus'] }}</p>
                    <p class="text-xs uppercase tracking-widest text-[#9E8C85]">Food Menus</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 6. SPECIAL DISH & BEST RECOMMENDATION MENU                  -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="menu" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center space-y-1.5 mb-16">
                <p class="font-script text-3xl text-[#D4AF37]">Special Dish</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Best Recommendation Menu
                </h2>
                <div class="w-12 h-0.5 mx-auto gold-gradient-bg mt-2"></div>
            </div>

            <!-- 3 Dish Cards with Chamfered Bottom Badge (matching design screenshot) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                
                <!-- Dish 1 -->
                <div class="bg-[#121212] rounded-2xl border gold-border overflow-hidden group hover:border-[#D4AF37] transition-all">
                    <div class="relative h-60 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80" 
                             alt="Greek Salad" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-5 bg-[#F8F5F2] text-[#1A1A1A] chamfer-top-right -mt-6 relative z-10 m-3 rounded-xl shadow-xl">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-serif font-bold text-sm text-[#111]">Royal Greek Salad</h3>
                            <span class="font-black text-xs text-[#8C6D37]">৳ 320</span>
                        </div>
                        <p class="text-[11px] text-[#6E635C] line-clamp-1 mb-2">Fresh feta cheese, Kalamata olives & crisp romaine</p>
                        <a href="#reservation" class="inline-flex items-center gap-1 text-[10px] font-extrabold text-[#B8922A] uppercase tracking-wider">
                            <span>Order Dish</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>

                <!-- Dish 2 -->
                <div class="bg-[#121212] rounded-2xl border gold-border overflow-hidden group hover:border-[#D4AF37] transition-all">
                    <div class="relative h-60 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=600&q=80" 
                             alt="Fettuccine Alfredo" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-5 bg-[#F8F5F2] text-[#1A1A1A] chamfer-top-right -mt-6 relative z-10 m-3 rounded-xl shadow-xl">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-serif font-bold text-sm text-[#111]">Fettuccine Alfredo</h3>
                            <span class="font-black text-xs text-[#8C6D37]">৳ 490</span>
                        </div>
                        <p class="text-[11px] text-[#6E635C] line-clamp-1 mb-2">Rich parmesan cream sauce, truffle oil & herbs</p>
                        <a href="#reservation" class="inline-flex items-center gap-1 text-[10px] font-extrabold text-[#B8922A] uppercase tracking-wider">
                            <span>Order Dish</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>

                <!-- Dish 3 -->
                <div class="bg-[#121212] rounded-2xl border gold-border overflow-hidden group hover:border-[#D4AF37] transition-all">
                    <div class="relative h-60 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=600&q=80" 
                             alt="Pancakes" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-5 bg-[#F8F5F2] text-[#1A1A1A] chamfer-top-right -mt-6 relative z-10 m-3 rounded-xl shadow-xl">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-serif font-bold text-sm text-[#111]">Velvet Berry Pancakes</h3>
                            <span class="font-black text-xs text-[#8C6D37]">৳ 290</span>
                        </div>
                        <p class="text-[11px] text-[#6E635C] line-clamp-1 mb-2">Fluffy buttermilk stack, raspberry glaze & walnuts</p>
                        <a href="#reservation" class="inline-flex items-center gap-1 text-[10px] font-extrabold text-[#B8922A] uppercase tracking-wider">
                            <span>Order Dish</span>
                            <i data-lucide="chevron-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- 2-Column: Left Dotted Menu List, Right Food Platter Photo -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-[#101010] p-6 sm:p-10 rounded-3xl border gold-border">
                
                <div class="lg:col-span-6 space-y-4">
                    <div class="border-b border-[#D4AF37]/15 pb-2.5">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="font-serif font-bold text-sm text-white">Mutton Kacchi Biryani (Full)</span>
                            <span class="flex-1 border-b border-dotted border-[#D4AF37]/30 mx-2"></span>
                            <span class="font-black text-xs text-[#D4AF37]">৳ 450</span>
                        </div>
                        <p class="text-[10px] text-[#9E8C85]">Tender mutton shank, saffron basmati & roasted potato</p>
                    </div>

                    <div class="border-b border-[#D4AF37]/15 pb-2.5">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="font-serif font-bold text-sm text-white">Old Dhaka Beef Tehari</span>
                            <span class="flex-1 border-b border-dotted border-[#D4AF37]/30 mx-2"></span>
                            <span class="font-black text-xs text-[#D4AF37]">৳ 290</span>
                        </div>
                        <p class="text-[10px] text-[#9E8C85]">Pure mustard oil aroma with succulent diced beef</p>
                    </div>

                    <div class="border-b border-[#D4AF37]/15 pb-2.5">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="font-serif font-bold text-sm text-white">Morog Polao with Biye Bari Roast</span>
                            <span class="flex-1 border-b border-dotted border-[#D4AF37]/30 mx-2"></span>
                            <span class="font-black text-xs text-[#D4AF37]">৳ 320</span>
                        </div>
                        <p class="text-[10px] text-[#9E8C85]">Golden fried quarter chicken with traditional almond gravy</p>
                    </div>

                    <div class="border-b border-[#D4AF37]/15 pb-2.5">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="font-serif font-bold text-sm text-white">Chittagong Beef Kala Bhuna</span>
                            <span class="flex-1 border-b border-dotted border-[#D4AF37]/30 mx-2"></span>
                            <span class="font-black text-xs text-[#D4AF37]">৳ 490</span>
                        </div>
                        <p class="text-[10px] text-[#9E8C85]">Slow-caramelized spicy beef chunks with fried garlic & onions</p>
                    </div>

                    <div>
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="font-serif font-bold text-sm text-white">Butter Garlic Naan</span>
                            <span class="flex-1 border-b border-dotted border-[#D4AF37]/30 mx-2"></span>
                            <span class="font-black text-xs text-[#D4AF37]">৳ 65</span>
                        </div>
                        <p class="text-[10px] text-[#9E8C85]">Clay-oven baked flatbread brushed with organic butter</p>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden border border-[#D4AF37]/30 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80" 
                             alt="Platter" 
                             class="w-full h-80 object-cover">
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 7. TABLE RESERVATION (CHAMFERED GOLD LUXURY FORM)           -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="reservation" class="py-24 relative bg-[#090909] border-t border-[#D4AF37]/15 diagonal-pattern">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: Text Info -->
                <div class="lg:col-span-5 space-y-4 text-left">
                    <p class="font-script text-3xl text-[#D4AF37]">Reservation</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                        Feel Happiness by Making a Reservation
                    </h2>
                    <p class="text-xs sm:text-sm text-[#A8988D] leading-relaxed">
                        Reserve your royal dining table in advance for birthdays, family gatherings, corporate dinners, or intimate romantic evenings. Enjoy VIP hospitality and instant confirmation.
                    </p>
                    <div class="pt-2 text-xs text-[#C5A880] space-y-1.5">
                        <p>Opening Hours: <strong>{{ $branch->opening_hours ?? "11:00 AM - 11:30 PM" }}</strong></p>
                        <p>Hotline: <strong>{{ $branch->phone ?? "+880 1700-000000" }}</strong></p>
                    </div>
                </div>

                <!-- Right: Chamfered Gold Luxury Booking Card (matching design screenshot) -->
                <div class="lg:col-span-7">
                    <div class="bg-gradient-to-br from-[#D4AF37] via-[#C5A880] to-[#8C6D37] p-8 sm:p-10 chamfer-top-right shadow-2xl text-[#1A1105]">
                        
                        <div class="text-center mb-6">
                            <h3 class="font-serif text-2xl sm:text-3xl font-black uppercase tracking-wider text-[#1F1404]">
                                Book Table
                            </h3>
                            <p class="text-[11px] font-bold text-[#453216] mt-0.5">Please fill in details to reserve your table</p>
                        </div>

                        <form @submit.prevent="submitReservation()" class="space-y-3.5">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Your Name *</label>
                                    <input type="text" x-model="form.customer_name" required placeholder="Ex: Ashfaqul Islam"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 border border-black/10 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Mobile Phone *</label>
                                    <input type="text" x-model="form.customer_phone" required placeholder="01711000000"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 border border-black/10 focus:outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Date *</label>
                                    <input type="date" x-model="form.reservation_date" required min="{{ date('Y-m-d') }}"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] border border-black/10 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Time Slot *</label>
                                    <select x-model="form.reservation_time" required
                                            class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] border border-black/10 focus:outline-none">
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
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Number of Guests *</label>
                                    <select x-model.number="form.guest_count" required
                                            class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] border border-black/10 focus:outline-none">
                                        <option value="1">1 Person</option>
                                        <option value="2" selected>2 Persons</option>
                                        <option value="4">4 Persons</option>
                                        <option value="6">6 Persons</option>
                                        <option value="8">8 Persons</option>
                                        <option value="12">12+ Persons</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Table Preference</label>
                                    <select x-model="form.table_id"
                                            class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] border border-black/10 focus:outline-none">
                                        <option value="">Auto Assign Best Table</option>
                                        @foreach($tables as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->floor_name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" :disabled="isSubmitting"
                                        class="w-full py-3.5 rounded-xl bg-[#0E0A04] hover:bg-black text-[#FFF0D0] font-black text-xs uppercase tracking-widest transition-all shadow-xl active:scale-98 flex items-center justify-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-[#D4AF37]"></i>
                                    <span x-text="isSubmitting ? 'Reserving...' : 'BOOK A TABLE'"></span>
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 8. TESTIMONIALS / CUSTOMER REVIEWS                          -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="py-20 bg-[#0C0C0C] border-t border-[#D4AF37]/15">
        <div class="max-w-3xl mx-auto px-4 text-center space-y-4">
            
            <p class="font-script text-3xl text-[#D4AF37]">Testimonials</p>
            <h2 class="font-serif text-3xl font-bold text-white tracking-tight">
                Customer Reviews
            </h2>

            <div class="pt-4">
                <div class="text-[#D4AF37]/25 text-6xl font-serif leading-none">“</div>
                <p class="font-serif italic text-base sm:text-xl text-[#E8DFD8] leading-relaxed max-w-xl mx-auto -mt-4 mb-4"
                   x-text="testimonials[activeTestimonial].quote"></p>

                <p class="font-bold text-xs text-[#D4AF37]" x-text="testimonials[activeTestimonial].name"></p>
                <p class="text-[11px] text-[#8E8075]" x-text="testimonials[activeTestimonial].role"></p>

                <div class="flex items-center justify-center gap-3 pt-4">
                    <button @click="prevTestimonial()" class="w-8 h-8 rounded-full border border-[#D4AF37]/30 flex items-center justify-center text-[#D4AF37] hover:bg-[#D4AF37]/10 transition-colors">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    </button>
                    <span class="text-[11px] text-[#8E8075]" x-text="(activeTestimonial + 1) + ' / ' + testimonials.length"></span>
                    <button @click="nextTestimonial()" class="w-8 h-8 rounded-full border border-[#D4AF37]/30 flex items-center justify-center text-[#D4AF37] hover:bg-[#D4AF37]/10 transition-colors">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 9. AMBIENCE VIDEO BANNER                                    -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="relative h-80 sm:h-96 flex items-center justify-center overflow-hidden border-t border-b border-[#D4AF37]/20">
        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=1600&q=80" 
             alt="Ambience" 
             class="absolute inset-0 w-full h-full object-cover brightness-50">
        
        <div class="relative z-10 text-center space-y-3 px-4">
            <button @click="videoModalOpen = true"
                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-2 border-[#D4AF37] flex items-center justify-center text-[#D4AF37] hover:scale-110 hover:bg-[#D4AF37] hover:text-black transition-all shadow-[0_0_30px_rgba(212,175,55,0.4)] mx-auto">
                <i data-lucide="play" class="w-6 h-6 fill-current ml-1"></i>
            </button>
            <p class="font-serif text-lg sm:text-xl font-bold text-white tracking-wide">
                Experience The Royal Dine Ambience
            </p>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 10. BLOG / TIPS & TRICKS                                    -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="blog" class="py-20 bg-[#0B0B0B]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center space-y-1.5 mb-14">
                <p class="font-script text-3xl text-[#D4AF37]">Blog Post</p>
                <h2 class="font-serif text-3xl font-bold text-white tracking-tight">
                    Tips & Tricks
                </h2>
                <div class="w-12 h-0.5 mx-auto gold-gradient-bg mt-2"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($blogs as $blog)
                <div class="bg-[#121212] rounded-2xl border gold-border overflow-hidden group hover:border-[#D4AF37] transition-all">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-5 chamfer-top-right bg-[#171717] -mt-4 relative z-10 m-2.5 rounded-xl border border-[#D4AF37]/15">
                        <span class="text-[9px] uppercase font-bold text-[#D4AF37] tracking-widest block mb-1">{{ $blog['date'] }}</span>
                        <h3 class="font-serif text-sm font-bold text-white mb-1.5 line-clamp-1 group-hover:text-[#D4AF37] transition-colors">
                            {{ $blog['title'] }}
                        </h3>
                        <p class="text-[11px] text-[#9E8C85] line-clamp-2 mb-3 leading-relaxed">
                            {{ $blog['excerpt'] }}
                        </p>
                        <div class="pt-2 border-t border-[#D4AF37]/10 flex items-center justify-between text-[11px] text-[#D4AF37]">
                            <span class="font-bold">By {{ $blog['author'] }}</span>
                            <span class="font-bold group-hover:underline">Read More →</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 11. SUBSCRIBE NEWSLETTER                                    -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="py-14 bg-[#101010] border-t border-b border-[#D4AF37]/20 diagonal-pattern">
        <div class="max-w-4xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left space-y-0.5">
                <p class="font-script text-2xl text-[#D4AF37]">Stay In Touch</p>
                <h3 class="font-serif text-2xl font-bold text-white">Subscribe Now !</h3>
            </div>
            
            <form @submit.prevent="alert('ধন্যবাদ! আপনি সফলভাবে আমাদের ভিআইপি ক্লাবে সাবস্ক্রাইব করেছেন।')" 
                  class="flex w-full md:w-auto flex-1 max-w-md gap-2">
                <input type="email" required placeholder="Enter your email..."
                       class="flex-1 px-4 py-2.5 rounded-full bg-[#181818] border border-[#D4AF37]/30 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-[#D4AF37]">
                <button type="submit" 
                        class="px-6 py-2.5 rounded-full gold-gradient-bg text-[#080808] font-black text-xs uppercase tracking-widest hover:brightness-110 transition-all shrink-0">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 12. FOOTER                                                   -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <footer class="bg-[#060606] text-[#9E8C85] pt-14 pb-8 border-t border-[#D4AF37]/15">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            
            <div class="space-y-3">
                <a href="{{ route('home') }}" class="font-script text-3xl text-[#D4AF37] block">
                    {{ $branch->restaurant_name ?? "Lazzat" }}
                </a>
                <p class="text-xs leading-relaxed text-[#7A6D65]">
                    Where culinary royalty meets contemporary gourmet excellence. Experience unmatched flavours crafted with passionate artistry.
                </p>
            </div>

            <div class="space-y-2">
                <h4 class="font-serif text-xs font-black uppercase tracking-widest text-[#D4AF37]">Menu</h4>
                <ul class="space-y-1.5 text-xs">
                    <li><a href="#menu" class="hover:text-[#D4AF37]">Special Dum Biryani</a></li>
                    <li><a href="#menu" class="hover:text-[#D4AF37]">Royal Kebabs & Grills</a></li>
                    <li><a href="#menu" class="hover:text-[#D4AF37]">Clay Oven Naan</a></li>
                </ul>
            </div>

            <div class="space-y-2">
                <h4 class="font-serif text-xs font-black uppercase tracking-widest text-[#D4AF37]">Hours & Links</h4>
                <ul class="space-y-1.5 text-xs">
                    <li>Open: <strong>{{ $branch->opening_hours ?? "11:00 AM - 11:30 PM" }}</strong></li>
                    <li><a href="#reservation" class="hover:text-[#D4AF37]">Table Reservation</a></li>
                    <li><a href="{{ route('pos.index') }}" class="hover:text-[#D4AF37]">POS Terminal</a></li>
                </ul>
            </div>

            <div class="space-y-2">
                <h4 class="font-serif text-xs font-black uppercase tracking-widest text-[#D4AF37]">Contact</h4>
                <ul class="space-y-1.5 text-xs">
                    <li>{{ $branch->address ?? "Gulshan Avenue, Dhaka, Bangladesh" }}</li>
                    <li>{{ $branch->phone ?? "+880 1700-000000" }}</li>
                    <li>{{ $branch->email ?? "info@lazzatdine.com" }}</li>
                </ul>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 border-t border-[#D4AF37]/10 flex flex-col sm:flex-row items-center justify-between text-[10px] text-[#6B5F57] gap-3">
            <p>© {{ date('Y') }} {{ $branch->restaurant_name ?? "Lazzat" }}. All Rights Reserved.</p>
            <p>Enterprise NBR Mushak 6.3 POS Architecture</p>
        </div>
    </footer>

    <!-- MODAL: RESERVATION SUCCESS -->
    <div x-show="reservationSuccessModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.outside="reservationSuccessModal = false"
             class="w-full max-w-md bg-[#121212] rounded-3xl p-6 sm:p-8 border border-[#D4AF37] text-center space-y-4 shadow-2xl relative">
            <div class="w-14 h-14 rounded-full gold-gradient-bg mx-auto flex items-center justify-center text-[#080808] shadow-lg">
                <i data-lucide="check" class="w-7 h-7 stroke-[3]"></i>
            </div>
            
            <h3 class="font-serif text-2xl font-bold text-white">Table Reserved Successfully!</h3>
            
            <div class="bg-[#181818] p-4 rounded-2xl border border-[#D4AF37]/20 text-xs text-left space-y-1 text-[#E0D4CF]">
                <p>Guest Name: <strong class="text-white" x-text="confirmedData?.customer_name"></strong></p>
                <p>Guests: <strong class="text-[#D4AF37]" x-text="confirmedData?.guest_count + ' Persons'"></strong></p>
                <p>Date & Time: <strong class="text-white" x-text="confirmedData?.date + ' at ' + confirmedData?.time"></strong></p>
                <p>Assigned Area: <strong class="text-[#D4AF37]" x-text="confirmedData?.table_name"></strong></p>
            </div>

            <button @click="reservationSuccessModal = false"
                    class="w-full py-3 rounded-full gold-gradient-bg text-[#080808] font-black text-xs uppercase tracking-wider hover:brightness-110 transition-all">
                Close & Continue
            </button>
        </div>
    </div>

    <!-- MODAL: VIDEO PREVIEW -->
    <div x-show="videoModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md">
        <div @click.outside="videoModalOpen = false" class="w-full max-w-3xl bg-[#111] rounded-3xl overflow-hidden border border-[#D4AF37] relative">
            <div class="p-3 border-b border-[#D4AF37]/20 flex justify-between items-center bg-[#181818]">
                <span class="font-serif text-xs text-[#D4AF37] font-bold">Restaurant Ambience Video</span>
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
                        alert(data.message || 'রিজার্ভেশন সম্পূর্ণ করা সম্ভব হয়নি।');
                    }
                } catch (e) {
                    alert('ত্রুটি: ' + e.message);
                } finally {
                    this.isSubmitting = false;
                }
            }
        };
    }
    </script>
</body>
</html>
