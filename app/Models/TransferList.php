<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferList extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','status'];

    public function items()
    {
        return $this->hasMany(TransferListItem::class);
    }
}
