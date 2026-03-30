<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:50',
            'license_plate' => 'required|string|max:20',
        ]);

        Vehicle::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'license_plate' => strtoupper($request->license_plate),
        ]);

        return redirect()->back()->with('success', 'Voertuig succesvol toegevoegd!');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== Auth::id()) {
            abort(403);
        }

        $vehicle->delete();

        return redirect()->back()->with('success', 'Voertuig verwijderd!');
    }
}
