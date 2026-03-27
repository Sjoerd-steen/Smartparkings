@extends('layouts.app')
@section('title', 'Betalen')
@section('page-title', 'Betaling Afronden')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="grid md:grid-cols-2 gap-6">

            {{-- OVERZICHT --}}
            <div class="card">
                <h3 class="font-bold text-gray-800 mb-4">📋 Reserveringsoverzicht</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Locatie</span>
                        <span class="font-medium">{{ $spot->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Verdieping</span>
                        <span class="font-medium">{{ $spot->location ?? '–' }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Datum</span>
                        <span class="font-medium">{{ $formData['datum'] }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Tijdslot</span>
                        <span class="font-medium">{{ $formData['start_tijd'] }} – {{ $formData['eind_tijd'] }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Duur</span>
                        <span class="font-medium">{{ $uren }} uur</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-500">Tarief</span>
                        <span class="font-medium">€{{ $spot->price_per_hour }}/uur</span>
                    </div>
                    <div class="flex justify-between py-3 bg-blue-50 px-3 rounded-lg">
                        <span class="font-bold text-blue-800">Totaalprijs</span>
                        <span class="font-bold text-xl text-blue-700">€{{ number_format($prijs, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- BETAALFORMULIER --}}
            <div class="card">
                <h3 class="font-bold text-gray-800 mb-4">💳 Betaalmethode</h3>

                <form method="POST" action="{{ route('user.reservations.store') }}">
                    @csrf
                    {{-- Hidden fields --}}
                    @foreach($formData as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <div class="space-y-3 mb-5">
                        @foreach(['ideal' => ['iDEAL', '🏦'], 'paypal' => ['PayPal', '💸'], 'tikkie' => ['Tikkie', '📱'], 'maestro' => ['Maestro', '💳']] as $val => [$label, $icon])
                            <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer hover:border-blue-400 transition-all
              {{ old('betaal_methode') === $val ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                <input type="radio" name="betaal_methode" value="{{ $val }}" required
                                       class="text-blue-600" {{ old('betaal_methode') === $val ? 'checked' : '' }}>
                                <span class="text-xl">{{ $icon }}</span>
                                <span class="font-medium">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 mb-5 text-sm text-gray-600 border border-gray-200">
                        <p class="font-medium text-gray-800 mb-2">Klantovereenkomst</p>
                        <p>Door te betalen gaat u akkoord met de reserveringsvoorwaarden van SmartParking. Annulering is mogelijk tot 2 uur voor aanvang.</p>
                    </div>

                    <label class="flex items-start gap-3 mb-5 cursor-pointer">
                        <input type="checkbox" name="agree" value="1" required class="mt-0.5 rounded">
                        <span class="text-sm text-gray-600">Ik ga akkoord met de klantovereenkomst [Accept]</span>
                    </label>

                    <button type="submit" class="btn-primary w-full py-3">
                        💳 Betalen – €{{ number_format($prijs, 2) }}
                    </button>

                    <a href="{{ route('user.reserve') }}" class="block text-center text-gray-500 text-sm mt-3 hover:underline">
                        ← Terug naar reservering
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection
