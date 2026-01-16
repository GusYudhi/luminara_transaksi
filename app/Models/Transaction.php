<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'uuid',
        'customer_name',
        'total_price',
        'bayar_amount',
        'kembalian',
        'payment_method',
        'status',
        'redeemed_at',
        
        // Midtrans Fields
        'midtrans_order_id',
        'midtrans_snap_token',
        'midtrans_payment_type',
        
        // Legacy (untuk sementara jika masih dipakai controller lama)
        'amount',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}
