<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lezzatos — The Authentic Restaurant & Cafe')</title>
    
    <!-- Google Fonts for Luxury Typography (Exact match) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@600;700;800;900&family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600;1,700&family=Great+Vibes&family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">
    
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
                        serif: ['"Playfair Display"', 'Cinzel', '"Times New Roman"', 'serif'],
                        script: ['"Great Vibes"', '"Alex Brush"', 'cursive'],
                        sans: ['Inter', 'sans-serif'],
                        classic: ['"Times New Roman"', 'Times', '"Cormorant Garamond"', '"Playfair Display"', 'serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- AOS (Animate On Scroll) & Lucide Icons -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
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

        .font-classic {
            font-family: "Times New Roman", Times, "Cormorant Garamond", "Playfair Display", serif;
        }

        /* Diagonal Luxury Gold Corner Lines (matching design screenshots) */
        .gold-diagonal-lines {
            background-image: repeating-linear-gradient(45deg, rgba(197,168,128,0.2) 0, rgba(197,168,128,0.2) 1.5px, transparent 0, transparent 10px);
        }

        .gold-diagonal-lines-subtle {
            background-image: repeating-linear-gradient(45deg, rgba(197,168,128,0.08) 0, rgba(197,168,128,0.08) 1px, transparent 0, transparent 12px);
        }

        /* Chamfered Cut-Corners (matching design screenshots) */
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
            transition: all 0.3s ease;
        }

        .gold-underline-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #C5A880;
            transition: width 0.3s ease, background-color 0.3s ease;
        }

        .gold-underline-btn:hover::after {
            width: 100%;
            background-color: #E5C07B;
            box-shadow: 0 0 10px rgba(229,192,123,0.8);
        }

        /* ════ LUXURY SMOOTH ANIMATIONS & MICRO-INTERACTIONS ════ */
        @keyframes floatSmooth {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-7px); }
        }

        @keyframes goldPulse {
            0%, 100% { box-shadow: 0 0 15px rgba(197, 168, 128, 0.2); }
            50% { box-shadow: 0 0 30px rgba(209, 165, 104, 0.5); }
        }

        .floating-element {
            animation: floatSmooth 6s ease-in-out infinite;
        }

        /* Luxury Card Hover Lift */
        .luxury-card {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease;
        }

        .luxury-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.8), 0 0 25px -5px rgba(197, 168, 128, 0.3);
            border-color: rgba(197, 168, 128, 0.7);
        }

        /* Luxury Image Zoom Container */
        .luxury-img-zoom {
            overflow: hidden;
            position: relative;
        }

        .luxury-img-zoom img {
            transition: transform 0.7s cubic-bezier(0.25, 1, 0.5, 1), filter 0.7s ease;
        }

        .luxury-img-zoom:hover img {
            transform: scale(1.07);
            filter: brightness(1.06);
        }

        /* Shimmer Button Effect */
        .gold-glow-btn {
            position: relative;
            overflow: hidden;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .gold-glow-btn:hover {
            box-shadow: 0 0 25px rgba(209, 165, 104, 0.5);
            transform: translateY(-2px);
        }

        /* Page Banner Header */
        .page-header-banner {
            background: linear-gradient(180deg, rgba(11,11,11,0.7) 0%, rgba(11,11,11,0.95) 100%), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
        }

        /* ════ INFINITE MARQUEE SCROLL (60FPS) ════ */
        @keyframes marqueeScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .marquee-container {
            overflow: hidden;
            display: flex;
            user-select: none;
            position: relative;
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }

        .marquee-track {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            gap: 3rem;
            animation: marqueeScroll 24s linear infinite;
            will-change: transform;
        }

        .marquee-container:hover .marquee-track {
            animation-play-state: paused;
        }

        .partner-logo-item {
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            opacity: 0.92;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 1.5rem;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.5));
        }

        .partner-logo-item:hover {
            opacity: 1;
            transform: scale(1.15);
            filter: drop-shadow(0 6px 16px rgba(255,255,255,0.15));
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
<body x-data="luxuryApp()" x-init="init()" class="antialiased selection:bg-[#C5A880]/30 selection:text-[#FFF]">

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- LUXURY NAVBAR (EXACT MATCHING SCREENSHOT)                    -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 backdrop-blur-md"
            :class="isScrolled ? 'bg-[#0B0B0B]/95 border-b border-[#C5A880]/20 py-4 shadow-2xl' : 'bg-transparent py-6'">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 flex items-center justify-between">
            
            <!-- Left: Gold Cursive Logo -->
            <a href="{{ route('home') }}" class="group">
                <span class="font-script text-3xl sm:text-4xl text-[#C5A880] tracking-wide group-hover:brightness-125 transition-all">
                    Lezzatos.
                </span>
            </a>

            <!-- Right: Navigation Menu Links with Classic Bold Times New Roman Font -->
            <nav class="hidden lg:flex items-center gap-4 xl:gap-6 font-classic text-[13px] uppercase tracking-[0.16em] font-bold text-[#B0A298]">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#C5A880] border-b-2 border-[#C5A880] pb-1 font-black shadow-sm' : 'hover:text-[#C5A880] transition-colors' }}">HOME</a>
                <a href="{{ route('our-menu') }}" class="{{ request()->routeIs('our-menu') ? 'text-[#C5A880] border-b-2 border-[#C5A880] pb-1 font-black shadow-sm' : 'hover:text-[#C5A880] transition-colors' }}">OUR MENU</a>
                <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') ? 'text-[#C5A880] border-b-2 border-[#C5A880] pb-1 font-black shadow-sm' : 'hover:text-[#C5A880] transition-colors' }}">SHOP</a>
                <a href="{{ route('about-us') }}" class="{{ request()->routeIs('about-us') ? 'text-[#C5A880] border-b-2 border-[#C5A880] pb-1 font-black shadow-sm' : 'hover:text-[#C5A880] transition-colors' }}">ABOUT US</a>
                <a href="{{ route('our-chef') }}" class="{{ request()->routeIs('our-chef') ? 'text-[#C5A880] border-b-2 border-[#C5A880] pb-1 font-black shadow-sm' : 'hover:text-[#C5A880] transition-colors' }}">CHEF</a>
                <a href="{{ route('our-service') }}" class="{{ request()->routeIs('our-service') ? 'text-[#C5A880] border-b-2 border-[#C5A880] pb-1 font-black shadow-sm' : 'hover:text-[#C5A880] transition-colors' }}">SERVICES</a>
                <a href="{{ route('news') }}" class="{{ request()->routeIs('news') ? 'text-[#C5A880] border-b-2 border-[#C5A880] pb-1 font-black shadow-sm' : 'hover:text-[#C5A880] transition-colors' }}">NEWS</a>
                <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'text-[#C5A880] border-b-2 border-[#C5A880] pb-1 font-black shadow-sm' : 'hover:text-[#C5A880] transition-colors' }}">FAQ</a>
                <a href="{{ route('reservation') }}" class="{{ request()->routeIs('reservation') ? 'text-[#C5A880] border-b-2 border-[#C5A880] pb-1 font-black shadow-sm' : 'hover:text-[#C5A880] transition-colors' }}">RESERVATION</a>
                <a href="{{ route('contact-us') }}" class="{{ request()->routeIs('contact-us') ? 'text-[#C5A880] border-b-2 border-[#C5A880] pb-1 font-black shadow-sm' : 'hover:text-[#C5A880] transition-colors' }}">CONTACT</a>
                <a href="{{ route('pos.index') }}" class="px-3.5 py-1 rounded-full border-2 border-[#C5A880] text-[#C5A880] hover:bg-[#C5A880] hover:text-black font-black tracking-widest transition-all shadow-md">
                    POS
                </a>
            </nav>

            <!-- Mobile Hamburger -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-[#C5A880]">
                <i :data-lucide="mobileMenuOpen ? 'x' : 'menu'" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Mobile Drawer -->
        <div x-show="mobileMenuOpen" x-cloak x-transition
             class="lg:hidden bg-[#111] border-b border-[#C5A880]/20 px-6 py-6 space-y-3 font-classic">
            <a @click="mobileMenuOpen=false" href="{{ route('home') }}" class="block text-sm font-bold tracking-widest {{ request()->routeIs('home') ? 'text-[#C5A880]' : 'text-gray-300 hover:text-[#C5A880]' }}">HOME</a>
            <a @click="mobileMenuOpen=false" href="{{ route('our-menu') }}" class="block text-sm font-bold tracking-widest {{ request()->routeIs('our-menu') ? 'text-[#C5A880]' : 'text-gray-300 hover:text-[#C5A880]' }}">OUR MENU</a>
            <a @click="mobileMenuOpen=false" href="{{ route('shop') }}" class="block text-sm font-bold tracking-widest {{ request()->routeIs('shop') ? 'text-[#C5A880]' : 'text-gray-300 hover:text-[#C5A880]' }}">SHOP</a>
            <a @click="mobileMenuOpen=false" href="{{ route('about-us') }}" class="block text-sm font-bold tracking-widest {{ request()->routeIs('about-us') ? 'text-[#C5A880]' : 'text-gray-300 hover:text-[#C5A880]' }}">ABOUT US</a>
            <a @click="mobileMenuOpen=false" href="{{ route('our-chef') }}" class="block text-sm font-bold tracking-widest {{ request()->routeIs('our-chef') ? 'text-[#C5A880]' : 'text-gray-300 hover:text-[#C5A880]' }}">OUR CHEF</a>
            <a @click="mobileMenuOpen=false" href="{{ route('our-service') }}" class="block text-sm font-bold tracking-widest {{ request()->routeIs('our-service') ? 'text-[#C5A880]' : 'text-gray-300 hover:text-[#C5A880]' }}">OUR SERVICE</a>
            <a @click="mobileMenuOpen=false" href="{{ route('news') }}" class="block text-sm font-bold tracking-widest {{ request()->routeIs('news') ? 'text-[#C5A880]' : 'text-gray-300 hover:text-[#C5A880]' }}">NEWS</a>
            <a @click="mobileMenuOpen=false" href="{{ route('faq') }}" class="block text-sm font-bold tracking-widest {{ request()->routeIs('faq') ? 'text-[#C5A880]' : 'text-gray-300 hover:text-[#C5A880]' }}">FAQ</a>
            <a @click="mobileMenuOpen=false" href="{{ route('reservation') }}" class="block text-sm font-bold tracking-widest {{ request()->routeIs('reservation') ? 'text-[#C5A880]' : 'text-gray-300 hover:text-[#C5A880]' }}">RESERVATION</a>
            <a @click="mobileMenuOpen=false" href="{{ route('contact-us') }}" class="block text-sm font-bold tracking-widest {{ request()->routeIs('contact-us') ? 'text-[#C5A880]' : 'text-gray-300 hover:text-[#C5A880]' }}">CONTACT</a>
            <a href="{{ route('pos.index') }}" class="block text-sm font-black text-[#C5A880]">POS TERMINAL</a>
        </div>
    </header>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- DYNAMIC PAGE CONTENT                                         -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <main>
        @yield('content')
    </main>

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- NEWSLETTER (MATCHING DESIGN)                                 -->
    <!-- ════════════════════════════════════════════════════════════ -->
    @section('newsletter')
    <section class="py-14 bg-[#111111] border-t border-b border-[#C5A880]/15 relative">
        <div class="max-w-4xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left space-y-0.5">
                <p class="font-script text-2xl text-[#C5A880]">Stay In Touch</p>
                <h3 class="font-serif text-2xl font-bold text-white">Subscribe Now !</h3>
            </div>
            
            <form @submit.prevent="alert('Thank you for subscribing to Lezzatos VIP Club!')" 
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
    @show

    <!-- ════════════════════════════════════════════════════════════ -->
    <!-- LUXURY FOOTER (MATCHING DESIGN)                              -->
    <!-- ════════════════════════════════════════════════════════════ -->
    <footer class="bg-[#080808] text-[#8C7D73] pt-14 pb-8">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
            
            <!-- Brand Bio -->
            <div class="space-y-3">
                <a href="{{ route('home') }}" class="font-script text-3xl text-[#C5A880] block">
                    Lezzatos.
                </a>
                <p class="text-xs leading-relaxed text-[#6B5F57]">
                    Where culinary royalty meets contemporary gourmet excellence. Experience unmatched flavours crafted with passionate artistry.
                </p>
                <!-- Real Vector SVG Social Media Icons (Solid Official Colors - No Slider/Scrollbar) -->
                <div class="flex items-center gap-3 pt-2">
                    <!-- Facebook -->
                    <a href="{{ $contactData['social']['facebook'] ?? 'https://facebook.com' }}" target="_blank" rel="noopener noreferrer" 
                       class="w-8 h-8 rounded-full bg-[#1877F2] text-white flex items-center justify-center transition-all duration-300 hover:scale-115 hover:shadow-[0_0_14px_rgba(24,119,242,0.7)] shadow-md" title="Facebook">
                        <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>

                    <!-- Instagram -->
                    <a href="{{ $contactData['social']['instagram'] ?? 'https://instagram.com' }}" target="_blank" rel="noopener noreferrer" 
                       class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] text-white flex items-center justify-center transition-all duration-300 hover:scale-115 hover:shadow-[0_0_14px_rgba(220,39,67,0.7)] shadow-md" title="Instagram">
                        <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>

                    <!-- TikTok -->
                    <a href="{{ $contactData['social']['tiktok'] ?? 'https://tiktok.com' }}" target="_blank" rel="noopener noreferrer" 
                       class="w-8 h-8 rounded-full bg-black border border-white/20 text-white flex items-center justify-center transition-all duration-300 hover:scale-115 hover:shadow-[0_0_14px_rgba(37,244,238,0.7)] shadow-md" title="TikTok">
                        <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                    </a>

                    <!-- YouTube -->
                    <a href="{{ $contactData['social']['youtube'] ?? 'https://youtube.com' }}" target="_blank" rel="noopener noreferrer" 
                       class="w-8 h-8 rounded-full bg-[#FF0000] text-white flex items-center justify-center transition-all duration-300 hover:scale-115 hover:shadow-[0_0_14px_rgba(255,0,0,0.7)] shadow-md" title="YouTube">
                        <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>

                    <!-- WhatsApp -->
                    <a href="{{ $contactData['social']['whatsapp'] ?? 'https://wa.me/62898245124' }}" target="_blank" rel="noopener noreferrer" 
                       class="w-8 h-8 rounded-full bg-[#25D366] text-white flex items-center justify-center transition-all duration-300 hover:scale-115 hover:shadow-[0_0_14px_rgba(37,211,102,0.7)] shadow-md" title="WhatsApp">
                        <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Menu Links -->
            <div class="space-y-2">
                <h4 class="font-serif text-xs font-bold uppercase tracking-widest text-white">MENU</h4>
                <ul class="space-y-1.5 text-xs">
                    <li><a href="{{ route('home') }}" class="hover:text-[#C5A880]">Home</a></li>
                    <li><a href="{{ route('our-menu') }}" class="hover:text-[#C5A880]">Our Menu</a></li>
                    <li><a href="{{ route('about-us') }}" class="hover:text-[#C5A880]">About Us</a></li>
                    <li><a href="{{ route('our-chef') }}" class="hover:text-[#C5A880]">Our Chef</a></li>
                    <li><a href="{{ route('reservation') }}" class="hover:text-[#C5A880]">Reservation</a></li>
                </ul>
            </div>

            <!-- Hours & Services -->
            <div class="space-y-2">
                <h4 class="font-serif text-xs font-bold uppercase tracking-widest text-white">HOURS</h4>
                <ul class="space-y-1.5 text-xs">
                    <li>Monday - Friday: <strong>08:00 - 22:00</strong></li>
                    <li>Saturday: <strong>08:00 - 23:00</strong></li>
                    <li>Sunday: <strong>Closed</strong></li>
                    <li><a href="{{ route('reservation') }}" class="text-[#C5A880] hover:underline">Book A Table</a></li>
                </ul>
            </div>

            <!-- Contact Details -->
            <div class="space-y-2">
                <h4 class="font-serif text-xs font-bold uppercase tracking-widest text-white">FIND US</h4>
                <ul class="space-y-1.5 text-xs">
                    <li>{{ $branch->address ?? "Braga Street 28, Bandung, West Java" }}</li>
                    <li>{{ $branch->phone ?? "+62 898245124" }}</li>
                    <li>{{ $branch->email ?? "lezzatos@restaurant.com" }}</li>
                </ul>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-6 sm:px-10 pt-6 border-t border-[#C5A880]/10 text-center text-[10px] text-[#554B44]">
            <p>© Copyright Lezzatos {{ date('Y') }}. All Right Reserved.</p>
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
    function luxuryApp() {
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
                
                // Automatic Auto-slide every 4 seconds
                setInterval(() => {
                    this.nextTestimonial();
                }, 4000);

                this.$nextTick(() => {
                    if (window.lucide) window.lucide.createIcons();
                    if (typeof AOS !== 'undefined') {
                        AOS.init({
                            duration: 700,
                            easing: 'ease-out-cubic',
                            once: true,
                            offset: 40,
                            delay: 30
                        });
                    }
                });
            },

            nextTestimonial() {
                if (this.testimonials.length > 0) {
                    this.activeTestimonial = (this.activeTestimonial + 1) % this.testimonials.length;
                }
            },

            prevTestimonial() {
                if (this.testimonials.length > 0) {
                    this.activeTestimonial = (this.activeTestimonial - 1 + this.testimonials.length) % this.testimonials.length;
                }
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
