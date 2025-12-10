<div class="space-y-6">
    {{-- Informasi Pesanan --}}
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Informasi Pesanan</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Order</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $order->order_date->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nama Pelanggan</p>
                <p class="font-medium text-gray-900 dark:text-white">{{ $order->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($order->status === 'proses') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                    @elseif($order->status === 'dikirim') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                    @elseif($order->status === 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total</p>
                <p class="font-bold text-lg text-gray-900 dark:text-white">Rp
                    {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Detail Produk --}}
    <div>
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Detail Produk</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nama Produk
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Harga Satuan
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Subtotal
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $item->product->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $item->quantity }} pcs
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                            Total Keseluruhan:
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>