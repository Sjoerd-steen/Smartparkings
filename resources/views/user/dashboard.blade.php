@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Parkeer Dashboard')

@section('content')
    {{-- STATS CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="card border-l-4 border-green-400">
            <p class="text-sm text-gray-500 mb-1">Beschikbaar</p>
            <p class="text-3xl font-bold text-green-600">{{ $beschikbaar }}</p>
            <p class="text-xs text-gray-400 mt-1">parkeerplaatsen</p>
        </div>
        <div class="card border-l-4 border-red-400">
            <p class="text-sm text-gray-500 mb-1">Bezet</p>
            <p class="text-3xl font-bold text-red-500">{{ $bezet + $gereserveerd }}</p>
            <p class="text-xs text-gray-400 mt-1">parkeerplaatsen</p>
        </div>
        <div class="card border-l-4 border-blue-400">
            <p class="text-sm text-gray-500 mb-1">Totaal</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalSpots }}</p>
            <p class="text-xs text-gray-400 mt-1">parkeerplaatsen</p>
        </div>
        <div class="card border-l-4 border-purple-400">
            <p class="text-sm text-gray-500 mb-1">Bezettingsgraad</p>
            <p class="text-3xl font-bold text-purple-600">{{ $bezettingsgraad }}%</p>
            <p class="text-xs text-gray-400 mt-1">van alle plekken</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- PARKEERKAART --}}
        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-800">🗺️ Parkeer Map</h2>
                <a href="{{ route('user.reserve') }}" class="btn-primary text-sm">+ Reserveren</a>
            </div>

            {{-- Bezettingsbalk --}}
            <div class="mb-5">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Bezettingsgraad</span>
                    <span>{{ $bezettingsgraad }}%</span>
                </div>
                <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500
          {{ $bezettingsgraad > 80 ? 'bg-red-500' : ($bezettingsgraad > 50 ? 'bg-yellow-500' : 'bg-green-500') }}"
                         style="width: {{ $bezettingsgraad }}%"></div>
                </div>
            </div>

            {{-- Grid van parkeerplekken --}}
            <div class="grid grid-cols-5 sm:grid-cols-8 gap-2">
                @foreach($spots as $spot)
                    <div class="aspect-square rounded-lg flex flex-col items-center justify-center text-xs font-bold cursor-default
          {{ $spot->status === 'beschikbaar' ? 'bg-green-100 text-green-800 border-2 border-green-300' :
             ($spot->status === 'gereserveerd' ? 'bg-yellow-100 text-yellow-800 border-2 border-yellow-300' :
             'bg-red-100 text-red-800 border-2 border-red-300') }}"
                         title="{{ $spot->name }} – {{ ucfirst($spot->status) }}">
          <span class="text-base">
            {{ $spot->status === 'beschikbaar' ? '🟢' : ($spot->status === 'gereserveerd' ? '🟡' : '🔴') }}
          </span>
                        <span class="text-[10px] mt-0.5">{{ Str::limit($spot->name, 4) }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Legenda --}}
            <div class="flex gap-4 mt-4 text-xs text-gray-600">
                <span class="flex items-center gap-1"><span class="w-3 h-3 bg-green-400 rounded-full"></span> Beschikbaar</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 bg-yellow-400 rounded-full"></span> Gereserveerd</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 bg-red-400 rounded-full"></span> Bezet</span>
            </div>

            <p class="text-xs text-gray-400 mt-3">
                <span id="lastUpdate"></span>
                <button onclick="location.reload()" class="ml-2 text-blue-500 hover:underline">↻ Vernieuwen</button>
            </p>
        </div>

        {{-- MIJN RESERVERINGEN SIDEBAR --}}
        <div class="card">
            <h2 class="text-lg font-bold text-gray-800 mb-4">📋 Mijn Reserveringen</h2>
            @if($mijnReservaties->isEmpty())
                <div class="text-center py-8">
                    <p class="text-4xl mb-3">🅿️</p>
                    <p class="text-gray-500 text-sm">Geen actieve reserveringen</p>
                    <a href="{{ route('user.reserve') }}" class="btn-primary text-sm inline-block mt-3">Reserveer nu</a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($mijnReservaties as $res)
                        <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="font-semibold text-blue-900 text-sm">{{ $res->parkingSpot->name }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $res->datum->format('d-m-Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $res->start_tijd }} – {{ $res->eind_tijd }}</p>
                            <p class="text-sm font-bold text-blue-700 mt-1">€{{ number_format($res->totaal_prijs, 2) }}</p>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('user.reservations') }}" class="block text-center text-blue-600 text-sm mt-4 hover:underline">
                    Alle reserveringen bekijken →
                </a>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('lastUpdate').textContent = 'Bijgewerkt: ' + new Date().toLocaleTimeString('nl-NL');
        // Auto-refresh elke 30 seconden
        setTimeout(() => location.reload(), 30000);
    </script>
@endsection
