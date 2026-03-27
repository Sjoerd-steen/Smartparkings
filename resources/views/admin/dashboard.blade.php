@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
    {{-- STATS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        @foreach([
          ['Gebruikers', $totalUsers, '👥', 'blue'],
          ['Parkeerplaatsen', $totalSpots, '🅿️', 'indigo'],
          ['Reserveringen', $totalReservations, '📅', 'purple'],
          ['Omzet', '€'.number_format($omzet,2), '💰', 'green'],
        ] as [$label, $value, $icon, $color])
            <div class="card">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl">{{ $icon }}</span>
                    <span class="badge-blue text-xs">Live</span>
                </div>
                <p class="text-2xl font-bold text-gray-800">{{ $value }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mb-8">
        {{-- Quick Links --}}
        <div class="card">
            <h3 class="font-bold text-gray-800 mb-4">⚡ Snel Navigeren</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.spots.create') }}" class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors text-blue-700 font-medium text-sm">
                    <span>➕</span> Parkeerplaats toevoegen
                </a>
                <a href="{{ route('admin.reservations.index') }}" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors text-purple-700 font-medium text-sm">
                    <span>📋</span> Reserveringen beheren
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors text-green-700 font-medium text-sm">
                    <span>👥</span> Gebruikers beheren
                </a>
            </div>
        </div>

        {{-- Bezettingsgraad --}}
        <div class="card">
            <h3 class="font-bold text-gray-800 mb-4">📊 Bezettingsgraad</h3>
            <div class="text-center py-4">
                <div class="text-5xl font-black {{ $bezettingsgraad > 80 ? 'text-red-500' : ($bezettingsgraad > 50 ? 'text-yellow-500' : 'text-green-500') }}">
                    {{ $bezettingsgraad }}%
                </div>
                <p class="text-gray-500 text-sm mt-2">van alle parkeerplaatsen bezet</p>
            </div>
            <div class="h-4 bg-gray-200 rounded-full overflow-hidden mt-3">
                <div class="h-full rounded-full {{ $bezettingsgraad > 80 ? 'bg-red-500' : ($bezettingsgraad > 50 ? 'bg-yellow-400' : 'bg-green-500') }}"
                     style="width:{{ $bezettingsgraad }}%"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-400 mt-2">
                <span>{{ $beschikbaar }} beschikbaar</span>
                <span>{{ $totalSpots - $beschikbaar }} bezet</span>
            </div>
        </div>

        {{-- Actieve reserveringen --}}
        <div class="card">
            <h3 class="font-bold text-gray-800 mb-4">🔴 Live Overzicht</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <span class="text-sm text-gray-600">Beschikbaar</span>
                    <span class="font-bold text-green-700">{{ $beschikbaar }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <span class="text-sm text-gray-600">Actieve res.</span>
                    <span class="font-bold text-blue-700">{{ $actief }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Recente Reserveringen --}}
    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-gray-800">📋 Recente Reserveringen</h3>
            <a href="{{ route('admin.reservations.index') }}" class="text-blue-600 text-sm hover:underline">Alles bekijken →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                <tr class="border-b border-gray-100">
                    <th class="pb-3 text-left text-gray-500 font-medium">Gebruiker</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Parkeerplaats</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Datum</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Prijs</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Status</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                @foreach($recentReservations as $res)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3">{{ $res->user->name }}</td>
                        <td class="py-3">{{ $res->parkingSpot->name }}</td>
                        <td class="py-3 text-gray-500">{{ $res->datum->format('d-m-Y') }}</td>
                        <td class="py-3 font-semibold">€{{ number_format($res->totaal_prijs, 2) }}</td>
                        <td class="py-3">
              <span class="{{ $res->status === 'actief' ? 'badge-green' : ($res->status === 'geannuleerd' ? 'badge-red' : 'badge-blue') }}">
                {{ ucfirst($res->status) }}
              </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
