<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

     // Tablo adını belirtin
     protected $table = 'transfers';

     // Doldurulabilir alanları tanımlayın
     protected $fillable = [
         'from_warehouse',
         'to_warehouse',
         'from_shelves',
         'to_shelves',
         'transfer_date',
         'status',
         'created_by'
     ];
 
     // İlişkiler
     public function items()
     {
         return $this->hasMany(TransferItem::class, 'transfer_id');
     }

     public function fromWarehouse()
     {
         return $this->belongsTo(Warehouse::class, 'from_warehouse');
     }
 
     public function createdBy()
     {
         return $this->belongsTo(User::class, 'created_by');
     }
}
