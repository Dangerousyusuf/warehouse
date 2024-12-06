<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        $totalWarehouses = Warehouse::count();
        $totalUsers = User::count();
        $occupancyRate = $this->calculateOccupancyRate();

        return view('index', compact('totalWarehouses', 'totalUsers', 'occupancyRate'));
    }

    private function calculateOccupancyRate()
    {
        $warehouses = Warehouse::all();
        $totalOccupancy = 0;
        $totalCount = $warehouses->count();

        foreach ($warehouses as $warehouse) {
            $totalOccupancy += $warehouse->occupancy_rate;
        }

        return $totalCount > 0 ? ($totalOccupancy / $totalCount) : 0;
    }
}
