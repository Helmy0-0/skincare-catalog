@extends('customer.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Konfirmasi Pesanan</h2>

        <div class="mb-6">
            <h3 class="font-semibold text-lg mb-4">Item Pesanan:</h3>
            
            @foreach($cart->items as $item)
            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg mb-3">
                <div class="flex gap-4 flex-1">
                    @if($item->product->image)
                    <img src="{{ asset($item->product->image_url) }}" 
                         alt="{{ $item->product->name }}" 
                         class="w-16 h-16 object-cover rounded">
                    @endif
                    <div>
                        <h4 class="font-medium">{{ $item->product->name }}</h4>
                        <p class="text-gray-600 text-sm">
                            Rp {{ number_format($item->product->price, 0, ',', '.') }} x {{ $item->quantity }}
                        </p>
                    </div>
                </div>
                <div class="font-semibold">
                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>

        <div class="border-t pt-4 mb-6">
            <div class="flex justify-between items-center text-xl font-bold">
                <span>Total Pembayaran:</span>
                <span class="text-blue-600">
                    Rp {{ number_format($totalAmount, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <form action="{{ route('orders.store-from-cart') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Catatan (Opsional)</label>
                <textarea name="notes" 
                          rows="3"
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Tambahkan catatan untuk pesanan Anda..."></textarea>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('cart.index') }}" 
                   class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg text-center hover:bg-gray-300">
                    Kembali ke Keranjang
                </a>
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Proses Pesanan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
