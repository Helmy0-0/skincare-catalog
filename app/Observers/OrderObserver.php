<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    protected $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Cek apakah status berubah
        if ($order->isDirty('status')) {
            $this->sendStatusNotification($order);
        }
    }

    /**
     * Kirim notifikasi WhatsApp ke customer
     */
    protected function sendStatusNotification(Order $order): void
    {
        try {
            $user = $order->user;
            
            // Pastikan user punya nomor WhatsApp
            if (!$user || !$user->phone) {
                Log::warning("Order #{$order->id}: User tidak memiliki nomor WhatsApp");
                return;
            }


            // Buat pesan berdasarkan status
            $message = $this->generateStatusMessage($order, $user);

            // Kirim pesan WhatsApp
            $response = $this->whatsappService->sendMessageToCustomer($message, $user->phone);

            if ($response) {
                Log::info("WhatsApp notification sent for Order #{$order->id} to {$user->phone}");
            } else {
                Log::error("Failed to send WhatsApp notification for Order #{$order->id}");
            }

        } catch (\Exception $e) {
            Log::error("Error sending WhatsApp notification: " . $e->getMessage());
        }
    }

    /**
     * Generate pesan berdasarkan status order
     */
    protected function generateStatusMessage(Order $order, $user): string
    {
        $totalAmount = (float) $order->total_amount;
        $formattedTotal = number_format($totalAmount, 0, ',', '.');
        
        $statusMessages = [
            'proses' => "🔄 *Status Pesanan Diperbarui*\n\nHalo {$user->name},\n\nPesanan Anda dengan nomor *#{$order->id}* sedang dalam proses.\n\n📅 Tanggal Order: {$order->order_date->format('d/m/Y')}\n💰 Total: Rp {$formattedTotal}\n\nTerima kasih telah berbelanja! 🙏",
            
            'dikirim' => "🚚 *Pesanan Sedang Dikirim*\n\nHalo {$user->name},\n\nKabar baik! Pesanan Anda dengan nomor *#{$order->id}* sudah dikirim.\n\n📅 Tanggal Order: {$order->order_date->format('d/m/Y')}\n💰 Total: Rp {$formattedTotal}\n\nMohon ditunggu ya! 📦",
            
            'selesai' => "✅ *Pesanan Selesai*\n\nHalo {$user->name},\n\nPesanan Anda dengan nomor *#{$order->id}* telah selesai.\n\n📅 Tanggal Order: {$order->order_date->format('d/m/Y')}\n💰 Total: Rp {$formattedTotal}\n\nTerima kasih telah berbelanja dengan kami! 💚\n\nSemoga Anda puas dengan produk kami. 😊"
        ];

        return $statusMessages[$order->status] ?? "Status pesanan Anda telah diperbarui menjadi: {$order->status}";
    }
}
