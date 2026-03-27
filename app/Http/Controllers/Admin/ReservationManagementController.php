<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationManagementController extends Controller {

    public function index(Request $request) {
        $query = Reservation::with(['user', 'parkingSpot']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        $reservations = $query->latest()->paginate(15);
        return view('admin.reservations.index', compact('reservations'));
    }

    public function update(Request $request, Reservation $reservation) {
        $request->validate([
            'status' => 'required|in:actief,geannuleerd,voltooid',
        ]);
        $reservation->update(['status' => $request->status]);

        if ($request->status === 'geannuleerd') {
            $reservation->parkingSpot->update(['status' => 'beschikbaar']);
        }

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservering bijgewerkt.');
    }

    public function destroy(Reservation $reservation) {
        $reservation->parkingSpot->update(['status' => 'beschikbaar']);
        $reservation->delete();
        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservering verwijderd.');
    }
}
