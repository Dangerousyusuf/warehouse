<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'model',
        'model_id',
        'user_id', // 'user_id' alanını ekledik
        'description', // Açıklama alanını ekledik
        // ... diğer alanlar ...
    ];

    // Kullanıcı ile ilişki
    public function user()
    {
        return $this->belongsTo(User::class); // ActivityLog'un bir User'a ait olduğunu belirtiyoruz
    }

    public function createLog($action, $model, $modelId, $userId) {
        if (is_null($modelId)) {
            throw new \InvalidArgumentException('model_id cannot be null'); // Hata kontrolü
        }
        
        if (is_null($model)) {
            throw new \InvalidArgumentException('model cannot be null'); // Hata kontrolü
        }
        
        ActivityLog::create([
            'action' => $action,
            'model' => $model, // 'model' değerini burada sağlıyoruz
            'model_id' => $modelId, // 'model_id' değerini burada sağlıyoruz
            'user_id' => $userId, // 'user_id' değerini burada sağlıyoruz
        ]);
    }
    
}
