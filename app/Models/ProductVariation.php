<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'variation_option_id', 
        'sku',  
        'name', 
        'variation_images',
        'parent_variation_id',
    ];


    public function product() {
        return $this->belongsTo(Product::class);
    }

    /*public function variationOption() {
        return $this->belongsTo(VariationOption::class);
    }*/
    public function variationOption() {
        return $this->belongsTo(VariationOption::class, 'variation_option_id');
    }
     // Ana varyant ile ilişki (parent_id varsa bu bir alt varyanttır)
     public function parent()
     {
         return $this->belongsTo(ProductVariation::class, 'parent_variation_id');
     }
 
     // Alt varyantlarla ilişki (bu bir ana varyant ise alt varyantlarını gösterir)
     public function children()
     {
         return $this->hasMany(ProductVariation::class, 'parent_variation_id');
     }

    public function stocks() {
        return $this->hasMany(Stocks::class);
    }
}
