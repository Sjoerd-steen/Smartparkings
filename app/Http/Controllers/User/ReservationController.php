<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ParkingSpot;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller {

    // Overzicht van beschikbare parkeerplaatsen om te reserveren
    public function create() {
        $spots = ParkingSpot::available()->get();
        return view('user.reserve', compact('spots'));
    }

    // Betaalpagina tonen
    public function betaalForm(Request $request) {
        $request->validate([
            'parking_spot_id' => 'required|exists:parking_spots,id',
            'datum'           => 'required|date|after_or_equal:today',
            'start_tijd'      => 'required',
            'eind_tijd'       => 'required|after:start_tijd',
            'voertuig'        => 'required|in:Auto,Motor,Fiets,Elektrisch',
            'kenteken'        => 'nullable|string|max:10',
        ]);

        $spot = ParkingSpot::findOrFail($request->parking_spot_id);

        // Bereken prijs
        $start = \Carbon\Carbon::parse($request->start_tijd);
        $eind  = \Carbon\Carbon::parse($request->eind_tijd);
        $uren  = max(1, $start->diffInHours($eind));
        $prijs = $uren * $spot->price_per_hour;

        return view('user.betaal', compact('spot', 'prijs', 'uren'))->with([
            'formData' => $request->all()
        ]);
    }

    // Reservering opslaan na betaling
    public function store(Request $request) {
        $request->validate([
            'parking_spot_id' => 'required|exists:parking_spots,id',
            'datum'           => 'required|date',
            'start_tijd'      => 'required',
            'eind_tijd'       => 'required',
            'voertuig'        => 'required',
            'betaal_methode'  => 'required|in:ideal,paypal,tikkie,maestro',
            'agree'           => 'required|accepted',
        ]);

        $spot = ParkingSpot::findOrFail($request->parking_spot_id);
        $start = \Carbon\Carbon::parse($request->start_tijd);
        $eind  = \Carbon\Carbon::parse($request->eind_tijd);
        $uren  = max(1, $start->diffInHours($eind));
        $prijs = $uren * $spot->price_per_hour;

        $reservation = Reservation::create([
            'user_id'         => Auth::id(),
            'parking_spot_id' => $request->parking_spot_id,
            'datum'           => $request->datum,
            'start_tijd'      => $request->start_tijd,
            'eind_tijd'       => $request->eind_tijd,
            'voertuig'        => $request->voertuig,
            'kenteken'        => $request->kenteken,
            'totaal_prijs'    => $prijs,
            'betaald'         => true,
            'betaal_methode'  => $request->betaal_methode,
            'status'          => 'actief',
        ]);

        // Update spot status
        $spot->update(['status' => 'gereserveerd']);

        return redirect()->route('user.reservations')
            ->with('success', "Reservering bevestigd! Parkeerplaats {$spot->name} gereserveerd voor €{$prijs}.");
    }

    // Overzicht eigen reserveringen
    public function index() {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('parkingSpot')
            ->latest()
            ->paginate(10);

        return view('user.reservations', compact('reservations'));
    }

    // Reservering annuleren
    public function destroy(Reservation $reservation) {
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Geen toegang.');
        }

        // Zet spot terug op beschikbaar
        $reservation->parkingSpot->update(['status' => 'beschikbaar']);
        $reservation->update(['status' => 'geannuleerd']);

        return redirect()->route('user.reservations')
            ->with('success', 'Reservering geannuleerd.');
    }
}
