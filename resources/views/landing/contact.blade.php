@extends('landing.layout')

@section('title', 'Contact Us — ' . ($branch->restaurant_name ?? 'Lezzatos'))

@section('content')

    <!-- ════ PAGE HEADER BANNER ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20" data-aos="fade-down">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                Contact Us
            </h1>
        </div>
    </section>

    <!-- ════ 1. GET IN TOUCH WITH US & CONTACT FORM ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Left: Contact Details -->
                <div class="lg:col-span-5 space-y-6" data-aos="fade-right">
                    <div>
                        <p class="font-script text-3xl text-[#C5A880]">Reach Us</p>
                        <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                            Get in Touch with Us
                        </h2>
                        <p class="text-xs sm:text-sm text-[#8C7D73] mt-3 leading-relaxed">
                            Have an inquiry about table bookings, private party catering, bespoke chef menus, or event reservations? Connect directly with our team.
                        </p>
                    </div>

                    <div class="space-y-4 pt-2">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full border border-[#C5A880]/40 flex items-center justify-center text-[#C5A880] shrink-0">
                                <i data-lucide="phone" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">{{ $contactData['phone'] ?? '+62 898245124' }}</p>
                                <p class="text-[10px] text-gray-500">Telephone</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full border border-[#C5A880]/40 flex items-center justify-center text-[#C5A880] shrink-0">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">{{ $contactData['email'] ?? 'lezzatos@restaurant.com' }}</p>
                                <p class="text-[10px] text-gray-500">Email</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full border border-[#C5A880]/40 flex items-center justify-center text-[#C5A880] shrink-0">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-white">{{ $contactData['address'] ?? 'Braga St 28, Bandung, West Java' }}</p>
                                <p class="text-[10px] text-gray-500">Location</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Sand Gold Contact Form -->
                <div class="lg:col-span-7" data-aos="fade-left">
                    <div class="bg-[#D1A568] p-8 sm:p-10 chamfer-top-right shadow-2xl text-black luxury-card">
                        <div class="text-center mb-6">
                            <h3 class="font-serif text-2xl sm:text-3xl font-bold text-white">Contact Form</h3>
                        </div>

                        <form @submit.prevent="alert('Thank you! Your message has been sent successfully.');" class="space-y-3.5">
                            <div>
                                <input type="text" required placeholder="Name" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm">
                            </div>
                            <div>
                                <input type="email" required placeholder="Email" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <input type="text" placeholder="Phone Number" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm">
                                <input type="text" placeholder="Subject" class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none shadow-sm">
                            </div>
                            <div>
                                <textarea rows="4" required placeholder="Message..." class="w-full px-3.5 py-2.5 rounded bg-white text-xs font-semibold text-[#1A1A1A] placeholder-gray-500 focus:outline-none resize-none shadow-sm"></textarea>
                            </div>
                            <div class="pt-2">
                                <button type="submit" class="w-full py-3.5 rounded bg-white hover:bg-gray-100 text-[#111] font-bold text-xs uppercase tracking-widest transition-all shadow-xl active:scale-98 gold-glow-btn cursor-pointer">
                                    SUBMIT
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 2. OPENING HOURS & INTERIOR COLLAGE ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <!-- Left: White Opening Hours Card -->
                <div class="lg:col-span-5 bg-white text-[#1A1A1A] p-8 sm:p-10 chamfer-top-right shadow-2xl space-y-4 luxury-card" data-aos="fade-right">
                    <p class="font-script text-2xl text-[#C5A880] mb-2">Opening Hours</p>

                    <div class="space-y-2.5 text-xs">
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

                <!-- Right: Interior Photos Collage -->
                <div class="lg:col-span-7 grid grid-cols-2 gap-4" data-aos="fade-left">
                    <div class="rounded-2xl overflow-hidden shadow-xl border border-[#C5A880]/20 luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80" alt="Interior" class="w-full h-48 object-cover">
                    </div>
                    <div class="row-span-2 rounded-2xl overflow-hidden shadow-xl border border-[#C5A880]/20 luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=600&q=80" alt="Dining Table" class="w-full h-full object-cover">
                    </div>
                    <div class="rounded-2xl overflow-hidden shadow-xl border border-[#C5A880]/20 luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80" alt="Lounge" class="w-full h-48 object-cover">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 3. OUR CHEF WILL MAKE YOU SATISFYING ════ -->
    <section class="py-24 bg-[#0B0B0B] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                
                <div class="lg:col-span-5 space-y-4" data-aos="fade-right">
                    <p class="font-script text-3xl text-[#C5A880]">Discover</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                        Our Chef will Make You Satisfying
                    </h2>
                    <p class="text-xs sm:text-sm text-[#8C7D73] leading-relaxed">
                        Experience bespoke culinary artistry and hospitality excellence every time you visit.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('our-menu') }}" class="inline-block px-8 py-3 rounded bg-[#D1A568] hover:bg-[#C5A880] text-black font-bold text-xs uppercase tracking-wider transition-all gold-glow-btn">
                            ORDER NOW
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-7" data-aos="fade-left">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&w=800&q=80" 
                             alt="Chefs Team" class="w-full h-80 sm:h-96 object-cover">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 4. SOCIAL MEDIA ICONS BAR ════ -->
    <section class="py-8 bg-[#111111] border-t border-b border-[#C5A880]/15" data-aos="fade-up">
        <div class="max-w-4xl mx-auto px-6 flex items-center justify-center gap-8 text-[#C5A880]">
            <a href="{{ $contactData['social']['facebook'] ?? '#' }}" class="hover:text-white transition-colors hover:scale-125 transform"><i data-lucide="facebook" class="w-6 h-6"></i></a>
            <a href="{{ $contactData['social']['twitter'] ?? '#' }}" class="hover:text-white transition-colors hover:scale-125 transform"><i data-lucide="twitter" class="w-6 h-6"></i></a>
            <a href="{{ $contactData['social']['instagram'] ?? '#' }}" class="hover:text-white transition-colors hover:scale-125 transform"><i data-lucide="instagram" class="w-6 h-6"></i></a>
            <a href="{{ $contactData['social']['youtube'] ?? '#' }}" class="hover:text-white transition-colors hover:scale-125 transform"><i data-lucide="youtube" class="w-6 h-6"></i></a>
            <a href="{{ $contactData['social']['whatsapp'] ?? '#' }}" class="hover:text-white transition-colors hover:scale-125 transform"><i data-lucide="message-circle" class="w-6 h-6"></i></a>
        </div>
    </section>

    <!-- ════ 5. LUXURY LOCATION MAP SECTION ════ -->
    <section class="py-16 bg-[#080808] border-b border-[#C5A880]/15 text-center" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="relative h-96 rounded-3xl overflow-hidden border border-[#C5A880]/30 shadow-2xl flex items-center justify-center bg-[#121212]">
                <div class="absolute inset-0 bg-cover bg-center opacity-60" style="background-image: url('https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=1600&q=80');"></div>
                
                <div class="relative z-10 flex flex-col items-center animate-bounce">
                    <div class="w-12 h-12 rounded-full bg-[#D1A568] flex items-center justify-center text-black shadow-2xl border-2 border-white">
                        <i data-lucide="map-pin" class="w-6 h-6 fill-current"></i>
                    </div>
                    <span class="mt-2 px-3 py-1 rounded-full bg-black/90 border border-[#C5A880] text-[10px] font-bold text-[#C5A880] tracking-wider">
                        {{ $branch->restaurant_name ?? 'Lezzatos' }}
                    </span>
                </div>
            </div>
        </div>
    </section>

@endsection
