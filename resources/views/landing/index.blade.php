<!DOCTYPE html>
<html lang="bn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $branch->restaurant_name ?? "Lazzat Luxury Dine & Restaurant" }} — The Authentic Restaurant & Cafe</title>
    
    <!-- Google Fonts for Luxury Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@500;600;700;800;900&family=Great+Vibes&family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --gold-primary: #C5A880;
            --gold-light: #E5C07B;
            --gold-bright: #D4AF37;
            --gold-dark: #8C6D37;
            --bg-dark: #090909;
            --bg-card: #121212;
            --bg-card-alt: #171717;
        }

        body {
            background-color: var(--bg-dark);
            color: #E2D9D2;
            font-family: 'Inter', 'Hind Siliguri', sans-serif;
            overflow-x: hidden;
        }

        .font-serif-luxury {
            font-family: 'Playfair Display', 'Cinzel', serif;
        }

        .font-script-gold {
            font-family: 'Great Vibes', 'Alex Brush', cursive;
        }

        .gold-gradient-text {
            background: linear-gradient(135deg, #FBF0D8 0%, #D4AF37 50%, #A47922 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .gold-gradient-bg {
            background: linear-gradient(135deg, #D4AF37 0%, #B8922A 50%, #8C6D37 100%);
        }

        .gold-border {
            border-color: rgba(197, 168, 128, 0.25);
        }

        .gold-border-glow:hover {
            border-color: rgba(212, 175, 55, 0.6);
            box-shadow: 0 0 25px rgba(212, 175, 55, 0.15);
        }

        /* Chamfered / Cut-Corner Badges */
        .chamfer-card-top-right {
            clip-path: polygon(0 0, calc(100% - 32px) 0, 100% 32px, 100% 100%, 0 100%);
        }

        .chamfer-card-bottom-left {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 32px 100%, 0 calc(100% - 32px));
        }

        .chamfer-card-both {
            clip-path: polygon(0 0, calc(100% - 24px) 0, 100% 24px, 100% 100%, 24px 100%, 0 calc(100% - 24px));
        }

        /* Luxury Diagonal Hatch Pattern */
        .diagonal-gold-pattern {
            background-image: repeating-linear-gradient(45deg, rgba(212,175,55,0.06) 0, rgba(212,175,55,0.06) 1px, transparent 0, transparent 10px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #090909;
        }
        ::-webkit-scrollbar-thumb {
            background: #332819;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #D4AF37;
        }
    </style>
</head>
<body x-data="luxuryLanding()" x-init="init()" class="antialiased selection:bg-[#D4AF37]/30 selection:text-[#FBF0D8]">

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 1. LUXURY NAVIGATION HEADER                                 -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 backdrop-blur-md border-b"
            :class="isScrolled ? 'bg-[#090909]/95 border-[#C5A880]/20 py-3 shadow-2xl' : 'bg-transparent border-[#C5A880]/10 py-5'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            
            <!-- Brand Logo (Gold Cursive Luxury) -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <span class="font-script-gold text-3xl sm:text-4xl text-[#D4AF37] group-hover:scale-105 transition-transform">
                    {{ $branch->restaurant_name ?? "Lazzat" }}
                </span>
                <span class="hidden sm:inline-block text-[9px] uppercase tracking-[0.3em] font-semibold text-[#A89485] pl-2 border-l border-[#C5A880]/30">
                    Luxury Dining
                </span>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center gap-8 text-xs uppercase tracking-[0.2em] font-medium text-[#C2B4AA]">
                <a href="#home" class="hover:text-[#D4AF37] transition-colors py-1" :class="activeSection==='home' ? 'text-[#D4AF37] border-b border-[#D4AF37]' : ''">HOME</a>
                <a href="#specialist" class="hover:text-[#D4AF37] transition-colors py-1" :class="activeSection==='specialist' ? 'text-[#D4AF37] border-b border-[#D4AF37]' : ''">CUISINES</a>
                <a href="#about" class="hover:text-[#D4AF37] transition-colors py-1" :class="activeSection==='about' ? 'text-[#D4AF37] border-b border-[#D4AF37]' : ''">ABOUT US</a>
                <a href="#menu" class="hover:text-[#D4AF37] transition-colors py-1" :class="activeSection==='menu' ? 'text-[#D4AF37] border-b border-[#D4AF37]' : ''">SPECIAL DISH</a>
                <a href="#reservation" class="hover:text-[#D4AF37] transition-colors py-1" :class="activeSection==='reservation' ? 'text-[#D4AF37] border-b border-[#D4AF37]' : ''">RESERVATION</a>
                <a href="#blog" class="hover:text-[#D4AF37] transition-colors py-1" :class="activeSection==='blog' ? 'text-[#D4AF37] border-b border-[#D4AF37]' : ''">BLOG</a>
            </nav>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <a href="{{ route('pos.index') }}" 
                   class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border border-[#D4AF37]/50 text-[11px] font-bold tracking-wider text-[#D4AF37] hover:bg-[#D4AF37] hover:text-[#090909] transition-all shadow-xs">
                    <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i>
                    <span>POS Terminal</span>
                </a>

                <a href="#reservation"
                   class="gold-gradient-bg text-[#090909] px-4 sm:px-5 py-2 rounded-full text-xs font-extrabold uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all shadow-lg flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    <span>Book Table</span>
                </a>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-[#D4AF37] focus:outline-none">
                    <i :data-lucide="mobileMenuOpen ? 'x' : 'menu'" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileMenuOpen" x-cloak x-transition
             class="lg:hidden bg-[#0F0F0F] border-b border-[#C5A880]/20 px-6 py-6 space-y-4">
            <a @click="mobileMenuOpen=false" href="#home" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">HOME</a>
            <a @click="mobileMenuOpen=false" href="#specialist" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">CUISINES</a>
            <a @click="mobileMenuOpen=false" href="#about" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">ABOUT US</a>
            <a @click="mobileMenuOpen=false" href="#menu" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">SPECIAL DISH & MENU</a>
            <a @click="mobileMenuOpen=false" href="#reservation" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">RESERVATION</a>
            <a @click="mobileMenuOpen=false" href="#blog" class="block text-sm font-semibold tracking-wider text-[#C2B4AA] hover:text-[#D4AF37]">BLOG</a>
            <div class="pt-4 border-t border-[#C5A880]/15 flex flex-col gap-2.5">
                <a href="{{ route('pos.index') }}" class="w-full py-2.5 rounded-xl text-center text-xs font-bold border border-[#D4AF37] text-[#D4AF37]">
                    POS Billing Terminal
                </a>
                <a href="{{ route('login') }}" class="w-full py-2.5 rounded-xl text-center text-xs font-bold bg-[#1E1E1E] text-white">
                    Staff & Admin Login
                </a>
            </div>
        </div>
    </header>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 2. HERO SECTION                                              -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="home" class="relative min-h-screen pt-28 sm:pt-32 pb-20 flex items-center overflow-hidden diagonal-gold-pattern">
        <!-- Ambient Glowing Lights -->
        <div class="absolute top-1/4 left-0 w-96 h-96 bg-[#D4AF37]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 right-0 w-[500px] h-[500px] bg-[#C5A880]/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                <!-- Left Hero Copy -->
                <div class="lg:col-span-6 space-y-6 text-center lg:text-left">
                    <p class="font-script-gold text-4xl sm:text-5xl md:text-6xl text-[#D4AF37] leading-tight">
                        Welcome to {{ $branch->restaurant_name ?? "Lazzat" }}
                    </p>
                    
                    <h1 class="font-serif-luxury text-4xl sm:text-5xl md:text-6xl lg:text-6xl font-bold tracking-tight text-white leading-[1.15]">
                        The Authentic <br class="hidden sm:block">
                        <span class="gold-gradient-text">Restaurant & Cafe</span>
                    </h1>

                    <p class="text-sm sm:text-base text-[#A8988D] max-w-xl mx-auto lg:mx-0 font-light leading-relaxed">
                        Experience royal culinary craftsmanship with our timeless gourmet delicacies, signature dum biryanis, sizzling kebabs, and enchanting fine dining ambiance.
                    </p>

                    <!-- CTAs -->
                    <div class="pt-4 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="#menu"
                           class="w-full sm:w-auto px-8 py-3.5 rounded-full gold-gradient-bg text-[#090909] font-black text-xs uppercase tracking-widest hover:shadow-[0_0_30px_rgba(212,175,55,0.4)] transition-all active:scale-95 text-center">
                            Explore Menu
                        </a>
                        
                        <a href="#reservation"
                           class="w-full sm:w-auto px-8 py-3.5 rounded-full border border-[#C5A880]/50 hover:border-[#D4AF37] text-white hover:text-[#D4AF37] font-bold text-xs uppercase tracking-widest transition-all text-center flex items-center justify-center gap-2">
                            <span>Book A Table</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>

                    <!-- Short Highlights -->
                    <div class="pt-8 border-t border-[#C5A880]/15 flex items-center justify-center lg:justify-start gap-8 text-xs text-[#8E8075]">
                        <div class="flex items-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4 text-[#D4AF37]"></i>
                            <span>100% Halal & Fresh</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="shield-check" class="w-4 h-4 text-[#D4AF37]"></i>
                            <span>NBR Mushak 6.3 Compliant</span>
                        </div>
                    </div>
                </div>

                <!-- Right Hero Image Grid (Artisan Collage matching screenshot) -->
                <div class="lg:col-span-6 relative">
                    <div class="relative w-full max-w-lg mx-auto">
                        <!-- Diagonal decorative backdrop box -->
                        <div class="absolute -inset-4 bg-gradient-to-tr from-[#D4AF37]/20 to-transparent rounded-3xl blur-xl opacity-60"></div>
                        
                        <div class="grid grid-cols-2 gap-3.5 relative z-10">
                            <!-- Image 1 -->
                            <div class="relative rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl group">
                                <img src="https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=600&q=80" 
                                     alt="Dum Biryani" 
                                     class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                <span class="absolute bottom-2.5 left-3 text-[11px] font-bold text-[#FBF0D8] tracking-wider">Dum Biryani</span>
                            </div>

                            <!-- Image 2 -->
                            <div class="relative rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl group mt-6">
                                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80" 
                                     alt="Royal Kebab Platter" 
                                     class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                <span class="absolute bottom-2.5 left-3 text-[11px] font-bold text-[#FBF0D8] tracking-wider">Royal Kebabs</span>
                            </div>

                            <!-- Image 3 -->
                            <div class="relative rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl group -mt-6">
                                <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=600&q=80" 
                                     alt="Gourmet Samosa & Chutney" 
                                     class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                <span class="absolute bottom-2.5 left-3 text-[11px] font-bold text-[#FBF0D8] tracking-wider">Crisp Starters</span>
                            </div>

                            <!-- Image 4 -->
                            <div class="relative rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl group">
                                <img src="https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=600&q=80" 
                                     alt="Artisan Dessert" 
                                     class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                <span class="absolute bottom-2.5 left-3 text-[11px] font-bold text-[#FBF0D8] tracking-wider">Sweet Delights</span>
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
    <section id="specialist" class="py-24 relative bg-[#0C0C0C] border-t border-b border-[#C5A880]/15">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center space-y-2 mb-16">
                <p class="font-script-gold text-4xl text-[#D4AF37]">Discover</p>
                <h2 class="font-serif-luxury text-3xl sm:text-4xl md:text-5xl font-bold text-white tracking-tight">
                    Our Specialist Cuisine
                </h2>
                <div class="w-16 h-0.5 mx-auto gold-gradient-bg mt-3"></div>
            </div>

            <!-- 4 Specialist Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($specialistCuisines as $cuisine)
                <div class="bg-[#141414] p-7 rounded-2xl border gold-border gold-border-glow transition-all duration-300 relative group overflow-hidden">
                    <!-- Accent diagonal hatch on top-right -->
                    <div class="absolute top-0 right-0 w-20 h-20 diagonal-gold-pattern opacity-40 group-hover:opacity-80 transition-opacity pointer-events-none"></div>

                    <!-- Icon -->
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 border border-[#C5A880]/30 group-hover:border-[#D4AF37] transition-colors"
                         style="background: rgba(212, 175, 55, 0.08);">
                        <i data-lucide="{{ $cuisine['icon'] }}" class="w-6 h-6 text-[#D4AF37]"></i>
                    </div>

                    <h3 class="font-serif-luxury text-lg font-bold text-white mb-2 group-hover:text-[#D4AF37] transition-colors">
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
    <!-- 4. ABOUT US & OUR STORY (CHAMFERED BADGE SECTION)           -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="about" class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left: 2 Offset Interior Photos -->
                <div class="lg:col-span-6 grid grid-cols-2 gap-4 relative">
                    <div class="rounded-3xl overflow-hidden border border-[#C5A880]/30 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80" 
                             alt="Luxury Dining Interior" 
                             class="w-full h-80 object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="rounded-3xl overflow-hidden border border-[#C5A880]/30 shadow-2xl mt-10">
                        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=600&q=80" 
                             alt="Royal Table Setting" 
                             class="w-full h-80 object-cover hover:scale-105 transition-transform duration-500">
                    </div>
                </div>

                <!-- Right: Chamfered White/Cream Card -->
                <div class="lg:col-span-6">
                    <div class="bg-[#F8F5F2] text-[#1A1A1A] p-8 sm:p-12 chamfer-card-top-right shadow-2xl relative border-l-4 border-[#D4AF37]">
                        
                        <span class="text-xs font-extrabold uppercase tracking-[0.25em] text-[#B8922A] block mb-2">
                            About Us
                        </span>

                        <h2 class="font-serif-luxury text-3xl sm:text-4xl font-black text-[#111111] mb-5 leading-tight">
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
    <!-- 5. STATS & EXPERIENCE COUNTERS                              -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="py-14 bg-[#111111] border-t border-b border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                
                <div class="space-y-1">
                    <p class="font-serif-luxury text-4xl sm:text-5xl font-bold gold-gradient-text">{{ $stats['restaurants'] }}</p>
                    <p class="text-xs uppercase tracking-widest text-[#9E8C85]">Restaurants</p>
                </div>

                <div class="space-y-1">
                    <p class="font-serif-luxury text-4xl sm:text-5xl font-bold gold-gradient-text">{{ $stats['experience_years'] }}</p>
                    <p class="text-xs uppercase tracking-widest text-[#9E8C85]">Years Experience</p>
                </div>

                <div class="space-y-1">
                    <p class="font-serif-luxury text-4xl sm:text-5xl font-bold gold-gradient-text">{{ $stats['awards_won'] }}</p>
                    <p class="text-xs uppercase tracking-widest text-[#9E8C85]">Award Winner</p>
                </div>

                <div class="space-y-1">
                    <p class="font-serif-luxury text-4xl sm:text-5xl font-bold gold-gradient-text">{{ $stats['food_menus'] }}</p>
                    <p class="text-xs uppercase tracking-widest text-[#9E8C85]">Food Menus</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 6. SPECIAL DISH & RECOMMENDATION MENU                       -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="menu" class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center space-y-2 mb-16">
                <p class="font-script-gold text-4xl text-[#D4AF37]">Special Dish</p>
                <h2 class="font-serif-luxury text-3xl sm:text-4xl md:text-5xl font-bold text-white tracking-tight">
                    Best Recommendation Menu
                </h2>
                <div class="w-16 h-0.5 mx-auto gold-gradient-bg mt-3"></div>
            </div>

            <!-- 3 Recommendation Cards (matching screenshot) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                
                <!-- Dish 1 -->
                <div class="bg-[#141414] rounded-2xl border gold-border overflow-hidden group hover:border-[#D4AF37] transition-all">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=600&q=80" 
                             alt="Greek Fresh Salad" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <!-- Chamfered Bottom Badge -->
                    <div class="p-6 bg-[#F8F5F2] text-[#1A1A1A] chamfer-card-top-right -mt-6 relative z-10 m-4 rounded-xl shadow-xl">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-serif-luxury font-bold text-base text-[#111]">Royal Greek Salad</h3>
                            <span class="font-black text-sm text-[#8C6D37]">৳ 320</span>
                        </div>
                        <p class="text-[11px] text-[#6E635C] line-clamp-1 mb-3">Fresh feta cheese, Kalamata olives & crisp romaine</p>
                        <a href="#reservation" class="inline-flex items-center gap-1 text-[11px] font-extrabold text-[#B8922A] uppercase tracking-wider">
                            <span>Order Dish</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>

                <!-- Dish 2 -->
                <div class="bg-[#141414] rounded-2xl border gold-border overflow-hidden group hover:border-[#D4AF37] transition-all">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1555949258-eb67b1ef0ceb?auto=format&fit=crop&w=600&q=80" 
                             alt="Gourmet Pasta Fettuccine" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <!-- Chamfered Bottom Badge -->
                    <div class="p-6 bg-[#F8F5F2] text-[#1A1A1A] chamfer-card-top-right -mt-6 relative z-10 m-4 rounded-xl shadow-xl">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-serif-luxury font-bold text-base text-[#111]">Fettuccine Alfredo</h3>
                            <span class="font-black text-sm text-[#8C6D37]">৳ 490</span>
                        </div>
                        <p class="text-[11px] text-[#6E635C] line-clamp-1 mb-3">Rich parmesan cream sauce, truffle oil & herbs</p>
                        <a href="#reservation" class="inline-flex items-center gap-1 text-[11px] font-extrabold text-[#B8922A] uppercase tracking-wider">
                            <span>Order Dish</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>

                <!-- Dish 3 -->
                <div class="bg-[#141414] rounded-2xl border gold-border overflow-hidden group hover:border-[#D4AF37] transition-all">
                    <div class="relative h-64 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1565299585323-38d6b0865b47?auto=format&fit=crop&w=600&q=80" 
                             alt="Strawberry Artisan Pancakes" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <!-- Chamfered Bottom Badge -->
                    <div class="p-6 bg-[#F8F5F2] text-[#1A1A1A] chamfer-card-top-right -mt-6 relative z-10 m-4 rounded-xl shadow-xl">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-serif-luxury font-bold text-base text-[#111]">Velvet Berry Pancakes</h3>
                            <span class="font-black text-sm text-[#8C6D37]">৳ 290</span>
                        </div>
                        <p class="text-[11px] text-[#6E635C] line-clamp-1 mb-3">Fluffy buttermilk stack, raspberry glaze & walnuts</p>
                        <a href="#reservation" class="inline-flex items-center gap-1 text-[11px] font-extrabold text-[#B8922A] uppercase tracking-wider">
                            <span>Order Dish</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>

            </div>

            <!-- 2-Column Menu Details & Food Display (matching screenshot) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-[#111111] p-6 sm:p-10 rounded-3xl border gold-border">
                
                <!-- Left: Dotted Pricing Menu -->
                <div class="lg:col-span-6 space-y-5">
                    
                    <div class="border-b border-[#C5A880]/15 pb-3">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="font-serif-luxury font-bold text-sm sm:text-base text-white">Mutton Kacchi Biryani (Full)</span>
                            <span class="flex-1 border-b border-dotted border-[#C5A880]/30 mx-2"></span>
                            <span class="font-black text-sm text-[#D4AF37]">৳ 450</span>
                        </div>
                        <p class="text-[11px] text-[#9E8C85] mt-0.5">Tender mutton shank, saffron basmati & roasted potato</p>
                    </div>

                    <div class="border-b border-[#C5A880]/15 pb-3">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="font-serif-luxury font-bold text-sm sm:text-base text-white">Old Dhaka Beef Tehari</span>
                            <span class="flex-1 border-b border-dotted border-[#C5A880]/30 mx-2"></span>
                            <span class="font-black text-sm text-[#D4AF37]">৳ 290</span>
                        </div>
                        <p class="text-[11px] text-[#9E8C85] mt-0.5">Pure mustard oil aroma with succulent diced beef</p>
                    </div>

                    <div class="border-b border-[#C5A880]/15 pb-3">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="font-serif-luxury font-bold text-sm sm:text-base text-white">Morog Polao with Biye Bari Roast</span>
                            <span class="flex-1 border-b border-dotted border-[#C5A880]/30 mx-2"></span>
                            <span class="font-black text-sm text-[#D4AF37]">৳ 320</span>
                        </div>
                        <p class="text-[11px] text-[#9E8C85] mt-0.5">Golden fried quarter chicken with traditional almond gravy</p>
                    </div>

                    <div class="border-b border-[#C5A880]/15 pb-3">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="font-serif-luxury font-bold text-sm sm:text-base text-white">Chittagong Beef Kala Bhuna</span>
                            <span class="flex-1 border-b border-dotted border-[#C5A880]/30 mx-2"></span>
                            <span class="font-black text-sm text-[#D4AF37]">৳ 490</span>
                        </div>
                        <p class="text-[11px] text-[#9E8C85] mt-0.5">Slow-caramelized spicy beef chunks with fried garlic & onions</p>
                    </div>

                    <div class="pb-1">
                        <div class="flex items-baseline justify-between gap-4">
                            <span class="font-serif-luxury font-bold text-sm sm:text-base text-white">Butter Garlic Naan</span>
                            <span class="flex-1 border-b border-dotted border-[#C5A880]/30 mx-2"></span>
                            <span class="font-black text-sm text-[#D4AF37]">৳ 65</span>
                        </div>
                        <p class="text-[11px] text-[#9E8C85] mt-0.5">Clay-oven baked flatbread brushed with organic butter</p>
                    </div>

                </div>

                <!-- Right: High Resolution Hero Food Dish -->
                <div class="lg:col-span-6 relative">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/40 shadow-2xl">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80" 
                             alt="Signature Dish" 
                             class="w-full h-80 sm:h-96 object-cover">
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 7. TABLE RESERVATION SECTION (CHAMFERED GOLD LUXURY FORM)   -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="reservation" class="py-24 relative bg-[#0A0A0A] border-t border-[#C5A880]/15 overflow-hidden diagonal-gold-pattern">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left: Reservation Info -->
                <div class="lg:col-span-5 space-y-5 text-center lg:text-left">
                    <p class="font-script-gold text-4xl text-[#D4AF37]">Reservation</p>
                    <h2 class="font-serif-luxury text-3xl sm:text-4xl md:text-5xl font-bold text-white leading-tight">
                        Feel Happiness by Making a Reservation
                    </h2>
                    <p class="text-xs sm:text-sm text-[#A8988D] leading-relaxed">
                        Reserve your royal dining table in advance for birthdays, family gatherings, corporate dinners, or intimate romantic evenings. Enjoy VIP hospitality and instant confirmation.
                    </p>

                    <div class="pt-4 space-y-2 text-xs text-[#C5A880]">
                        <p class="flex items-center justify-center lg:justify-start gap-2">
                            <i data-lucide="clock" class="w-4 h-4 text-[#D4AF37]"></i>
                            <span>Opening Hours: <strong>{{ $branch->opening_hours ?? "11:00 AM - 11:30 PM" }}</strong></span>
                        </p>
                        <p class="flex items-center justify-center lg:justify-start gap-2">
                            <i data-lucide="phone-call" class="w-4 h-4 text-[#D4AF37]"></i>
                            <span>Hotline: <strong>{{ $branch->phone ?? "+880 1700-000000" }}</strong></span>
                        </p>
                    </div>
                </div>

                <!-- Right: Chamfered Gold Luxury Booking Card (matching screenshot) -->
                <div class="lg:col-span-7">
                    <div class="bg-gradient-to-br from-[#D4AF37] via-[#C5A880] to-[#9C7838] p-8 sm:p-10 chamfer-card-top-right shadow-2xl text-[#1A1105]">
                        
                        <div class="text-center mb-6">
                            <h3 class="font-serif-luxury text-2xl sm:text-3xl font-black uppercase tracking-wider text-[#1F1404]">
                                Book Table
                            </h3>
                            <p class="text-[11px] font-bold text-[#453216] mt-0.5">Please fill in details to reserve your table</p>
                        </div>

                        <!-- Form -->
                        <form @submit.prevent="submitReservation()" class="space-y-4">
                            
                            <!-- Customer Name & Phone -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Your Name *</label>
                                    <input type="text" x-model="form.customer_name" required placeholder="Ex: Ashfaqul Islam"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 border border-black/10 focus:outline-none focus:ring-2 focus:ring-black/40">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Mobile Phone *</label>
                                    <input type="text" x-model="form.customer_phone" required placeholder="01711000000"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 border border-black/10 focus:outline-none focus:ring-2 focus:ring-black/40">
                                </div>
                            </div>

                            <!-- Date & Time Picker -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Date *</label>
                                    <input type="date" x-model="form.reservation_date" required min="{{ date('Y-m-d') }}"
                                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] border border-black/10 focus:outline-none focus:ring-2 focus:ring-black/40">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Time Slot *</label>
                                    <select x-model="form.reservation_time" required
                                            class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] border border-black/10 focus:outline-none focus:ring-2 focus:ring-black/40">
                                        <option value="01:00 PM">01:00 PM (Lunch)</option>
                                        <option value="02:00 PM">02:00 PM (Lunch)</option>
                                        <option value="03:30 PM">03:30 PM (Late Lunch)</option>
                                        <option value="06:30 PM">06:30 PM (Evening)</option>
                                        <option value="07:30 PM">07:30 PM (Dinner)</option>
                                        <option value="08:30 PM" selected>08:30 PM (Prime Dinner)</option>
                                        <option value="09:30 PM">09:30 PM (Late Dinner)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Guests & Table Selection -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Number of Guests *</label>
                                    <select x-model.number="form.guest_count" required
                                            class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] border border-black/10 focus:outline-none focus:ring-2 focus:ring-black/40">
                                        <option value="1">1 Person</option>
                                        <option value="2" selected>2 Persons (Couple)</option>
                                        <option value="4">4 Persons (Family)</option>
                                        <option value="6">6 Persons (Group)</option>
                                        <option value="8">8 Persons (Party)</option>
                                        <option value="12">12+ Persons (VIP Lounge)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Table Choice (Optional)</label>
                                    <select x-model="form.table_id"
                                            class="w-full px-3.5 py-2.5 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] border border-black/10 focus:outline-none focus:ring-2 focus:ring-black/40">
                                        <option value="">Auto Select Best Table</option>
                                        @foreach($tables as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->floor_name }} - {{ $t->capacity }} seats)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Special Notes -->
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#2A1D0B] mb-1">Special Requests</label>
                                <input type="text" x-model="form.special_requests" placeholder="Ex: Birthday decoration, quiet corner table"
                                       class="w-full px-3.5 py-2 rounded-xl bg-white/90 focus:bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 border border-black/10 focus:outline-none focus:ring-2 focus:ring-black/40">
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2">
                                <button type="submit" :disabled="isSubmitting"
                                        class="w-full py-3.5 rounded-xl bg-[#110D05] hover:bg-black text-[#FBF0D8] font-black text-xs uppercase tracking-widest transition-all shadow-xl active:scale-98 flex items-center justify-center gap-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-[#D4AF37]"></i>
                                    <span x-text="isSubmitting ? 'Reserving Table...' : 'Confirm Table Reservation'"></span>
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
    <section class="py-24 relative bg-[#0D0D0D] border-t border-[#C5A880]/15">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-6">
            
            <p class="font-script-gold text-4xl text-[#D4AF37]">Testimonials</p>
            <h2 class="font-serif-luxury text-3xl sm:text-4xl font-bold text-white tracking-tight">
                Customer Reviews
            </h2>

            <!-- Review Card & Carousel -->
            <div class="relative pt-6">
                <!-- Quotation Mark Icon -->
                <div class="text-[#D4AF37]/25 text-7xl font-serif-luxury leading-none">“</div>
                
                <p class="font-serif-luxury italic text-lg sm:text-2xl text-[#E8DFD8] leading-relaxed max-w-2xl mx-auto -mt-6 mb-6"
                   x-text="testimonials[activeTestimonial].quote"></p>

                <div class="space-y-1">
                    <p class="font-bold text-sm text-[#D4AF37]" x-text="testimonials[activeTestimonial].name"></p>
                    <p class="text-xs text-[#8E8075]" x-text="testimonials[activeTestimonial].role"></p>
                </div>

                <!-- Carousel Controls -->
                <div class="flex items-center justify-center gap-4 pt-6">
                    <button @click="prevTestimonial()" class="w-9 h-9 rounded-full border border-[#C5A880]/30 hover:border-[#D4AF37] flex items-center justify-center text-[#D4AF37] hover:bg-[#D4AF37]/10 transition-colors">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <span class="text-xs text-[#8E8075]" x-text="(activeTestimonial + 1) + ' / ' + testimonials.length"></span>
                    <button @click="nextTestimonial()" class="w-9 h-9 rounded-full border border-[#C5A880]/30 hover:border-[#D4AF37] flex items-center justify-center text-[#D4AF37] hover:bg-[#D4AF37]/10 transition-colors">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 9. LUXURY VIDEO & AMBIENCE BANNER                           -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="relative h-96 sm:h-[450px] flex items-center justify-center overflow-hidden border-t border-b border-[#C5A880]/20">
        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=1600&q=80" 
             alt="Ambience" 
             class="absolute inset-0 w-full h-full object-cover brightness-50">
        
        <div class="relative z-10 text-center space-y-4 px-4">
            <!-- Glowing Play Button -->
            <button @click="videoModalOpen = true"
                    class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-[#D4AF37] flex items-center justify-center text-[#D4AF37] hover:scale-110 hover:bg-[#D4AF37] hover:text-black transition-all shadow-[0_0_40px_rgba(212,175,55,0.4)] mx-auto group">
                <i data-lucide="play" class="w-8 h-8 fill-current ml-1"></i>
            </button>
            <p class="font-serif-luxury text-xl sm:text-2xl font-bold text-white tracking-wide">
                Experience The Royal Dine Ambience
            </p>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 10. BLOG & CHEF'S TIPS & TRICKS                             -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section id="blog" class="py-24 relative bg-[#0C0C0C]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center space-y-2 mb-16">
                <p class="font-script-gold text-4xl text-[#D4AF37]">Blog Post</p>
                <h2 class="font-serif-luxury text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Tips & Tricks
                </h2>
                <div class="w-16 h-0.5 mx-auto gold-gradient-bg mt-3"></div>
            </div>

            <!-- 3 Blog Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($blogs as $blog)
                <div class="bg-[#141414] rounded-2xl border gold-border overflow-hidden group hover:border-[#D4AF37] transition-all">
                    <!-- Image -->
                    <div class="relative h-52 overflow-hidden">
                        <img src="{{ $blog['image'] }}" alt="{{ $blog['title'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <!-- Content (Chamfered Top-Right) -->
                    <div class="p-6 chamfer-card-top-right bg-[#171717] -mt-4 relative z-10 m-3 rounded-xl border border-[#C5A880]/15">
                        <span class="text-[10px] uppercase font-bold text-[#D4AF37] tracking-widest block mb-1">{{ $blog['date'] }}</span>
                        <h3 class="font-serif-luxury text-base font-bold text-white mb-2 line-clamp-1 group-hover:text-[#D4AF37] transition-colors">
                            {{ $blog['title'] }}
                        </h3>
                        <p class="text-xs text-[#9E8C85] line-clamp-2 mb-4 leading-relaxed">
                            {{ $blog['excerpt'] }}
                        </p>
                        <div class="pt-3 border-t border-[#C5A880]/10 flex items-center justify-between text-xs text-[#C5A880]">
                            <span class="font-bold">By {{ $blog['author'] }}</span>
                            <span class="text-[11px] font-bold group-hover:underline">Read More →</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 11. NEWSLETTER / SUBSCRIBE BAR                              -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <section class="py-16 bg-[#111111] border-t border-b border-[#C5A880]/20 relative diagonal-gold-pattern">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left space-y-1">
                <p class="font-script-gold text-3xl text-[#D4AF37]">Stay In Touch</p>
                <h3 class="font-serif-luxury text-2xl sm:text-3xl font-bold text-white">Subscribe Now !</h3>
            </div>
            
            <form @submit.prevent="alert('ধন্যবাদ! আপনি সফলভাবে আমাদের ভিআইপি ক্লাবে সাবস্ক্রাইব করেছেন।')" 
                  class="flex w-full md:w-auto flex-1 max-w-md gap-2">
                <input type="email" required placeholder="Enter your email address..."
                       class="flex-1 px-4 py-3 rounded-full bg-[#1A1A1A] border border-[#C5A880]/30 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-[#D4AF37]">
                <button type="submit" 
                        class="px-6 py-3 rounded-full gold-gradient-bg text-[#090909] font-black text-xs uppercase tracking-widest hover:brightness-110 transition-all shrink-0">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- 12. LUXURY FOOTER                                            -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <footer class="bg-[#070707] text-[#9E8C85] pt-16 pb-8 border-t border-[#C5A880]/15">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
            
            <!-- Col 1: Bio -->
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="font-script-gold text-4xl text-[#D4AF37] block">
                    {{ $branch->restaurant_name ?? "Lazzat" }}
                </a>
                <p class="text-xs leading-relaxed text-[#7A6D65]">
                    Where culinary royalty meets contemporary gourmet excellence. Experience unmatched flavours crafted with passionate artistry.
                </p>
                <div class="flex items-center gap-3 pt-2">
                    <a href="#" class="w-8 h-8 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#D4AF37] hover:border-[#D4AF37] transition-colors"><i data-lucide="facebook" class="w-4 h-4"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#D4AF37] hover:border-[#D4AF37] transition-colors"><i data-lucide="instagram" class="w-4 h-4"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#D4AF37] hover:border-[#D4AF37] transition-colors"><i data-lucide="twitter" class="w-4 h-4"></i></a>
                </div>
            </div>

            <!-- Col 2: Navigation -->
            <div class="space-y-3">
                <h4 class="font-serif-luxury text-xs font-black uppercase tracking-widest text-[#D4AF37]">Menu</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#menu" class="hover:text-[#D4AF37] transition-colors">Special Dum Biryani</a></li>
                    <li><a href="#menu" class="hover:text-[#D4AF37] transition-colors">Royal Kebabs & Grills</a></li>
                    <li><a href="#menu" class="hover:text-[#D4AF37] transition-colors">Clay Oven Naan</a></li>
                    <li><a href="#menu" class="hover:text-[#D4AF37] transition-colors">Artisan Desserts</a></li>
                </ul>
            </div>

            <!-- Col 3: Hours & Services -->
            <div class="space-y-3">
                <h4 class="font-serif-luxury text-xs font-black uppercase tracking-widest text-[#D4AF37]">Hours & Links</h4>
                <ul class="space-y-2 text-xs">
                    <li>Open: <strong>{{ $branch->opening_hours ?? "11:00 AM - 11:30 PM" }}</strong></li>
                    <li><a href="#reservation" class="hover:text-[#D4AF37] transition-colors">Table Reservation</a></li>
                    <li><a href="{{ route('pos.index') }}" class="hover:text-[#D4AF37] transition-colors">POS Terminal</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-[#D4AF37] transition-colors">Staff Login</a></li>
                </ul>
            </div>

            <!-- Col 4: Contact -->
            <div class="space-y-3">
                <h4 class="font-serif-luxury text-xs font-black uppercase tracking-widest text-[#D4AF37]">Contact</h4>
                <ul class="space-y-2 text-xs">
                    <li class="flex items-start gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-[#D4AF37] shrink-0 mt-0.5"></i>
                        <span>{{ $branch->address ?? "Gulshan Avenue, Dhaka, Bangladesh" }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4 text-[#D4AF37] shrink-0"></i>
                        <span>{{ $branch->phone ?? "+880 1700-000000" }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4 text-[#D4AF37] shrink-0"></i>
                        <span>{{ $branch->email ?? "info@lazzatdine.com" }}</span>
                    </li>
                </ul>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 border-t border-[#C5A880]/10 flex flex-col sm:flex-row items-center justify-between text-[11px] text-[#6B5F57] gap-4">
            <p>© {{ date('Y') }} {{ $branch->restaurant_name ?? "Lazzat Luxury Dine" }}. All Rights Reserved.</p>
            <p>Developed with Enterprise NBR Mushak 6.3 POS Architecture</p>
        </div>
    </footer>

    <!-- ════ MODAL: RESERVATION SUCCESS ════ -->
    <div x-show="reservationSuccessModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div @click.outside="reservationSuccessModal = false"
             class="w-full max-w-md bg-[#141414] rounded-3xl p-6 sm:p-8 border border-[#D4AF37] text-center space-y-4 shadow-2xl relative">
            <div class="w-16 h-16 rounded-full gold-gradient-bg mx-auto flex items-center justify-center text-[#090909] shadow-lg">
                <i data-lucide="check" class="w-8 h-8 stroke-[3]"></i>
            </div>
            
            <h3 class="font-serif-luxury text-2xl font-bold text-white">Table Reserved Successfully!</h3>
            
            <div class="bg-[#1C1C1C] p-4 rounded-2xl border border-[#C5A880]/20 text-xs text-left space-y-1.5 text-[#E0D4CF]">
                <p>Guest Name: <strong class="text-white" x-text="confirmedData?.customer_name"></strong></p>
                <p>Guests: <strong class="text-[#D4AF37]" x-text="confirmedData?.guest_count + ' Persons'"></strong></p>
                <p>Date & Time: <strong class="text-white" x-text="confirmedData?.date + ' at ' + confirmedData?.time"></strong></p>
                <p>Assigned Area: <strong class="text-[#D4AF37]" x-text="confirmedData?.table_name"></strong></p>
            </div>

            <p class="text-xs text-[#A8988D]">
                We look forward to welcoming you for an unforgettable royal dining experience.
            </p>

            <button @click="reservationSuccessModal = false"
                    class="w-full py-3 rounded-full gold-gradient-bg text-[#090909] font-black text-xs uppercase tracking-wider hover:brightness-110 transition-all">
                Close & Continue
            </button>
        </div>
    </div>

    <!-- ════ MODAL: VIDEO PREVIEW ════ -->
    <div x-show="videoModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md">
        <div @click.outside="videoModalOpen = false" class="w-full max-w-3xl bg-[#111] rounded-3xl overflow-hidden border border-[#D4AF37] relative">
            <div class="p-3 border-b border-[#C5A880]/20 flex justify-between items-center bg-[#171717]">
                <span class="font-serif-luxury text-xs text-[#D4AF37] font-bold">Restaurant Ambience Video</span>
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
            activeSection: 'home',
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
                    this.isScrolled = window.scrollY > 50;
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
                        alert(data.message || 'রিজার্ভেশন প্রক্রিয়া সম্পূর্ণ করা সম্ভব হয়নি।');
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
