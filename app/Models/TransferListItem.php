<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferListItem extends Model
{
    use HasFactory;

    protected $fillable = ['transfer_list_id', 'product_id', 'stock_id', 'quantity', 'from_warehouse_id','to_warehouse_id','from_shelves_id','to_shelves_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stocks::class);
    }
}
