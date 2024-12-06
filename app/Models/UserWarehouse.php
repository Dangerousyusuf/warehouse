<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWarehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'warehouse_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class);
    }
}

