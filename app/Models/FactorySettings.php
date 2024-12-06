<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactorySettings extends Model
{
    use HasFactory;
    protected $table = 'factory_settings'; 
    protected $fillable = ['variable', 'value']; // Kütüphane ile doldurulabilir alanlar
}
