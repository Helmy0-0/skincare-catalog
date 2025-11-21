@extends('customer.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Pesanan Saya</h1>
            <a href="{{ route('customer.home') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Lanjut Belanja
            </a>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    {{-- Header Order --}}
                    <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
                        <div class="flex gap-6">
                            <div>
                                <p class="text-sm text-gray-600">Order ID</p>
                                <p class="font-semibold">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal</p>
                                <p class="font-semibold">{{ $order->order_date->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total</p>
                                <p class="font-semibold text-blue-600">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        
                        <div>
                            <span class="px-4 py-2 rounded-full text-sm font-medium
                                @if($order->status == 'proses') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'dikirim') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800
                                @endif">
                                @if($order->status == 'proses') Diproses
                                @elseif($order->status == 'dikirim') Dikirim
                                @else Selesai
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Order Items --}}
                    <div class="px-6 py-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center gap-4 py-3 {{ !$loop->last ? 'border-b' : '' }}">
                            @if($item->product->image ?? false)
                            <img src="{{ asset($item->product->image_url) }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-16 h-16 object-cover rounded">
                            @else
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @endif
                            
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-800">
                                    {{ $item->product->name ?? 'Produk tidak tersedia' }}
                                </h3>
                                <p class="text-sm text-gray-600">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div class="font-semibold text-gray-800">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t flex justify-between items-center">
                        <div class="flex gap-4 items-center text-sm text-gray-600">
                            <span>
                                <i class="fas fa-box"></i>
                                {{ $order->items->count() }} item
                            </span>
                            <span>•</span>
                            <span>{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <a href="{{ route('orders.show', $order->id) }}" 
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="inline-block p-4 bg-gray-100 rounded-full mb-4">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Pesanan</h3>
                <p class="text-gray-600 mb-6">Anda belum melakukan pemesanan apapun</p>
                <a href="{{ route('orders.index') }}" 
                   class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>

<style>
@media (max-width: 640px) {
    .bg-gray-50.px-6.py-4 {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
}
</style>
@endsection