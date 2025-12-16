<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\AlamatUser;
use App\Models\JenisEkspedisi;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Notification;

class CheckoutCustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');
    }

    /**
     * Display checkout page
     */
    public function index()
    {
        $keranjang = Keranjang::with(['barang.gambarBarangs', 'barang.kategori'])
                             ->where('id_user', Auth::id())
                             ->get();

        if ($keranjang->isEmpty()) {
            return redirect()->route('keranjang')->with('error', 'Keranjang Anda kosong!');
        }

        // Check stock for all items
        foreach ($keranjang as $item) {
            if ($item->barang->stok < $item->jumlah) {
                return redirect()->route('keranjang')->with('error', 'Stok ' . $item->barang->nama_barang . ' tidak mencukupi! Stok tersedia: ' . $item->barang->stok);
            }
        }

        $user = Auth::user();
        $alamatUsers = AlamatUser::where('id_user', $user->id)->get();
        $jenisEkspedisi = JenisEkspedisi::all();

        $subtotal = $keranjang->sum('subtotal');
        $total = $subtotal;

        return view('checkout_pembeli', compact('keranjang', 'user', 'alamatUsers', 'jenisEkspedisi', 'subtotal', 'total'));
    }

    /**
     * Create order and get Midtrans snap token
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'alamat_user_id' => 'required|exists:alamat_users,id',
            'nama_penerima' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'total_amount' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Get selected address
            $alamatUser = AlamatUser::where('id', $request->alamat_user_id)
                                    ->where('id_user', Auth::id())
                                    ->firstOrFail();

            // Get cart items
            $keranjang = Keranjang::with('barang')
                                ->where('id_user', Auth::id())
                                ->get();

            if ($keranjang->isEmpty()) {
                return response()->json([
                    'error' => 'Keranjang Anda kosong!'
                ], 400);
            }

            // Check stock
            foreach ($keranjang as $item) {
                if ($item->barang->stok < $item->jumlah) {
                    return response()->json([
                        'error' => 'Stok ' . $item->barang->nama_barang . ' tidak mencukupi! Stok tersedia: ' . $item->barang->stok
                    ], 400);
                }
            }

            // Build address string
            $alamatLengkap = $alamatUser->alamat . ', ' . $alamatUser->kecamatan . ', ' .
                           $alamatUser->kabupaten . ', ' . $alamatUser->provinsi . ' ' . $alamatUser->kode_pos;

            // Generate transaction code
            $kodeTransaksi = 'TRX-' . time() . '-' . Auth::id();

            // Create order with status waiting for payment
            $penjualan = Penjualan::create([
                'id_user' => Auth::id(),
                'kode_transaksi' => $kodeTransaksi,
                'snap_token' => '',
                'alamat_pengiriman' => $alamatLengkap,
                'status' => 'menunggu_pembayaran',
                'total_harga' => $request->total_amount
            ]);

            // Create order details
            $items = [];
            foreach ($keranjang as $item) {
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id,
                    'id_produk' => $item->id_barang,
                    'qty' => $item->jumlah,
                    'harga' => $item->barang->harga,
                    'subtotal' => $item->barang->harga * $item->jumlah
                ]);

                $items[] = [
                    'id' => $item->barang->id,
                    'price' => $item->barang->harga,
                    'quantity' => $item->jumlah,
                    'name' => $item->barang->nama_barang
                ];
            }

            // Prepare Midtrans transaction
            $user = Auth::user();
            $params = [
                'transaction_details' => [
                    'order_id' => $kodeTransaksi,
                    'gross_amount' => $request->total_amount,
                ],
                'item_details' => $items,
                'customer_details' => [
                    'first_name' => $request->nama_penerima,
                    'email' => $user->email,
                    'phone' => $request->no_telp,
                    'shipping_address' => [
                        'address' => $alamatUser->alamat,
                        'city' => $alamatUser->kabupaten,
                        'postal_code' => $alamatUser->kode_pos,
                    ]
                ]
            ];

            // Get Snap token
            $snapToken = Snap::getSnapToken($params);

            // Update snap token
            $penjualan->update(['snap_token' => $snapToken]);

            DB::commit();

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $kodeTransaksi
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Create order error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle Midtrans callback/notification
     */
    public function callback(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderID = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            Log::info('Midtrans callback received', [
                'order_id' => $orderID,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus
            ]);

            // Find order in database
            $penjualan = Penjualan::where('kode_transaksi', $orderID)->first();

            if (!$penjualan) {
                Log::error('Order not found', ['order_id' => $orderID]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            DB::beginTransaction();

            // Handle payment status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $penjualan->update(['status' => 'menunggu_pembayaran']);
                } else if ($fraudStatus == 'accept') {
                    $penjualan->update(['status' => 'sedang_diproses']);
                    $this->processSuccessfulPayment($penjualan);
                }
            } else if ($transactionStatus == 'settlement') {
                $penjualan->update(['status' => 'sedang_diproses']);
                $this->processSuccessfulPayment($penjualan);
            } else if ($transactionStatus == 'pending') {
                $penjualan->update(['status' => 'menunggu_pembayaran']);
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $penjualan->update(['status' => 'dibatalkan']);
            }

            DB::commit();

            return response()->json(['message' => 'Callback handled successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Callback error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Process successful payment
     */
    private function processSuccessfulPayment($penjualan)
    {
        // Reload the penjualan to ensure we have fresh data
        $penjualan->load('detailPenjualans.barang');

        // Update stock
        foreach ($penjualan->detailPenjualans as $detail) {
            $detail->barang->decrement('stok', $detail->qty);

            Log::info('Stock decremented', [
                'product' => $detail->barang->nama_barang,
                'qty' => $detail->qty,
                'old_stock' => $detail->barang->stok + $detail->qty,
                'new_stock' => $detail->barang->stok
            ]);
        }

        // Always clear cart for this user
        $deletedCount = Keranjang::where('id_user', $penjualan->id_user)->delete();

        Log::info('Cart cleared for user', [
            'user_id' => $penjualan->id_user,
            'deleted_items' => $deletedCount,
            'order_id' => $penjualan->kode_transaksi
        ]);

        // Send email notification
        $this->sendPaymentConfirmationEmail($penjualan);
    }

    /**
     * Send payment confirmation email
     */
    private function sendPaymentConfirmationEmail($penjualan)
    {
        try {
            $user = $penjualan->user;

            Log::info('Sending payment confirmation email', [
                'order_id' => $penjualan->kode_transaksi,
                'to_email' => $user->email,
                'to_name' => $user->name
            ]);

            Mail::send('emails.payment_confirmation', ['penjualan' => $penjualan], function($message) use ($user, $penjualan) {
                $message->to($user->email, $user->name)
                        ->subject('Konfirmasi Pembayaran - ' . $penjualan->kode_transaksi);
            });

            Log::info('Email sent successfully', [
                'order_id' => $penjualan->kode_transaksi
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Payment success redirect page
     */
    public function success(Request $request)
    {
        return redirect()->route('beranda')->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
    }

    /**
     * Update payment status to processed manually
     */
    public function updatePaymentStatus(Request $request)
    {
        try {
            // Get the latest order by current user with status 'menunggu_pembayaran'
            $penjualan = Penjualan::where('id_user', Auth::id())
                                  ->where('status', 'menunggu_pembayaran')
                                  ->latest()
                                  ->first();

            if ($penjualan) {
                DB::beginTransaction();

                // Update status to 'sedang_diproses'
                $penjualan->update(['status' => 'sedang_diproses']);

                // Process successful payment (update stock, clear cart, send email)
                $this->processSuccessfulPayment($penjualan);

                DB::commit();

                Log::info('Payment status updated manually', [
                    'order_id' => $penjualan->kode_transaksi,
                    'user_id' => Auth::id()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Status pembayaran berhasil diupdate'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update payment status error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
