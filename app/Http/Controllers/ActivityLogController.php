<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
  public function index() {
        // activityLogs değişkenini tanımla
        $activityLogs = ActivityLog::orderBy('created_at', 'desc')->get(); // En yakın tarihe göre sıralama
        // View'a activityLogs değişkenini gönder
        return view('activity.activity_list', compact('activityLogs'));
    }

    public function logActivity($action, $userId, $productId, $description = null) {
        ActivityLog::create([
            'action' => $action,
            'user_id' => $userId,
            'product_id' => $productId,
            'description' => $description, // Açıklama alanını ekledik
        ]);
    }
}
