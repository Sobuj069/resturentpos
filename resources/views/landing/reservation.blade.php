@extends('landing.layout')

@section('title', 'Reservation — ' . ($branch->restaurant_name ?? 'Lezzatos'))

@section('content')

    <!-- ════ PAGE HEADER BANNER ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20" data-aos="fade-down">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                Reservation
            </h1>
        </div>
    </section>

    <!-- ════ 1. MAKE A RESERVATION (2-COLUMN BOOKING MODULE) ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-6xl mx-auto px-6 sm:px-10">
            
            <div class="text-center space-y-1 mb-16" data-aos="fade-up">
                <p class="font-script text-3xl text-[#C5A880]">Book Table</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Make a Reservation
                </h2>
            </div>

            <form @submit.prevent="submitReservation()" class="space-y-6">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                    
                    <!-- Left: Sand Gold Card -->
                    <div class="lg:col-span-6 bg-[#D1A568] p-8 sm:p-10 chamfer-top-right shadow-2xl flex flex-col justify-between space-y-4 text-black luxury-card" data-aos="fade-right">
                        <div>
                            <p class="text-xs uppercase font-bold tracking-widest text-black/70 mb-4">{{ $branch->restaurant_name ?? 'Lezzatos' }}</p>
                            
                            <div class="space-y-3">
                                <div>
                                    <input type="text" x-model="form.customer_name" required placeholder="Name"
                                           class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm">
                                </div>
                                <div>
                                    <input type="text" x-model="form.customer_phone" required placeholder="Phone Number"
                                           class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm">
                                </div>
                                <div>
                                    <input type="email" x-model="form.customer_email" placeholder="Email Address"
                                           class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm">
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <select x-model.number="form.guest_count" required class="w-full px-3 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] focus:outline-none shadow-sm">
                                        <option value="1">1 Person</option>
                                        <option value="2" selected>2 Persons</option>
                                        <option value="4">4 Persons</option>
                                        <option value="6">6 Persons</option>
                                        <option value="8">8 Persons</option>
                                        <option value="12">12+ Persons</option>
                                    </select>
                                    <select x-model="form.table_id" class="w-full px-3 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] focus:outline-none shadow-sm">
                                        <option value="">Select Table</option>
                                        @foreach($tables as $t)
                                        <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->floor_name }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Dark Calendar Card -->
                    <div class="lg:col-span-6 bg-[#141414] p-8 sm:p-10 chamfer-top-right border border-[#C5A880]/20 shadow-2xl flex flex-col justify-between space-y-6 luxury-card" data-aos="fade-left">
                        
                        <!-- Month Header -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-white font-serif text-sm font-bold border-b border-[#C5A880]/15 pb-3">
                                <button type="button" class="p-1 hover:text-[#C5A880]"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>
                                <span>{{ date('F Y') }}</span>
                                <button type="button" class="p-1 hover:text-[#C5A880]"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>
                            </div>

                            <!-- Date Picker input -->
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#A8988D] mb-1.5">Choose Date</label>
                                <input type="date" x-model="form.reservation_date" required min="{{ date('Y-m-d') }}"
                                       class="w-full px-3.5 py-2.5 rounded bg-[#1C1C1C] border border-[#C5A880]/30 text-xs font-semibold text-white focus:outline-none focus:border-[#C5A880]">
                            </div>

                            <!-- Time Select -->
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wider text-[#A8988D] mb-1.5">Select Time</label>
                                <select x-model="form.reservation_time" required
                                        class="w-full px-3.5 py-2.5 rounded bg-[#1C1C1C] border border-[#C5A880]/30 text-xs font-semibold text-white focus:outline-none focus:border-[#C5A880]">
                                    <option value="10:00 AM">10:00 AM (Morning)</option>
                                    <option value="01:00 PM">01:00 PM (Lunch)</option>
                                    <option value="02:00 PM">02:00 PM (Lunch)</option>
                                    <option value="06:30 PM">06:30 PM (Evening)</option>
                                    <option value="07:30 PM">07:30 PM (Dinner)</option>
                                    <option value="08:30 PM" selected>08:30 PM (Prime Dinner)</option>
                                    <option value="09:30 PM">09:30 PM (Late Dinner)</option>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Center CTA Button -->
                <div class="text-center pt-4" data-aos="fade-up">
                    <button type="submit" :disabled="isSubmitting"
                            class="px-12 py-3.5 rounded bg-[#D1A568] hover:bg-[#C5A880] text-black font-bold text-xs uppercase tracking-widest transition-all shadow-xl active:scale-98 gold-glow-btn cursor-pointer">
                        <span x-text="isSubmitting ? 'BOOKING...' : 'BOOK TABLE'"></span>
                    </button>
                </div>

            </form>

        </div>
    </section>

    <!-- ════ 2. OUR BOOKING PROCESS (4 STEP BADGES) ════ -->
    <section class="py-20 relative bg-[#0E0E0E] border-t border-[#C5A880]/15 text-center overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 space-y-12">
            <h2 class="font-serif text-3xl font-bold text-white tracking-tight" data-aos="fade-up">Our Booking Process</h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 relative">
                
                <div class="space-y-3 luxury-card p-4 rounded-2xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 rounded-full border-2 border-[#C5A880] flex items-center justify-center mx-auto text-[#C5A880] shadow-lg">
                        <i data-lucide="calendar" class="w-7 h-7"></i>
                    </div>
                    <h4 class="font-serif text-sm font-bold text-white">Choose</h4>
                    <p class="text-[11px] text-[#8C7D73]">Pick preferred date & table</p>
                </div>

                <div class="space-y-3 luxury-card p-4 rounded-2xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 rounded-full border-2 border-[#C5A880] flex items-center justify-center mx-auto text-[#C5A880] shadow-lg">
                        <i data-lucide="user" class="w-7 h-7"></i>
                    </div>
                    <h4 class="font-serif text-sm font-bold text-white">Detail</h4>
                    <p class="text-[11px] text-[#8C7D73]">Enter your contact information</p>
                </div>

                <div class="space-y-3 luxury-card p-4 rounded-2xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 rounded-full border-2 border-[#C5A880] flex items-center justify-center mx-auto text-[#C5A880] shadow-lg">
                        <i data-lucide="credit-card" class="w-7 h-7"></i>
                    </div>
                    <h4 class="font-serif text-sm font-bold text-white">Payment</h4>
                    <p class="text-[11px] text-[#8C7D73]">Free table reservation</p>
                </div>

                <div class="space-y-3 luxury-card p-4 rounded-2xl" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-16 h-16 rounded-full border-2 border-[#C5A880] flex items-center justify-center mx-auto text-[#C5A880] shadow-lg">
                        <i data-lucide="check-circle-2" class="w-7 h-7"></i>
                    </div>
                    <h4 class="font-serif text-sm font-bold text-white">Confirm</h4>
                    <p class="text-[11px] text-[#8C7D73]">Instant booking confirmation</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 3. RECOMMENDATION / PRIVATE DINING ════ -->
    <section class="py-24 bg-[#0B0B0B] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-6" data-aos="fade-right">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=800&q=80" 
                             alt="Private Dining Table" class="w-full h-80 sm:h-96 object-cover">
                    </div>
                </div>

                <div class="lg:col-span-6" data-aos="fade-left">
                    <div class="bg-white text-[#1A1A1A] p-8 sm:p-12 chamfer-top-right shadow-2xl space-y-4 luxury-card">
                        <p class="font-script text-2xl text-[#C5A880]">Recommendation</p>
                        <h2 class="font-serif text-3xl font-bold text-[#111]">Private Dining</h2>
                        <p class="text-xs sm:text-sm text-[#665D56] leading-relaxed">
                            Looking for an exclusive private celebration or a romantic candle light anniversary? Our private booths and dedicated butler services provide the ultimate dining luxury.
                        </p>
                        <a href="#home" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#C5A880] hover:text-[#111] transition-colors border-b border-[#C5A880] pb-0.5">
                            <span>Book Table</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 4. ABOUT & OPENING HOURS ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-6 space-y-6" data-aos="fade-right">
                    <p class="font-script text-3xl text-[#C5A880]">About</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white">{{ $branch->restaurant_name ?? 'Lezzatos' }}</h2>
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-3 text-xs text-[#A8988D]">
                            <div class="w-10 h-10 rounded-full border border-[#C5A880]/40 flex items-center justify-center text-[#C5A880]">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </div>
                            <span>{{ $contactData['phone'] ?? '+62 898245124' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-[#A8988D]">
                            <div class="w-10 h-10 rounded-full border border-[#C5A880]/40 flex items-center justify-center text-[#C5A880]">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <span>{{ $contactData['email'] ?? 'lezzatos@restaurant.com' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Opening Hours Table Box -->
                <div class="lg:col-span-6 bg-white text-[#1A1A1A] p-8 sm:p-10 rounded-2xl shadow-2xl space-y-4 luxury-card" data-aos="fade-left">
                    <h3 class="font-serif text-xl font-bold text-[#111] border-b pb-3">Opening Hours</h3>
                    
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <span>Monday - Friday</span>
                            <span class="font-bold text-[#111]">{{ $contactData['opening_hours']['mon_fri'] ?? '08:00 - 22:00' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-100">
                            <span>Saturday</span>
                            <span class="font-bold text-[#111]">{{ $contactData['opening_hours']['sat'] ?? '08:00 - 23:00' }}</span>
                        </div>
                        <div class="flex justify-between py-1 text-red-600 font-bold">
                            <span>Sunday</span>
                            <span>{{ $contactData['opening_hours']['sun'] ?? 'Closed' }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 5. CUSTOMER REVIEWS ════ -->
    <section class="py-20 bg-[#0B0B0B] border-t border-[#C5A880]/15 text-center" data-aos="fade-up">
        <div class="max-w-3xl mx-auto px-6 space-y-4">
            <p class="font-script text-3xl text-[#C5A880]">Testimonials</p>
            <h2 class="font-serif text-3xl font-bold text-white tracking-tight">Customer Reviews</h2>
            <div class="pt-6 relative">
                <div class="flex items-center justify-between">
                    <button @click="prevTestimonial()" class="text-[#C5A880] hover:text-white transition-colors p-2 hover:scale-125 transform"><i data-lucide="chevron-left" class="w-6 h-6"></i></button>
                    <div class="max-w-xl mx-auto px-4">
                        <p class="text-xs sm:text-sm text-[#A8988D] italic leading-relaxed" x-text="testimonials[activeTestimonial].quote"></p>
                        <div class="text-[#C5A880] text-4xl font-serif mt-3 mb-1">“</div>
                        <p class="font-bold text-xs uppercase tracking-wider text-white" x-text="testimonials[activeTestimonial].name"></p>
                    </div>
                    <button @click="nextTestimonial()" class="text-[#C5A880] hover:text-white transition-colors p-2 hover:scale-125 transform"><i data-lucide="chevron-right" class="w-6 h-6"></i></button>
                </div>
            </div>
        </div>
    </section>

@endsection
