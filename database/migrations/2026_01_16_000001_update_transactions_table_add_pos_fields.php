<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Kolom POS baru
            $table->string('uuid')->after('id')->nullable()->unique(); // UUID dari Flutter
            $table->string('customer_name')->after('uuid')->nullable();
            
            // Rename kolom lama agar lebih jelas (optional, tapi saya biarkan yang lama sebagai legacy/midtrans specific)
            // Kita pakai kolom baru untuk standarisasi POS
            
            $table->decimal('total_price', 15, 2)->after('amount')->default(0);
            $table->decimal('bayar_amount', 15, 2)->nullable()->after('total_price');
            $table->decimal('kembalian', 15, 2)->nullable()->after('bayar_amount');
            
            $table->string('payment_method')->default('TUNAI')->after('kembalian'); // TUNAI, QRIS, etc
            
            $table->timestamp('redeemed_at')->nullable()->after('updated_at');
            
            // Mapping kolom lama (Midtrans) ke konsep baru
            // 'order_id' lama -> 'midtrans_order_id'
            // 'snap_token' -> 'midtrans_snap_token'
            // Kita rename saja agar rapi
            $table->renameColumn('order_id', 'midtrans_order_id');
            $table->renameColumn('snap_token', 'midtrans_snap_token');
            $table->renameColumn('payment_type', 'midtrans_payment_type');
        });
        
        // Make existing amount column nullable as we use total_price now
        Schema::table('transactions', function (Blueprint $table) {
             $table->decimal('amount', 12, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('midtrans_order_id', 'order_id');
            $table->renameColumn('midtrans_snap_token', 'snap_token');
            $table->renameColumn('midtrans_payment_type', 'payment_type');
            
            $table->dropColumn([
                'uuid',
                'customer_name', 
                'total_price', 
                'bayar_amount', 
                'kembalian', 
                'payment_method', 
                'redeemed_at'
            ]);
        });
    }
};
