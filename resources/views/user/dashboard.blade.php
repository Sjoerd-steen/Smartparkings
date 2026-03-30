@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    {{-- STATS CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card !p-6 flex flex-col items-center justify-center text-center">
            <p class="text-sm text-gray-300 font-semibold mb-2 uppercase tracking-wide">Beschikbaar</p>
            <p class="text-4xl font-extrabold text-green-400">{{ $beschikbaar }}</p>
            <p class="text-xs text-gray-400 mt-2">parkeerplaatsen</p>
        </div>
        <div class="card !p-6 flex flex-col items-center justify-center text-center">
            <p class="text-sm text-gray-300 font-semibold mb-2 uppercase tracking-wide">Bezet</p>
            <p class="text-4xl font-extrabold text-red-500">{{ $bezet + $gereserveerd }}</p>
            <p class="text-xs text-gray-400 mt-2">parkeerplaatsen</p>
        </div>
        <div class="card !p-6 flex flex-col items-center justify-center text-center">
            <p class="text-sm text-gray-300 font-semibold mb-2 uppercase tracking-wide">Totaal</p>
            <p class="text-4xl font-extrabold text-blue-400">{{ $totalSpots }}</p>
            <p class="text-xs text-gray-400 mt-2">parkeerplaatsen</p>
        </div>
        <div class="card !p-6 flex flex-col items-center justify-center text-center">
            <p class="text-sm text-gray-300 font-semibold mb-2 uppercase tracking-wide">Bezettingsgraad</p>
            <p class="text-4xl font-extrabold text-purple-400">{{ $bezettingsgraad }}%</p>
            <p class="text-xs text-gray-400 mt-2">van alle plekken</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        {{-- PARKEERKAART (Left, takes 2 cols) --}}
        <div class="lg:col-span-2 card">
            <div class="flex items-center justify-between border-b border-gray-600 pb-4 mb-6">
                <h2 class="text-2xl font-bold text-white tracking-wide">🗺️ Parkeer Map</h2>
                <a href="{{ route('user.reserve') }}" class="btn-primary !w-auto !mt-0 !py-2 text-sm uppercase">Nieuwe Reservering</a>
            </div>

            {{-- Bezettingsbalk --}}
            <div class="mb-8">
                <div class="flex justify-between text-sm font-bold text-gray-300 mb-2">
                    <span class="uppercase tracking-wider">Huidige Bezettingsgraad</span>
                    <span>{{ $bezettingsgraad }}%</span>
                </div>
                <div class="h-4 bg-gray-700 rounded-full overflow-hidden shadow-inner">
                    <div class="h-full rounded-full transition-all duration-500
          {{ $bezettingsgraad > 80 ? 'bg-red-500' : ($bezettingsgraad > 50 ? 'bg-yellow-500' : 'bg-green-500') }}"
                         style="width: {{ $bezettingsgraad }}%"></div>
                </div>
            </div>

            {{-- Grid van parkeerplekken --}}
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                @foreach($spots as $spot)
                    <div class="aspect-square rounded-xl flex flex-col items-center justify-center text-sm font-bold shadow-md transform hover:scale-105 transition-transform duration-200 cursor-default
          {{ $spot->status === 'beschikbaar' ? 'bg-green-900 border border-green-500 text-green-300' :
             ($spot->status === 'gereserveerd' ? 'bg-yellow-900 border border-yellow-500 text-yellow-300' :
             'bg-red-900 border border-red-500 text-red-300') }}"
                         title="{{ $spot->name }} – {{ ucfirst($spot->status) }}">
          <span class="text-xl mb-1 drop-shadow-md">
            {{ $spot->status === 'beschikbaar' ? '🟢' : ($spot->status === 'gereserveerd' ? '🟡' : '🔴') }}
          </span>
                        <span>{{ Str::limit($spot->name, 4) }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Legenda --}}
            <div class="flex gap-6 mt-8 text-sm font-semibold text-gray-300 justify-center bg-gray-800 py-3 rounded-lg">
                <span class="flex items-center gap-2"><span class="w-4 h-4 bg-green-500 rounded-full shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span> Beschikbaar</span>
                <span class="flex items-center gap-2"><span class="w-4 h-4 bg-yellow-500 rounded-full shadow-[0_0_8px_rgba(234,179,8,0.6)]"></span> Gereserveerd</span>
                <span class="flex items-center gap-2"><span class="w-4 h-4 bg-red-500 rounded-full shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span> Bezet</span>
            </div>

            <p class="text-xs text-gray-400 mt-6 text-center">
                <span id="lastUpdate"></span>
                <button onclick="location.reload()" class="ml-3 text-blue-400 font-semibold hover:text-blue-300 hover:underline transition-colors uppercase tracking-wider">↻ Vernieuwen</button>
            </p>
        </div>

        {{-- SIDEBAR: Voertuigen & Reserveringen --}}
        <div class="space-y-8">
            {{-- MIJN VOERTUIGEN --}}
            <div class="card">
                <h2 class="text-xl font-bold text-white border-b border-gray-600 pb-3 mb-5 uppercase tracking-wide">🚗 Mijn Voertuigen</h2>
                
                @if($vehicles->isEmpty())
                    <p class="text-sm text-gray-400 mb-4 italic text-center">U heeft nog geen voertuigen toegevoegd.</p>
                @else
                    <ul class="space-y-3 mb-6">
                        @foreach($vehicles as $vehicle)
                            <li class="bg-gray-800 rounded-lg p-3 flex justify-between items-center shadow-inner">
                                <div>
                                    <span class="text-white font-bold block">{{ $vehicle->license_plate }}</span>
                                    <span class="text-xs text-gray-400 uppercase tracking-widest">{{ $vehicle->type }}</span>
                                </div>
                                <form method="POST" action="{{ route('user.vehicles.destroy', $vehicle->id) }}" onsubmit="return confirm('Weet u zeker dat u dit voertuig wilt verwijderen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors p-2 bg-red-900/30 rounded-md">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('user.vehicles.store') }}" class="bg-gray-700/50 p-4 rounded-xl border border-gray-600 shadow-inner">
                    @csrf
                    <p class="text-sm font-bold text-white mb-3 tracking-wide uppercase">Nieuw Voertuig Toevoegen</p>
                    <div class="mb-3">
                        <select name="type" required class="form-input text-sm font-medium h-10 py-1">
                            <option value="">Kies Vervoersmiddel</option>
                            <option value="Auto">Auto</option>
                            <option value="Motor">Motor</option>
                            <option value="Elektrisch">Elektrisch</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="license_plate" required class="form-input text-sm uppercase placeholder-gray-500 h-10 py-1" placeholder="KENTEKEN (bijv. AB-123-C)" maxlength="15">
                    </div>
                    <button type="submit" class="btn-primary !w-full !mt-2 !py-2 text-sm uppercase tracking-wider">Toevoegen</button>
                </form>
            </div>

            {{-- MIJN RESERVERINGEN --}}
            <div class="card">
                <h2 class="text-xl font-bold text-white border-b border-gray-600 pb-3 mb-5 uppercase tracking-wide">📋 Recente Reserveringen</h2>
                @if($mijnReservaties->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-4xl mb-3 opacity-80">🅿️</p>
                        <p class="text-gray-400 font-medium text-sm">Geen actieve reserveringen</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($mijnReservaties as $res)
                            <div class="p-4 bg-gray-800 rounded-xl border border-gray-600 shadow-md hover:border-blue-500 transition-colors">
                                <div class="flex justify-between items-start mb-2">
                                    <p class="font-extrabold text-white text-lg">{{ $res->parkingSpot->name }}</p>
                                    <span class="bg-blue-900 text-blue-300 text-xs font-bold px-2 py-1 rounded-md">{{ $res->voertuig }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-sm text-gray-300 mb-2">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $res->datum->format('d-m-Y') }}
                                    </div>
                                    <div class="flex items-center gap-2 text-right justify-end">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($res->start_tijd)->format('H:i') }} - {{ \Carbon\Carbon::parse($res->eind_tijd)->format('H:i') }}
                                    </div>
                                </div>
                                @if($res->kenteken)
                                <p class="text-xs text-gray-400 font-mono tracking-widest bg-gray-900 inline-block px-2 py-1 rounded border border-gray-700 mt-1">
                                    {{ $res->kenteken }}
                                </p>
                                @endif
                                <div class="mt-3 pt-3 border-t border-gray-700 flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-400">Totaal:</span>
                                    <span class="text-lg font-bold text-green-400">€{{ number_format($res->totaal_prijs, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                
                <a href="{{ route('user.reservations') }}" class="btn-primary !w-full !mt-6 text-sm uppercase tracking-wider bg-gray-700 hover:bg-gray-600 block">
                    Alle Reserveringen Bekijken
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('lastUpdate').textContent = 'BIJGEWERKT: ' + new Date().toLocaleTimeString('nl-NL');
    setTimeout(() => location.reload(), 30000);
</script>
@endsection
