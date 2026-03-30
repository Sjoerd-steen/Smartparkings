@extends('layouts.app')
@section('title', 'Reserveren')
@section('page-title', 'Parkeerplaats Reserveren')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card relative overflow-hidden">
        {{-- Decorative background element --}}
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>

        <div class="relative z-10">
            <h2 class="text-2xl font-bold text-white border-b border-gray-600 pb-4 mb-6 uppercase tracking-wide">
                🅿️ Nieuwe Reservering
            </h2>

            @if($spots->isEmpty())
                <div class="text-center py-16 bg-gray-800 rounded-xl border border-gray-700">
                    <p class="text-6xl mb-4 opacity-50">😔</p>
                    <p class="text-gray-300 font-semibold text-lg">Geen beschikbare parkeerplaatsen</p>
                    <p class="text-gray-500 text-sm mt-2 uppercase tracking-widest">Probeer het later opnieuw</p>
                </div>
            @else
                <form method="POST" action="{{ route('user.betaal') }}" class="space-y-6">
                    @csrf

                    <div class="bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-inner">
                        <label class="form-label uppercase tracking-wider text-xs text-gray-400 mb-2">Selecteer Parkeerplaats *</label>
                        <select name="parking_spot_id" required class="form-input text-lg font-semibold h-14 bg-gray-700 text-white cursor-pointer focus:ring-2 focus:ring-blue-500 border-none">
                            <option value="" disabled selected>-- Kies een parkeerplaats --</option>
                            @foreach($spots as $spot)
                                <option value="{{ $spot->id }}" {{ old('parking_spot_id') == $spot->id ? 'selected' : '' }}>
                                    {{ $spot->name }} – {{ $spot->location }} (€{{ number_format($spot->price_per_hour, 2) }}/uur)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <label class="form-label uppercase tracking-wider text-xs text-gray-400 mb-2">Datum *</label>
                            <input type="date" name="datum" value="{{ old('datum', date('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}" required class="form-input bg-gray-700 text-white border-none focus:ring-2 focus:ring-blue-500 h-12">
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <label class="form-label uppercase tracking-wider text-xs text-gray-400 mb-2">Starttijd *</label>
                            <input type="time" name="start_tijd" value="{{ old('start_tijd', '09:00') }}" required class="form-input bg-gray-700 text-white border-none focus:ring-2 focus:ring-blue-500 h-12">
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <label class="form-label uppercase tracking-wider text-xs text-gray-400 mb-2">Eindtijd *</label>
                            <input type="time" name="eind_tijd" value="{{ old('eind_tijd', '11:00') }}" required class="form-input bg-gray-700 text-white border-none focus:ring-2 focus:ring-blue-500 h-12">
                        </div>
                    </div>

                    <div class="bg-gray-800 p-5 rounded-xl border border-gray-700 shadow-inner">
                        <div class="flex justify-between items-center mb-4">
                            <label class="form-label uppercase tracking-wider text-xs text-gray-400 !mb-0">Kies een opgeslagen voertuig</label>
                            <span class="text-xs text-blue-400 italic">Optioneel</span>
                        </div>
                        
                        @php
                            $userVehicles = auth()->user()->vehicles ?? collect();
                        @endphp
                        
                        @if($userVehicles->isNotEmpty())
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-5 pb-5 border-b border-gray-600">
                                @foreach($userVehicles as $vehicle)
                                    <label class="flex flex-col p-3 border border-gray-600 bg-gray-700 rounded-lg cursor-pointer transition-all hover:bg-gray-600 vehicle-select-btn">
                                        <input type="radio" name="saved_vehicle_id" value="{{ $vehicle->id }}" class="hidden"
                                            data-type="{{ $vehicle->type }}" data-plate="{{ $vehicle->license_plate }}"
                                            onchange="selectSavedVehicle(this)">
                                        <span class="text-lg font-bold text-white tracking-widest">{{ $vehicle->license_plate }}</span>
                                        <span class="text-xs text-gray-400 uppercase mt-1">Geregistreerde {{ $vehicle->type }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="pb-5 mb-5 border-b border-gray-600 text-sm text-gray-400 italic">
                                U heeft nog geen voertuigen toegevoegd in uw dashboard.
                            </div>
                        @endif

                        <label class="form-label uppercase tracking-wider text-xs text-gray-400 mb-3">Of vul handmatig in *</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                            @foreach(['Auto' => '🚗', 'Motor' => '🏍️', 'Fiets' => '🚲', 'Elektrisch' => '⚡'] as $type => $icon)
                                <label class="flex flex-col items-center p-3 border border-gray-600 bg-gray-700 rounded-lg cursor-pointer transition-all manual-type-btn hover:bg-gray-600
                {{ old('voertuig', 'Auto') === $type ? 'ring-2 ring-blue-500 bg-blue-900/30' : '' }}">
                                    <input type="radio" name="voertuig" id="type_{{ $type }}" value="{{ $type }}" class="hidden"
                                           {{ old('voertuig', 'Auto') === $type ? 'checked' : '' }}
                                           onchange="updateManualType(this)">
                                    <span class="text-3xl mb-2 drop-shadow-md">{{ $icon }}</span>
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-300">{{ $type }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div>
                            <label class="form-label uppercase tracking-wider text-xs text-gray-400 mb-2">Kenteken (optioneel)</label>
                            <input type="text" name="kenteken" id="manual_kenteken" value="{{ old('kenteken') }}"
                                   class="form-input bg-gray-700 text-white font-mono tracking-widest uppercase border-none focus:ring-2 focus:ring-blue-500 h-12" placeholder="AA-123-BB" maxlength="15">
                        </div>
                    </div>

                    <div class="bg-blue-900/30 rounded-xl p-4 border border-blue-800/50 flex gap-4 items-start">
                        <span class="text-2xl">💡</span>
                        <p class="text-sm text-blue-200 mt-1">
                            De totaalprijs wordt berekend op basis van het aantal uur × het uurtarief van de geselecteerde parkeerplaats. Betaling voldoet u in de volgende stap.
                        </p>
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 text-lg tracking-widest uppercase shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                        Doorgaan naar betaling →
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    function updateManualType(radio) {
        // Clear saved vehicle selection
        document.querySelectorAll('input[name="saved_vehicle_id"]').forEach(el => el.checked = false);
        document.querySelectorAll('.vehicle-select-btn').forEach(el => el.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-900/30'));
        
        // Update manual UI
        document.querySelectorAll('.manual-type-btn').forEach(el => el.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-900/30'));
        radio.parentElement.classList.add('ring-2', 'ring-blue-500', 'bg-blue-900/30');
    }

    function selectSavedVehicle(radio) {
        // Highlight saved vehicle card
        document.querySelectorAll('.vehicle-select-btn').forEach(el => el.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-900/30'));
        radio.parentElement.classList.add('ring-2', 'ring-blue-500', 'bg-blue-900/30');

        // Apply data to manual inputs implicitly
        const type = radio.getAttribute('data-type');
        const plate = radio.getAttribute('data-plate');
        
        // Find matching type radio and check it visually
        const typeRadio = document.getElementById('type_' + type);
        if (typeRadio) {
            typeRadio.checked = true;
            document.querySelectorAll('.manual-type-btn').forEach(el => el.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-900/30'));
            typeRadio.parentElement.classList.add('ring-2', 'ring-blue-500', 'bg-blue-900/30');
        }

        // Fill in license plate
        document.getElementById('manual_kenteken').value = plate;
    }
</script>
@endsection
