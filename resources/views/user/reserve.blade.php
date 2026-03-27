@extends('layouts.app')
@section('title', 'Reserveren')
@section('page-title', 'Parkeerplaats Reserveren')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="card">
            <h2 class="text-xl font-bold text-gray-800 mb-6">🅿️ Nieuwe Reservering</h2>

            @if($spots->isEmpty())
                <div class="text-center py-12">
                    <p class="text-5xl mb-4">😔</p>
                    <p class="text-gray-600 font-medium">Geen beschikbare parkeerplaatsen</p>
                    <p class="text-gray-400 text-sm mt-1">Probeer het later opnieuw</p>
                </div>
            @else
                <form method="POST" action="{{ route('user.betaal') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="form-label">Parkeerplaats *</label>
                        <select name="parking_spot_id" required class="form-input">
                            <option value="">-- Kies een parkeerplaats --</option>
                            @foreach($spots as $spot)
                                <option value="{{ $spot->id }}" {{ old('parking_spot_id') == $spot->id ? 'selected' : '' }}>
                                    {{ $spot->name }} – {{ $spot->location }} (€{{ $spot->price_per_hour }}/uur)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Datum *</label>
                            <input type="date" name="datum" value="{{ old('datum', date('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}" required class="form-input">
                        </div>
                        <div></div>
                        <div>
                            <label class="form-label">Starttijd *</label>
                            <input type="time" name="start_tijd" value="{{ old('start_tijd', '09:00') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Eindtijd *</label>
                            <input type="time" name="eind_tijd" value="{{ old('eind_tijd', '11:00') }}" required class="form-input">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Vervoersmiddel *</label>
                        <div class="grid grid-cols-4 gap-3">
                            @foreach(['Auto' => '🚗', 'Motor' => '🏍️', 'Fiets' => '🚲', 'Elektrisch' => '⚡'] as $type => $icon)
                                <label class="flex flex-col items-center p-3 border-2 rounded-lg cursor-pointer transition-all
                {{ old('voertuig', 'Auto') === $type ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-blue-300' }}">
                                    <input type="radio" name="voertuig" value="{{ $type }}" class="hidden"
                                           {{ old('voertuig', 'Auto') === $type ? 'checked' : '' }}
                                           onchange="document.querySelectorAll('[data-voertuig]').forEach(el => el.classList.remove('border-blue-500','bg-blue-50'));
                            this.parentElement.classList.add('border-blue-500','bg-blue-50')">
                                    <span class="text-2xl">{{ $icon }}</span>
                                    <span class="text-xs font-medium mt-1">{{ $type }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Kenteken (optioneel)</label>
                        <input type="text" name="kenteken" value="{{ old('kenteken') }}"
                               class="form-input" placeholder="AA-123-BB" maxlength="10">
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <p class="text-sm text-blue-700">
                            💡 De totaalprijs wordt berekend op basis van het aantal uur × het uurtarief van de geselecteerde parkeerplaats.
                        </p>
                    </div>

                    <button type="submit" class="btn-primary w-full py-3 text-center">
                        Doorgaan naar betaling →
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
