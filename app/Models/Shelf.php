<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'stock_limit',
        'warehouse_id',
    ];
  
    public function warhouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function products()
    {
        return $this->hasMany(Stocks::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stocks::class)->whereNull('deleted_at');
    }
    
}
