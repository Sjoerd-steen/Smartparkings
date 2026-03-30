@extends('layouts.app')
@section('title', 'Mijn Reserveringen')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6 border-b border-gray-600 pb-4">
        <div>
            <h2 class="text-2xl font-bold text-white uppercase tracking-wide">📋 Mijn Reserveringen</h2>
            <p class="text-gray-400 text-sm mt-1">Overzicht van al uw parkeerreserveringen</p>
        </div>
        <a href="{{ route('user.reserve') }}" class="btn-primary flex items-center gap-2">
            <span class="text-lg">+</span> Nieuwe Reservering
        </a>
    </div>

    @if($reservations->isEmpty())
        <div class="card text-center py-16 flex flex-col items-center justify-center border border-gray-700">
            <p class="text-6xl mb-4 opacity-50">🅿️</p>
            <p class="text-xl font-bold text-gray-300">Nog geen reserveringen</p>
            <p class="text-gray-500 mt-2 text-sm uppercase tracking-widest">Reserveer uw eerste parkeerplaats!</p>
            <a href="{{ route('user.reserve') }}" class="btn-primary mt-6 tracking-wide">Reserveer nu</a>
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($reservations as $res)
                <div class="card border border-gray-700 hover:border-blue-500 transition-colors relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full 
                        @if($res->status === 'actief') bg-green-500 
                        @elseif($res->status === 'geannuleerd') bg-red-500 
                        @else bg-blue-500 @endif
                    "></div>
                    
                    <div class="flex items-start justify-between mb-4 pb-4 border-b border-gray-700">
                        <div>
                            <h3 class="font-bold text-white text-lg">{{ $res->parkingSpot->name }}</h3>
                            <p class="text-xs text-gray-400 uppercase tracking-widest mt-1">{{ $res->parkingSpot->location }}</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wider
                            @if($res->status === 'actief') bg-green-900/50 text-green-400 border border-green-700
                            @elseif($res->status === 'geannuleerd') bg-red-900/50 text-red-400 border border-red-700
                            @else bg-blue-900/50 text-blue-400 border border-blue-700 @endif">
                            {{ ucfirst($res->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm mb-6">
                        <div class="bg-gray-800 p-2 rounded border border-gray-700">
                            <span class="text-xs text-gray-500 uppercase tracking-widest block mb-1">Datum</span>
                            <p class="font-bold text-gray-300">{{ $res->datum->format('d-m-Y') }}</p>
                        </div>
                        <div class="bg-gray-800 p-2 rounded border border-gray-700">
                            <span class="text-xs text-gray-500 uppercase tracking-widest block mb-1">Tijdslot</span>
                            <p class="font-bold text-gray-300">{{ \Carbon\Carbon::parse($res->start_tijd)->format('H:i') }} – {{ \Carbon\Carbon::parse($res->eind_tijd)->format('H:i') }}</p>
                        </div>
                        <div class="bg-gray-800 p-2 rounded border border-gray-700">
                            <span class="text-xs text-gray-500 uppercase tracking-widest block mb-1">Voertuig</span>
                            <p class="font-bold text-gray-300">{{ $res->voertuig }}</p>
                            @if($res->kenteken)
                                <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $res->kenteken }}</p>
                            @endif
                        </div>
                        <div class="bg-gray-800 p-2 rounded border border-gray-700">
                            <span class="text-xs text-gray-500 uppercase tracking-widest block mb-1">Betaalstatus</span>
                            <p>@if($res->betaald) <span class="text-green-400 font-bold">✓ Betaald</span> @else <span class="text-red-400 font-bold">Niet betaald</span> @endif</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-700">
                        <div>
                            <span class="text-xs text-gray-500 uppercase tracking-widest block">Totaal Prijs</span>
                            <p class="font-extrabold text-blue-400 text-lg">€{{ number_format($res->totaal_prijs, 2) }}</p>
                        </div>
                        
                        @if($res->status === 'actief')
                            <form method="POST" action="{{ route('user.reservations.destroy', $res) }}"
                                  onsubmit="return confirm('Weet u zeker dat u deze reservering wilt annuleren?')">
                                @csrf @method('DELETE')
                                <button class="btn-danger !py-2 text-sm uppercase tracking-wider flex items-center gap-2 hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Annuleren
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $reservations->links('pagination::tailwind') }}
        </div>
    @endif
</div>
@endsection
