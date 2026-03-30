<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {

    public function index() {
        $spots = ParkingSpot::all();
        $beschikbaar = $spots->where('status', 'beschikbaar')->count();
        $bezet       = $spots->where('status', 'bezet')->count();
        $gereserveerd = $spots->where('status', 'gereserveerd')->count();
        $totalSpots  = $spots->count();
        $bezettingsgraad = $totalSpots > 0 ? round((($bezet + $gereserveerd) / $totalSpots) * 100) : 0;

        $user = Auth::user();

        $mijnReservaties = Reservation::where('user_id', $user->id)
            ->where('status', 'actief')
            ->with('parkingSpot')
            ->latest()
            ->take(3)
            ->get();

        $vehicles = $user->vehicles;

        return view('user.dashboard', compact(
            'spots', 'beschikbaar', 'bezet', 'gereserveerd',
            'bezettingsgraad', 'mijnReservaties', 'totalSpots', 'vehicles'
        ));
    }
}
