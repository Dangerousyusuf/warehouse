<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferItem extends Model
{
    use HasFactory;

    // Tablo adını belirtin
    protected $table = 'transfer_items';

    // Doldurulabilir alanları tanımlayın
    protected $fillable = [
        'transfer_id',
        'product_id',
        'product_variation_id',
        'quantity'
    ];

    // İlişkiler
    public function transfer()
    {
        return $this->belongsTo(Transfer::class, 'transfer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }
}
