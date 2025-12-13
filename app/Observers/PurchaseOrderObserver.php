<?php

namespace App\Observers;

use App\Models\Stock;
use App\Models\PurchaseOrder;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderObserver
{
    /**
     * Handle the PurchaseOrder "created" event.
     */
    public function created(PurchaseOrder $purchaseOrder): void
    {
        //
    }

    /**
     * Handle the PurchaseOrder "updated" event.
     */
    public function updated(PurchaseOrder $purchaseOrder): void
    {
        // 1. Cek: Apakah status PO berubah menjadi 'Received' (Diterima)
        if ($purchaseOrder->isDirty('status') && $purchaseOrder->status === 'Received') {
            
            // 2. Gunakan Database Transaction untuk memastikan semua atau tidak sama sekali berhasil.
            DB::transaction(function () use ($purchaseOrder) {
                
                // 3. Iterasi setiap item dalam Purchase Order
                foreach ($purchaseOrder->items as $item) {
                    
                    // a. Cari atau Buat entry di tabel Stock
                    $stock = Stock::firstOrNew([
                        'product_id' => $item->product_id,
                        'warehouse_id' => $purchaseOrder->warehouse_id, // Gunakan Gudang Tujuan PO
                    ]);

                    // Jika Stock sudah ada, tambahkan kuantitas lama dengan kuantitas item PO.
                    $stock->quantity += $item->quantity;
                    $stock->save();

                    // b. Catat Transaksi Stok (Log)
                    StockTransaction::create([
                        'user_id' => Auth::id(),
                        'product_id' => $item->product_id,
                        'warehouse_id' => $purchaseOrder->warehouse_id,
                        'reference_type' => PurchaseOrder::class, // Model referensi
                        'reference_id' => $purchaseOrder->id,      // ID referensi PO
                        'quantity' => $item->quantity,             // Kuantitas yang masuk (positif)
                        'type' => 'Stock In (Purchase)',           // Jenis transaksi
                        'current_stock' => $stock->quantity,       // Stok akhir setelah transaksi
                    ]);

                    // PENTING: Tambahkan logika untuk memperbarui 'price_in' (harga beli rata-rata) 
                    // di Product Model jika Anda menggunakan metode Average Cost. 
                    // Untuk saat ini kita abaikan agar tetap sederhana, 
                    // tetapi dalam sistem nyata ini harus dilakukan.
                }

            }); // DB::transaction selesai
        }
    }

    /**
     * Handle the PurchaseOrder "deleted" event.
     */
    public function deleted(PurchaseOrder $purchaseOrder): void
    {
        //
    }

    /**
     * Handle the PurchaseOrder "restored" event.
     */
    public function restored(PurchaseOrder $purchaseOrder): void
    {
        //
    }

    /**
     * Handle the PurchaseOrder "force deleted" event.
     */
    public function forceDeleted(PurchaseOrder $purchaseOrder): void
    {
        //
    }
}
