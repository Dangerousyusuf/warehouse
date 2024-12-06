<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'product_variation_id',
        'quantity',
        'type',
        'stock_out',
        'from_warehouse_id',
        'to_warehouse_id',
        'from_shelves_id',
        'to_shelves_id',
        'note',
        'transfer_status',
        'created_user',
        'updated_user',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_user');
    }
    public function fromShelves()
    {
        return $this->belongsTo(Shelf::class, 'from_shelves_id');
    }
    
    public function toShelves()
    {
        return $this->belongsTo(Shelf::class, 'to_shelves_id');
    }
}
