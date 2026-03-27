<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingSpot;
use Illuminate\Http\Request;

class ParkingSpotController extends Controller {

    public function index() {
        $spots = ParkingSpot::withCount('reservations')->paginate(20);
        return view('admin.spots.index', compact('spots'));
    }

    public function create() {
        return view('admin.spots.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'           => 'required|string|max:50|unique:parking_spots',
            'location'       => 'nullable|string|max:100',
            'type'           => 'required|in:Standaard,Gehandicapt,Motor,Elektrisch',
            'price_per_hour' => 'required|numeric|min:0',
            'status'         => 'required|in:beschikbaar,bezet,gereserveerd',
        ]);

        ParkingSpot::create($request->all());
        return redirect()->route('admin.spots.index')
            ->with('success', "Parkeerplaats {$request->name} aangemaakt.");
    }

    public function edit(ParkingSpot $spot) {
        return view('admin.spots.edit', compact('spot'));
    }

    public function update(Request $request, ParkingSpot $spot) {
        $request->validate([
            'name'           => 'required|string|max:50|unique:parking_spots,name,' . $spot->id,
            'location'       => 'nullable|string|max:100',
            'type'           => 'required|in:Standaard,Gehandicapt,Motor,Elektrisch',
            'price_per_hour' => 'required|numeric|min:0',
            'status'         => 'required|in:beschikbaar,bezet,gereserveerd',
        ]);

        $spot->update($request->all());
        return redirect()->route('admin.spots.index')
            ->with('success', "Parkeerplaats {$spot->name} bijgewerkt.");
    }

    public function destroy(ParkingSpot $spot) {
        $spot->delete();
        return redirect()->route('admin.spots.index')
            ->with('success', 'Parkeerplaats verwijderd.');
    }
}
