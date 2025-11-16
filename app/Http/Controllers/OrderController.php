<?php

namespace App\Http\Controllers;

use App\Services\WhatsappService;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function createDirect($productId)
    {
        $product = Product::findOrFail($productId);
        return view('orders.direct', compact('product'));
    }

    public function storeDirect(Request $request, string $productId)
    {
        Log::info('storeDirect called', ['productId' => $productId, 'request' => $request->all()]);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($productId);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak habis');
        }

        DB::beginTransaction();
        try {
            // Buat order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_date' => now(),
                'status' => 'proses',
                'total_amount' => $product->price * $request->quantity,
            ]);

            Log::info('Order created', ['order_id' => $order->id]);

            // Buat order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);

            $product->decrement('stock', $request->quantity);

            DB::commit();

            Log::info('Order committed successfully', ['order_id' => $order->id]);


            $message = "🛒 Pesanan Baru!\n\n" .
                "Order ID: #{$order->id}\n" .
                "Produk: {$product->name}\n" .
                "Jumlah: {$request->quantity}\n" .
                "Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n" .
                "Cek detail di dashboard admin.";

            $whatsappService = new WhatsappService();
            $response = $whatsappService->sendMessageToAdmin($message);

            Log::info('WhatsApp notification sent', [
                'order_id' => $order->id,
                'response' => $response
            ]);


            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function createFromCart()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong');
        }

        $totalAmount = $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('orders.from-cart', compact('cart', 'totalAmount'));
    }

    public function storeFromCart(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with('items.product')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong');
        }

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            foreach ($cart->items as $item) {
                $totalAmount += $item->product->price * $item->quantity;
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_date' => now(),
                'status' => 'proses',
                'total_amount' => $totalAmount,
            ]);

            // Buat order items dari cart items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);

                // Update stok
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }
            // Build product list message
            $productList = "";
            foreach ($cart->items as $item) {
                $productList .= "- {$item->product->name} x{$item->quantity}\n";
            }

            $message = "🛒 Pesanan Baru!\n\n" .
                "Order ID: #{$order->id}\n" .
                "Produk:\n{$productList}" .
                "Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n\n" .
                "Cek detail di dashboard admin.";

            $whatsappService = new WhatsappService();
            $response = $whatsappService->sendMessageToAdmin($message);

            Log::info('WhatsApp notification sent', [
                'order_id' => $order->id,
                'response' => $response
            ]);

            $cart->items()->delete();

            DB::commit();
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $order = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
