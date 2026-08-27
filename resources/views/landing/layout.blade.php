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

        /* Page Banner Header */
        .page-header-banner {
            background: linear-gradient(180deg, rgba(11,11,11,0.7) 0%, rgba(11,11,11,0.95) 100%), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1600&q=80');
            background-size: cover;
            background-position: center;
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

            <!-- Right: Navigation Menu Links -->
            <nav class="hidden md:flex items-center gap-7 text-[11px] uppercase tracking-[0.25em] font-semibold text-[#A8988D]">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#C5A880] border-b border-[#C5A880] pb-1' : 'hover:text-[#C5A880] transition-colors' }}">HOME</a>
                <a href="{{ route('our-menu') }}" class="{{ request()->routeIs('our-menu') ? 'text-[#C5A880] border-b border-[#C5A880] pb-1' : 'hover:text-[#C5A880] transition-colors' }}">OUR MENU</a>
                <a href="{{ route('about-us') }}" class="{{ request()->routeIs('about-us') ? 'text-[#C5A880] border-b border-[#C5A880] pb-1' : 'hover:text-[#C5A880] transition-colors' }}">ABOUT US</a>
                <a href="{{ route('our-chef') }}" class="{{ request()->routeIs('our-chef') ? 'text-[#C5A880] border-b border-[#C5A880] pb-1' : 'hover:text-[#C5A880] transition-colors' }}">OUR CHEF</a>
                <a href="{{ route('reservation') }}" class="{{ request()->routeIs('reservation') ? 'text-[#C5A880] border-b border-[#C5A880] pb-1' : 'hover:text-[#C5A880] transition-colors' }}">RESERVATION</a>
                <a href="{{ route('contact-us') }}" class="{{ request()->routeIs('contact-us') ? 'text-[#C5A880] border-b border-[#C5A880] pb-1' : 'hover:text-[#C5A880] transition-colors' }}">CONTACT</a>
                <a href="{{ route('pos.index') }}" class="px-3 py-1 rounded-full border border-[#C5A880]/50 text-[#C5A880] hover:bg-[#C5A880] hover:text-black transition-all">
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
            <a @click="mobileMenuOpen=false" href="{{ route('home') }}" class="block text-xs font-semibold tracking-widest text-[#C5A880]">HOME</a>
            <a @click="mobileMenuOpen=false" href="{{ route('our-menu') }}" class="block text-xs font-semibold tracking-widest text-gray-300 hover:text-[#C5A880]">OUR MENU</a>
            <a @click="mobileMenuOpen=false" href="{{ route('about-us') }}" class="block text-xs font-semibold tracking-widest text-gray-300 hover:text-[#C5A880]">ABOUT US</a>
            <a @click="mobileMenuOpen=false" href="{{ route('our-chef') }}" class="block text-xs font-semibold tracking-widest text-gray-300 hover:text-[#C5A880]">OUR CHEF</a>
            <a @click="mobileMenuOpen=false" href="{{ route('reservation') }}" class="block text-xs font-semibold tracking-widest text-gray-300 hover:text-[#C5A880]">RESERVATION</a>
            <a @click="mobileMenuOpen=false" href="{{ route('contact-us') }}" class="block text-xs font-semibold tracking-widest text-gray-300 hover:text-[#C5A880]">CONTACT</a>
            <a href="{{ route('pos.index') }}" class="block text-xs font-bold text-[#C5A880]">POS TERMINAL</a>
            <a href="{{ route('login') }}" class="block text-xs font-bold text-gray-300">STAFF LOGIN</a>
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
                <div class="flex items-center gap-3 pt-1">
                    <a href="#" class="w-7 h-7 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] hover:border-[#C5A880]"><i data-lucide="facebook" class="w-3.5 h-3.5"></i></a>
                    <a href="#" class="w-7 h-7 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] hover:border-[#C5A880]"><i data-lucide="instagram" class="w-3.5 h-3.5"></i></a>
                    <a href="#" class="w-7 h-7 rounded-full border border-[#C5A880]/30 flex items-center justify-center text-[#C5A880] hover:border-[#C5A880]"><i data-lucide="twitter" class="w-3.5 h-3.5"></i></a>
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
            testimonials: @json($testimonials ?? []),
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
