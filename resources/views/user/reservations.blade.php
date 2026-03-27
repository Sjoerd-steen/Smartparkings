@extends('layouts.app')
@section('title', 'Mijn Reserveringen')
@section('page-title', 'Bekijk uw Reserveringen')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-500">Overzicht van al uw parkeerreserveringen</p>
        <a href="{{ route('user.reserve') }}" class="btn-primary">+ Nieuwe Reservering</a>
    </div>

    @if($reservations->isEmpty())
        <div class="card text-center py-16">
            <p class="text-6xl mb-4">🅿️</p>
            <p class="text-xl font-semibold text-gray-700">Nog geen reserveringen</p>
            <p class="text-gray-400 mt-2">Reserveer uw eerste parkeerplaats!</p>
            <a href="{{ route('user.reserve') }}" class="btn-primary inline-block mt-5">Reserveer nu</a>
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-5">
            @foreach($reservations as $res)
                <div class="card">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-bold text-gray-800">{{ $res->parkingSpot->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $res->parkingSpot->location }}</p>
                        </div>
                        <span class="
            @if($res->status === 'actief') badge-green
            @elseif($res->status === 'geannuleerd') badge-red
            @else badge-blue @endif">
            {{ ucfirst($res->status) }}
          </span>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                        <div><span class="text-gray-400">Datum</span><p class="font-medium">{{ $res->datum->format('d-m-Y') }}</p></div>
                        <div><span class="text-gray-400">Tijdslot</span><p class="font-medium">{{ $res->start_tijd }} – {{ $res->eind_tijd }}</p></div>
                        <div><span class="text-gray-400">Prijs</span><p class="font-bold text-blue-600">€{{ number_format($res->totaal_prijs, 2) }}</p></div>
                        <div><span class="text-gray-400">Betaalstatus</span>
                            <p>@if($res->betaald) <span class="badge-green">✓ Betaald</span> @else <span class="badge-red">Niet betaald</span> @endif</p>
                        </div>
                        <div><span class="text-gray-400">Voertuig</span><p class="font-medium">{{ $res->voertuig }}</p></div>
                        @if($res->kenteken)
                            <div><span class="text-gray-400">Kenteken</span><p class="font-medium font-mono">{{ $res->kenteken }}</p></div>
                        @endif
                    </div>

                    @if($res->status === 'actief')
                        <form method="POST" action="{{ route('user.reservations.destroy', $res) }}"
                              onsubmit="return confirm('Weet u zeker dat u deze reservering wilt annuleren?')">
                            @csrf @method('DELETE')
                            <button class="btn-danger w-full text-sm">🗑️ Reservering annuleren</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $reservations->links() }}</div>
    @endif
@endsection
