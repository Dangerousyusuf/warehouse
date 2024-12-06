<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number', // Telefon numarası eklendi
        'role', // Görev alanı eklendi
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'phone_number' => 'string', // Telefon numarası için string dönüşümü
            'role' => 'string', // Görev alanı için string dönüşümü
        ];
    }
    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class, 'user_warehouse');
    }

// app/Models/Warehouse.php
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class); // Kullanıcının birden fazla ActivityLog kaydı olabileceğini belirtiyoruz
    }

    public function getSelectedWarehouseIdsAttribute()
    {
        return json_decode($this->attributes['selected_warehouse_ids'], true) ?? []; // JSON formatında dizi döndür
    }
}
