<div class="xl:max-w-4xl max-w-2xl mx-auto p-5 m-2 py-10 flex flex-col">
    <h2 class="text-xl flex font-semibold mb-6">Keranjang Belanja</h2>

    @if($cart && $cart->items->count())
        <table class="w-full  text-left border-collapse">
            <thead>
                <tr>
                    <th class="border-b p-2">Produk</th>
                    <th class="border-b p-2">Harga</th>
                    <th class="border-b p-2">Qty</th>
                    <th class="border-b p-2">Total</th>
                    <th class="border-b p-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart->items as $item)
                    <tr class="text-sm md:text-lg">
                        <td class="xl:p-2 ">{{ $item->product->name }}</td>
                        <td class="xl:p-2 ">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                        <td class="p-2">
                            <div class="flex items-center space-x-2">
                                {{-- Decrease --}}
                                <button wire:click="decreaseQuantity({{ $item->id }})"
                                    class="px-2 py-1 bg-gray-300 rounded">-
                                </button>

                                {{-- Quantity --}}
                                <span>{{ $item->quantity }}</span>

                                {{-- Increase --}}
                                <button wire:click="increaseQuantity({{ $item->id }})"
                                    class="px-2 py-1 bg-gray-300 rounded">+</button>
                            </div>
                        </td>
                        <td class="xl:p-2">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                        <td class="p-2">
                            <button wire:click="removeItem({{ $item->id }})" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @php
            $total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);
        @endphp

        <div class="mt-6 text-right font-semibold text-lg">
            Total: Rp {{ number_format($total, 0, ',', '.') }}
        </div>

        <div class="mt-6 text-right">
            <a href="{{ route('orders.create-from-cart') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                Checkout
            </a>
        </div>
    @else
        <p class="text-gray-500 text-center">Keranjang Kosong</p>
    @endif
</div>