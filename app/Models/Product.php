<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_code',
        'barcode',
        'description',
        'image',
        'standard_price',
        'sale_price',
        'stock_quantity',
        'last_restock_date',
        'total_stock_limit',
        'unit',
        'product_type',
        'estimated_daily_usage',
        'estimated_delivery_time',
        'auto_order_quantity',
        'category_id',
        'warehouse_id',
        'shelf_id',
        'critical_stock_level',
        'weight',
        'weight_unit',
        'size_x',
        'size_y',
        'size_z',
        'status',
        'slug',
        'parent_id', 
        'variation_option_id', 
        'parent_variation_id',
    ];

    protected $casts = [
        'last_restock_date' => 'datetime',
        'standard_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'total_stock_limit' => 'integer',
        'estimated_daily_usage' => 'integer',
        'estimated_delivery_time' => 'integer',
        'auto_order_quantity' => 'integer',
        'critical_stock_level' => 'integer',
        'weight' => 'decimal:2',
        'size_x' => 'decimal:2',
        'size_y' => 'decimal:2',
        'size_z' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function variationOption() {
        return $this->belongsTo(VariationOption::class, 'variation_option_id');
    }

    public function stocks()
    {
        return $this->hasMany(Stocks::class);
    }

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }

  

}