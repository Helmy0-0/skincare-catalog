<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Exclusive')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="font-sans bg-white text-gray-800">
  {{-- navbar --}}
  <nav class="border-b border-gray-100 bg-white">
    <div class="max-w-full mx-auto px-6 flex justify-between items-center h-16">
      {{-- Nama Brand --}}
      <a href="#" class="text-2xl font-bold text-blue-600">
        Mirglow
      </a>

      {{-- Link Navigasi --}}
      <div class="hidden md:flex items-center gap-8">
        <a href="{{ route('customer.home') }}" class="text-gray-700 hover:text-blue-600 transition">Home</a>
        <a href="{{ route('customer.products') }}" class="text-gray-700 hover:text-blue-600 transition">Products</a>
        <a href="{{ route('customer.about') }}" class="text-gray-700 hover:text-blue-600 transition">About</a>
      </div>

      {{-- User Profile / Auth Section --}}
      <div class="flex items-center gap-4">
        @auth
          <a href="{{ route('cart.index') }}"
            class="relative flex item-center text-gray-700 hover:text-blue-600 transition">
            <svg xlmns="http://www.w3.org/2000/svg" fill="none" viewbox="0 0 24 24" stroke-width="2" stroke="currentColor"
              class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8H18M7 13l-2-8M10 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z" />
            </svg>
            @php
              $cartCount = \App\Models\CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))->count();
            @endphp
            @if ($cartCount > 0)
              <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-semibold rounded-full px-1.5 py-0.5">
                {{ $cartCount }}
              </span>
            @endif
          </a>
          <div class="relative flex justify-end">
            <button id="user-menu-button" class="flex items-center gap-2 focus:outline-none cursor-pointer">
              <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->role }}</p>
              </div>
              <div
                class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold hover:bg-blue-200 transition-colors">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
              </div>
              <svg class="w-4 h-4 text-gray-500 ml-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            {{-- Dropdown --}}
            <div id="user-menu"
              class="hidden absolute top-12 right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg z-50">

            @if (Auth::user()->role === 'admin')
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    Masuk Admin
            </a>
             @endif

            <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                Pesanan Saya
            </a>

            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                Profile
            </a>

            <form action="{{ route('logout') }}" method="POST" class="block">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                  Logout
                </button>
            </form>
            </div>
          </div>
        @else
          <a href="{{ route('login') }}" class="text-sm text-blue-600 font-medium hover:underline">Login</a>
        @endauth
      </div>
    </div>
  </nav>

  {{-- content --}}
  <main>
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="border-t border-gray-100 mt-16">
    <div class="max-w-7xl mx-auto px-6 py-8 text-center text-sm text-gray-500">
      © {{ date('Y') }} Exclusive. All rights reserved.
    </div>
  </footer>

  {{-- Dropdown Script --}}
  <script>
    const userMenuBtn = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu');

    if (userMenuBtn && userMenu) {
      userMenuBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userMenu.classList.toggle('hidden');
      });

      document.addEventListener('click', (e) => {
        if (!userMenuBtn.contains(e.target) && !userMenu.contains(e.target)) {
          userMenu.classList.add('hidden');
        }
      });
    }

    document.addEventListener('livewire:initialized', () => {
      Livewire.on('toast', ({ message }) => {
        const toast = document.createElement('div');
        toast.textContent = message;
        toast.className = 'fixed bottom-5 right-5 bg-[#9ece7c] text-black px-4 py-4 rounded-lg shadow-lg animate-fade-in';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
      });
    });
  </script>

  <style>
    @keyframes fade-in {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-fade-in {
      animation: fade-in 0.2s ease-out;
    }
  </style>
  @livewireScripts
</body>

</html>
