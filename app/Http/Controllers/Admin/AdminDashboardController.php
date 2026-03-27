<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, ParkingSpot, Reservation};

class AdminDashboardController extends Controller {

    public function index() {
        $totalUsers        = User::where('role', 'user')->count();
        $totalSpots        = ParkingSpot::count();
        $beschikbaar       = ParkingSpot::where('status', 'beschikbaar')->count();
        $totalReservations = Reservation::count();
        $actief            = Reservation::where('status', 'actief')->count();
        $omzet             = Reservation::where('betaald', true)->sum('totaal_prijs');
        $bezettingsgraad   = $totalSpots > 0
            ? round((ParkingSpot::whereIn('status', ['bezet','gereserveerd'])->count() / $totalSpots) * 100)
            : 0;

        $recentReservations = Reservation::with(['user','parkingSpot'])
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers','totalSpots','beschikbaar','totalReservations',
            'actief','omzet','bezettingsgraad','recentReservations'
        ));
    }
}
