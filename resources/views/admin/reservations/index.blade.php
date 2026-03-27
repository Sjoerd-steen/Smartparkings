@extends('layouts.app')
@section('title', 'Reserveringen Beheren')
@section('page-title', 'Reserveringen Beheren')

@section('content')
    <div class="card mb-6">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Zoek op gebruikersnaam..." class="form-input flex-1 min-w-48">
            <select name="status" class="form-input w-40">
                <option value="">Alle statussen</option>
                <option value="actief" {{ request('status') === 'actief' ? 'selected' : '' }}>Actief</option>
                <option value="geannuleerd" {{ request('status') === 'geannuleerd' ? 'selected' : '' }}>Geannuleerd</option>
                <option value="voltooid" {{ request('status') === 'voltooid' ? 'selected' : '' }}>Voltooid</option>
            </select>
            <button type="submit" class="btn-primary">🔍 Filteren</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('admin.reservations.index') }}" class="btn-secondary">✕ Reset</a>
            @endif
        </form>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                <tr class="border-b-2 border-gray-100">
                    <th class="pb-3 text-left text-gray-500 font-medium">Gebruiker</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Parkeerplaats</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Datum</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Tijdslot</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Prijs</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Betaald</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Status</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Acties</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                @foreach($reservations as $res)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3">
                            <p class="font-medium">{{ $res->user->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $res->user->email }}</p>
                        </td>
                        <td class="py-3">{{ $res->parkingSpot->name }}</td>
                        <td class="py-3">{{ $res->datum->format('d-m-Y') }}</td>
                        <td class="py-3 text-gray-500">{{ $res->start_tijd }} – {{ $res->eind_tijd }}</td>
                        <td class="py-3 font-semibold">€{{ number_format($res->totaal_prijs, 2) }}</td>
                        <td class="py-3">
              <span class="{{ $res->betaald ? 'badge-green' : 'badge-red' }}">
                {{ $res->betaald ? '✓ Ja' : 'Nee' }}
              </span>
                        </td>
                        <td class="py-3">
                            <form method="POST" action="{{ route('admin.reservations.update', $res) }}">
                                @csrf @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="text-xs border border-gray-200 rounded px-2 py-1.5">
                                    @foreach(['actief','geannuleerd','voltooid'] as $s)
                                        <option value="{{ $s }}" {{ $res->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="py-3">
                            <form method="POST" action="{{ route('admin.reservations.destroy', $res) }}"
                                  onsubmit="return confirm('Reservering verwijderen?')">
                                @csrf @method('DELETE')
                                <button class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg font-medium">
                                    Verwijderen
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $reservations->appends(request()->query())->links() }}</div>
    </div>
@endsection
