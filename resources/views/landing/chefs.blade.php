@extends('landing.layout')

@section('title', 'Our Chef — Lezzatos Master Culinary Team')

@section('content')

    <!-- ════ PAGE HEADER BANNER ════ -->
    <section class="page-header-banner pt-40 pb-20 text-center relative border-b border-[#C5A880]/20" data-aos="fade-down">
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <h1 class="font-serif text-4xl sm:text-5xl font-bold text-white tracking-wide">
                Our Chef
            </h1>
        </div>
    </section>

    <!-- ════ 1. MEET OUR INNOVATIVE PERSON (6 CHEFS GRID) ════ -->
    <section class="py-24 bg-[#0B0B0B] relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            
            <div class="text-center space-y-1 mb-16" data-aos="fade-up">
                <p class="font-script text-3xl text-[#C5A880]">Chef</p>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Meet Our Innovative Person
                </h2>
                <p class="text-xs text-[#8C7D73] max-w-md mx-auto">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.
                </p>
            </div>

            <!-- 6 Chefs Grid (3x2) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                
                <!-- Chef 1 -->
                <div class="rounded-2xl overflow-hidden border border-[#C5A880]/20 group shadow-xl luxury-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="luxury-img-zoom h-80">
                        <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&w=600&q=80" 
                             alt="Chef" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Chef 2 (Active/Hover Card) -->
                <div class="bg-[#D1A568] rounded-2xl p-8 chamfer-top-right flex flex-col items-center justify-center text-center text-black shadow-2xl luxury-card" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="font-serif text-2xl font-bold mb-1">Dany William</h3>
                    <p class="text-xs font-semibold mb-6 opacity-80">Executive Head Chef</p>
                    <div class="flex items-center gap-3">
                        <a href="#" class="w-8 h-8 rounded-full border border-black/30 flex items-center justify-center hover:bg-black hover:text-white transition-colors"><i data-lucide="facebook" class="w-4 h-4"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full border border-black/30 flex items-center justify-center hover:bg-black hover:text-white transition-colors"><i data-lucide="instagram" class="w-4 h-4"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full border border-black/30 flex items-center justify-center hover:bg-black hover:text-white transition-colors"><i data-lucide="twitter" class="w-4 h-4"></i></a>
                    </div>
                </div>

                <!-- Chef 3 -->
                <div class="rounded-2xl overflow-hidden border border-[#C5A880]/20 group shadow-xl luxury-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="luxury-img-zoom h-80">
                        <img src="https://images.unsplash.com/photo-1583394293214-28ded15ee548?auto=format&fit=crop&w=600&q=80" 
                             alt="Chef" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Chef 4 -->
                <div class="rounded-2xl overflow-hidden border border-[#C5A880]/20 group shadow-xl luxury-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="luxury-img-zoom h-80">
                        <img src="https://images.unsplash.com/photo-1581299894007-aaa50297cf16?auto=format&fit=crop&w=600&q=80" 
                             alt="Pastry Chef" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Chef 5 -->
                <div class="rounded-2xl overflow-hidden border border-[#C5A880]/20 group shadow-xl luxury-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="luxury-img-zoom h-80">
                        <img src="https://images.unsplash.com/photo-1566554273541-37a9ca77b91f?auto=format&fit=crop&w=600&q=80" 
                             alt="Grill Chef" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Chef 6 -->
                <div class="rounded-2xl overflow-hidden border border-[#C5A880]/20 group shadow-xl luxury-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="luxury-img-zoom h-80">
                        <img src="https://images.unsplash.com/photo-1607631568010-a87245c0daf8?auto=format&fit=crop&w=600&q=80" 
                             alt="Master Chef" class="w-full h-full object-cover">
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ════ 2. STATISTIC / BEST FOR CUSTOMERS ════ -->
    <section class="py-20 bg-[#0E0E0E] border-t border-[#C5A880]/15 relative">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Left: White Chamfered Card with 4 Stats -->
                <div class="lg:col-span-6 bg-white text-[#1A1A1A] p-8 sm:p-10 chamfer-top-right shadow-2xl space-y-6 luxury-card" data-aos="fade-right">
                    <p class="font-script text-2xl text-[#C5A880]">Statistic</p>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[#111] leading-tight">
                        We Will Provide the Best for Customers
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-6 pt-2 border-t border-gray-100">
                        <div>
                            <p class="font-serif text-3xl font-bold text-[#111]">12</p>
                            <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Branches</p>
                        </div>
                        <div>
                            <p class="font-serif text-3xl font-bold text-[#111]">10</p>
                            <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Years</p>
                        </div>
                        <div>
                            <p class="font-serif text-3xl font-bold text-[#111]">50+</p>
                            <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Facilities</p>
                        </div>
                        <div>
                            <p class="font-serif text-3xl font-bold text-[#111]">200+</p>
                            <p class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Delicacies</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Chef Crafting Dish Image -->
                <div class="lg:col-span-6" data-aos="fade-left">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=800&q=80" 
                             alt="Chef Crafting" class="w-full h-80 object-cover">
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ════ 3. TIPS & TRICKS (3 BLOG CARDS) ════ -->
    <section class="py-24 bg-[#0B0B0B] border-t border-[#C5A880]/15">
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

    <!-- ════ 4. OUR GALLERY SECTION ════ -->
    <section class="py-24 bg-[#0E0E0E] border-t border-[#C5A880]/15 text-center">
        <div class="max-w-7xl mx-auto px-6 sm:px-10 space-y-8">
            <div class="space-y-1" data-aos="fade-up">
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight">Our Gallery</h2>
                <p class="text-xs text-[#8C7D73]">A glimpse into our royal kitchen mastery and luxury dining atmosphere.</p>
            </div>

            <!-- Top Large Video Image -->
            <div class="relative h-96 rounded-3xl overflow-hidden border border-[#C5A880]/20 shadow-2xl flex items-center justify-center" data-aos="zoom-in">
                <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?auto=format&fit=crop&w=1600&q=80" 
                     alt="Gallery Tour" class="absolute inset-0 w-full h-full object-cover brightness-50">
                <button @click="videoModalOpen = true" 
                        class="relative z-10 w-20 h-20 rounded-full border-2 border-[#C5A880] flex items-center justify-center text-[#C5A880] hover:scale-110 hover:bg-[#C5A880] hover:text-black transition-all shadow-2xl cursor-pointer">
                    <i data-lucide="play" class="w-8 h-8 fill-current ml-1"></i>
                </button>
            </div>

            <!-- Bottom 3 Photos Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" data-aos="fade-up">
                <div class="rounded-2xl overflow-hidden shadow-xl border border-[#C5A880]/20 luxury-img-zoom">
                    <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?auto=format&fit=crop&w=600&q=80" alt="Service" class="w-full h-48 object-cover">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xl border border-[#C5A880]/20 luxury-img-zoom">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80" alt="Guests" class="w-full h-48 object-cover">
                </div>
                <div class="rounded-2xl overflow-hidden shadow-xl border border-[#C5A880]/20 luxury-img-zoom">
                    <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80" alt="Dining" class="w-full h-48 object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- ════ 5. RESERVATION CTA BANNER ════ -->
    <section class="py-20 bg-[#0B0B0B] border-t border-[#C5A880]/15" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6 sm:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center bg-[#111111] p-8 sm:p-12 rounded-3xl border border-[#C5A880]/20">
                <div class="lg:col-span-6 space-y-4">
                    <p class="font-script text-3xl text-[#C5A880]">Reservation</p>
                    <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">We will Serve You Better</h2>
                    <p class="text-xs sm:text-sm text-[#8C7D73]">Reserve a table with our master chef's customized tasting menu tailored specifically for your special occasion.</p>
                    <div class="pt-2">
                        <a href="{{ route('reservation') }}" class="inline-block px-8 py-3 rounded bg-[#D1A568] hover:bg-[#C5A880] text-black font-bold text-xs uppercase tracking-wider transition-all gold-glow-btn">
                            BOOK A TABLE
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden border border-[#C5A880]/30 shadow-2xl luxury-img-zoom">
                        <img src="https://images.unsplash.com/photo-1583394293214-28ded15ee548?auto=format&fit=crop&w=800&q=80" alt="Head Waiter" class="w-full h-72 object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
