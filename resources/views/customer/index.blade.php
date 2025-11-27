@extends('customer.layouts.app')

@section('title', 'Home - Mirglow')

@section('content')
  <!-- Hero Section with Carousel -->
  <section class="relative h-[500px] overflow-hidden">
    <!-- Carousel Container -->
    <div class="carousel-container relative h-full">
      @foreach ($banners as $index => $banner)
        <div
          class="carousel-slide {{ $index === 0 ? 'active' : 'opacity-0' }} absolute inset-0 transition-opacity duration-1000"
          style="background-image: url('{{ $banner->image_url }}'); background-size: cover; background-position: center;">

          <div class="absolute inset-0 bg-black/30"></div>
          <div class="relative z-10 max-w-7xl mx-auto px-6 h-full flex flex-col justify-center">

            {{-- TEXT FIXED, NO CHANGE --}}
            <h1 class="text-4xl md:text-5xl font-bold leading-tight text-white drop-shadow-lg">
              Temukan <br>
              <span class="text-blue-400">Kecantikanmu</span> Sekarang
            </h1>
            <p class="text-gray-100 mt-4 max-w-md drop-shadow">
              Jelajahi produk skincare terbaik dari brand terpercaya dengan harga yang bersahabat.
            </p>
            <a href="{{ route('customer.products') }}"
              class="self-start inline-block mt-6 bg-blue-600 text-white px-6 py-3 font-semibold rounded-lg hover:bg-blue-700 transition">
              Lihat Produk
            </a>

          </div>
        </div>
      @endforeach
    </div>

    <!-- Navigation Arrows -->
    <button id="prevSlide"
      class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/30 hover:bg-white/50 text-white p-3 rounded-full backdrop-blur-sm transition">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
      </svg>
    </button>
    <button id="nextSlide"
      class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/30 hover:bg-white/50 text-white p-3 rounded-full backdrop-blur-sm transition">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
      </svg>
    </button>

    <!-- Dots Indicator -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex space-x-2">
      @foreach ($banners as $index => $banner)
        <button class="carousel-dot {{ $index === 0 ? 'active bg-white' : 'bg-white/50' }} w-3 h-3 rounded-full transition"
          data-slide="{{ $index }}"></button>
      @endforeach
    </div>
  </section>

  <!-- Carousel JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const slides = document.querySelectorAll('.carousel-slide');
      const dots = document.querySelectorAll('.carousel-dot');
      const prevBtn = document.getElementById('prevSlide');
      const nextBtn = document.getElementById('nextSlide');
      let currentSlide = 0;
      let autoPlayInterval;

      function showSlide(index) {
        // Reset all slides
        slides.forEach(slide => {
          slide.classList.remove('active');
          slide.classList.add('opacity-0');
        });

        // Reset all dots
        dots.forEach(dot => {
          dot.classList.remove('active', 'bg-white');
          dot.classList.add('bg-white/50');
        });

        // Show current slide
        slides[index].classList.add('active');
        slides[index].classList.remove('opacity-0');

        // Highlight current dot
        dots[index].classList.add('active', 'bg-white');
        dots[index].classList.remove('bg-white/50');
      }

      function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
      }

      function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
      }

      function startAutoPlay() {
        autoPlayInterval = setInterval(nextSlide, 5000); // Change slide every 3 seconds
      }

      function stopAutoPlay() {
        clearInterval(autoPlayInterval);
      }

      // Navigation buttons
      nextBtn.addEventListener('click', () => {
        nextSlide();
        stopAutoPlay();
        startAutoPlay(); // Restart autoplay after manual navigation
      });

      prevBtn.addEventListener('click', () => {
        prevSlide();
        stopAutoPlay();
        startAutoPlay();
      });

      // Dot navigation
      dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
          currentSlide = index;
          showSlide(currentSlide);
          stopAutoPlay();
          startAutoPlay();
        });
      });

      startAutoPlay();
    });
  </script>

  {{-- Featured Products --}}
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <h2 class="text-2xl font-semibold text-gray-900">Produk Unggulan</h2>
      <p class="text-gray-500 mt-2">Pilihan terbaik untuk perawatan kulitmu</p>

      <div class="mt-10 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-8">

        @forelse($featuredProducts as $product)
          <a href="{{ route('customer.product.detail', $product->id) }}">
            <div
              class="group bg-white/90 backdrop-blur border border-gray-100 rounded-2xl overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
              <div class="relative w-full h-52 bg-gray-100 overflow-hidden">
                <img src="{{ asset(path: $product->image_url) }}" alt="{{ $product->name }}" alt="{{ $product->name }}"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
              </div>

              <div class="p-4 text-left">
                <h3 class="text-gray-800 font-semibold text-sm line-clamp-2">{{ $product->name }}</h3>
                <p class="text-blue-600 font-bold text-base mt-3">
                  Rp{{ number_format($product->price, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-500 mt-1">Terjual: {{$product->order_items_sum_quantity ?? 0  }}</p>
              </div>
            </div>
          </a>
        @empty
          <p class="text-gray-500 col-span-full">Belum ada produk unggulan.</p>
        @endforelse

      </div>
    </div>
  </section>

  {{-- Products Section --}}
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-xl font-semibold text-gray-900 mb-6">Produk</h2>

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-8">

        @foreach ($products as $product)
          <a href="{{ route('customer.product.detail', $product->id) }}">
            <div
              class="group bg-white/90 backdrop-blur border border-gray-100 rounded-2xl overflow-hidden shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 cursor-pointer">
              <div class="relative w-full h-48 bg-gray-100 overflow-hidden">
                <img src="{{ asset(path: $product->image_url) }}" alt="{{ $product->name }}"
                  class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
              </div>

              <div class="p-4">
                <h3 class="text-gray-800 font-semibold text-sm line-clamp-2">{{ $product->name }}</h3>
                <p class="text-blue-600 font-bold text-base mt-3">
                  Rp{{ number_format($product->price, 0, ',', '.') }}
                </p>
              </div>
            </div>
          </a>
        @endforeach

      </div>

      {{-- Tombol Lihat Lebih Banyak --}}
      <div class="text-center mt-10">
        <a href="{{ route('customer.products') }}" class="text-blue-600 font-semibold hover:underline">Lihat Lebih Banyak
          →</a>
      </div>

    </div>
  </section>

@endsection
