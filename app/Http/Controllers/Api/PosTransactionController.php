<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PosTransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::with('items')
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'uuid' => 'required|unique:transactions,uuid',
            'customer_name' => 'nullable|string',
            'total_price' => 'required|numeric',
            'bayar_amount' => 'required|numeric',
            'kembalian' => 'required|numeric',
            'payment_method' => 'required|string',
            'items' => 'required|array',
            'items.*.product_name' => 'required|string',
            'items.*.product_price' => 'required|numeric',
            'items.*.quantity' => 'required|integer',
            'midtrans_order_id' => 'nullable|string',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // 1. Simpan Header Transaksi
                $transaction = Transaction::create([
                    'uuid' => $request->uuid,
                    'customer_name' => $request->customer_name,
                    'total_price' => $request->total_price,
                    'bayar_amount' => $request->bayar_amount,
                    'kembalian' => $request->kembalian,
                    'payment_method' => $request->payment_method,
                    'status' => 'PAID', // Dari POS lokal biasanya sudah paid
                    'midtrans_order_id' => $request->midtrans_order_id,
                    'created_at' => now(), // Atau ambil dari request jika ingin sinkron waktu
                ]);

                // 2. Simpan Items
                foreach ($request->items as $item) {
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_name' => $item['product_name'],
                        'product_price' => $item['product_price'],
                        'quantity' => $item['quantity'],
                        'total_line_price' => $item['product_price'] * $item['quantity'],
                    ]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil disimpan ke cloud',
                    'data' => $transaction->load('items')
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error("Gagal simpan transaksi: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($uuid)
    {
        $transaction = Transaction::with('items')->where('uuid', $uuid)->firstOrFail();
        return response()->json(['data' => $transaction]);
    }
}
