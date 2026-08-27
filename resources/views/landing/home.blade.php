@extends('landing.layout')

@section('title', ($branch->restaurant_name ?? 'Lezzatos') . ' — The Authentic Restaurant & Cafe')

@section('content')

    <!-- ════ HERO SECTION ════ -->
    <section id="home" class="relative min-h-[92vh] pt-36 pb-20 flex items-center bg-[#0B0B0B] overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left 50% Text Column -->
                <div class="lg:col-span-6 space-y-6 text-left" data-aos="fade-right" data-aos-duration="900">
                    <p class="font-script text-3xl sm:text-4xl text-[#C5A880] tracking-wide" data-aos="fade-down" data-aos-delay="100">
                        {{ $hero['tagline'] ?? 'Welcome to Lezzatos' }}
                    </p>
                    
                    <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-[1.18] tracking-tight" data-aos="fade-up" data-aos-delay="200">
                        {{ $hero['title_line1'] ?? 'The Authentic' }} <br>
                        {{ $hero['title_line2'] ?? 'Restaurant & Cafe' }}
                    </h1>

                    <p class="text-xs sm:text-sm text-[#8C7D73] max-w-md font-light leading-relaxed" data-aos="fade-up" data-aos-delay="300">
                        {{ $hero['description'] ?? 'Experience royal culinary craftsmanship with our timeless gourmet delicacies, signature dum biryanis, sizzling kebabs, and enchanting fine dining ambiance.' }}
                    </p>

                    <div class="pt-4" data-aos="fade-up" data-aos-delay="400">
                        <a href="{{ $hero['btn_url'] ?? route('our-menu') }}" class="gold-underline-btn text-xs uppercase tracking-[0.25em] font-bold text-white hover:text-[#C5A880] transition-all inline-block">
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

    <!-- ════ 7. CUSTOMER REVIEWS ════ -->
    <section class="py-20 bg-[#0E0E0E] border-t border-[#C5A880]/15">
        <div class="max-w-3xl mx-auto px-6 text-center space-y-4" data-aos="fade-up">
            <p class="font-script text-3xl text-[#C5A880]">Testimonials</p>
            <h2 class="font-serif text-3xl font-bold text-white tracking-tight">Customer Reviews</h2>
            <div class="pt-6 relative">
                <div class="flex items-center justify-between">
                    <button @click="prevTestimonial()" class="text-[#C5A880] hover:text-white transition-colors p-2 hover:scale-125 transform"><i data-lucide="chevron-left" class="w-6 h-6"></i></button>
                    <div class="max-w-xl mx-auto px-4">
                        <p class="text-xs sm:text-sm text-[#A8988D] italic leading-relaxed transition-opacity duration-300" x-text="testimonials[activeTestimonial].quote"></p>
                        <div class="text-[#C5A880] text-4xl font-serif mt-3 mb-1">“</div>
                        <p class="font-bold text-xs uppercase tracking-wider text-white" x-text="testimonials[activeTestimonial].name"></p>
                    </div>
                    <button @click="nextTestimonial()" class="text-[#C5A880] hover:text-white transition-colors p-2 hover:scale-125 transform"><i data-lucide="chevron-right" class="w-6 h-6"></i></button>
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
