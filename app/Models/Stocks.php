<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_variation_id',
        'warehouse_id',
        'shelf_id',
        'stock_quantity',
        'created_user',
        'update_user'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

    // Varyant ilişkisi
    public function variant()
    {
        return $this->belongsTo(Product::class, 'product_variation_id');
    }

}
