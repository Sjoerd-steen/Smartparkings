@extends('layouts.app')
@section('title', 'Betalen')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-white border-b border-gray-600 pb-4 mb-6 uppercase tracking-wide">
        💳 Betaling Afronden
    </h2>

    <div class="grid md:grid-cols-2 gap-8">
        {{-- OVERZICHT --}}
        <div class="card relative overflow-hidden h-fit">
            <div class="absolute -top-16 -right-16 w-32 h-32 bg-green-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            
            <h3 class="font-bold text-white mb-6 uppercase tracking-wider text-lg border-b border-gray-600 pb-2">📋 Reserveringsoverzicht</h3>
            <div class="space-y-4 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400 uppercase tracking-widest text-xs">Locatie</span>
                    <span class="font-bold text-white">{{ $spot->name }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400 uppercase tracking-widest text-xs">Verdieping</span>
                    <span class="font-bold text-white">{{ $spot->location ?? '–' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400 uppercase tracking-widest text-xs">Datum</span>
                    <span class="font-bold text-white">{{ $formData['datum'] }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400 uppercase tracking-widest text-xs">Tijdslot</span>
                    <span class="font-bold text-white">{{ $formData['start_tijd'] }} – {{ $formData['eind_tijd'] }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400 uppercase tracking-widest text-xs">Voertuig</span>
                    <span class="font-bold text-white">{{ $formData['voertuig'] }} {{ $formData['kenteken'] ? '('.$formData['kenteken'].')' : '' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400 uppercase tracking-widest text-xs">Duur</span>
                    <span class="font-bold text-white">{{ $uren }} uur</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-700">
                    <span class="text-gray-400 uppercase tracking-widest text-xs">Tarief</span>
                    <span class="font-bold text-white">€{{ number_format($spot->price_per_hour, 2) }}/uur</span>
                </div>
                
                <div class="flex justify-between items-center py-4 bg-gray-800 px-4 rounded-xl border border-gray-600 shadow-inner mt-4">
                    <span class="font-bold text-gray-300 uppercase tracking-widest">Totaalprijs</span>
                    <span class="font-extrabold text-2xl text-green-400 drop-shadow-md">€{{ number_format($prijs, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- BETAALFORMULIER --}}
        <div class="card">
            <h3 class="font-bold text-white mb-6 uppercase tracking-wider text-lg border-b border-gray-600 pb-2">💳 Betaalmethode</h3>

            <form method="POST" action="{{ route('user.reservations.store') }}">
                @csrf
                {{-- Hidden fields --}}
                @foreach($formData as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <div class="space-y-3 mb-6">
                    @foreach([
                        'ideal' => ['iDEAL', '🏦', 'Snel en veilig betalen via uw eigen bank.'], 
                        'paypal' => ['PayPal', '💸', 'Afrekenen met uw PayPal saldo of gekoppelde kaart.'], 
                        'tikkie' => ['Tikkie', '📱', 'Betaalverzoek via uw mobiele telefoon.'], 
                        'maestro' => ['Maestro / Creditcard', '��', 'Betalen via pinpas of creditcard.']
                    ] as $val => [$label, $icon, $desc])
                        <label class="flex items-start gap-4 p-4 border border-gray-600 bg-gray-700 rounded-xl cursor-pointer hover:bg-gray-600 transition-all
          {{ old('betaal_methode') === $val ? 'ring-2 ring-blue-500 bg-blue-900/30' : '' }}" onclick="document.querySelectorAll('input[name=betaal_methode]').forEach(el => el.parentElement.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-900/30')); this.classList.add('ring-2', 'ring-blue-500', 'bg-blue-900/30');">
                            <div class="flex items-center h-full pt-1">
                                <input type="radio" name="betaal_methode" value="{{ $val }}" required
                                       class="text-blue-500 bg-gray-800 border-gray-600 focus:ring-blue-500 focus:ring-opacity-50" {{ old('betaal_methode') === $val ? 'checked' : '' }}>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl drop-shadow-md">{{ $icon }}</span>
                                    <span class="font-bold text-white tracking-wide">{{ $label }}</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">{{ $desc }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="bg-gray-800 rounded-xl p-4 mb-6 border border-gray-700 shadow-inner">
                    <p class="font-bold text-gray-300 mb-2 uppercase tracking-wide text-xs">Klantovereenkomst</p>
                    <p class="text-xs text-gray-400 leading-relaxed">Door te betalen gaat u akkoord met de reserveringsvoorwaarden van SmartParking. Annulering is mogelijk tot 2 uur voor aanvang met volledige restitutie.</p>
                </div>

                <label class="flex items-start gap-3 mb-6 cursor-pointer p-2 hover:bg-gray-800/50 rounded-lg transition-colors">
                    <input type="checkbox" name="agree" value="1" required class="mt-1 rounded bg-gray-700 border-gray-600 text-blue-500 focus:ring-blue-500">
                    <span class="text-sm text-gray-300 font-medium">Ik ga akkoord met de <a href="#" class="text-blue-400 hover:underline">klantovereenkomst</a></span>
                </label>

                <button type="submit" class="btn-primary w-full py-4 text-lg tracking-widest uppercase shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all flex justify-center items-center gap-2">
                    <span>Betalen</span> 
                    <span class="bg-black/20 px-2 py-1 rounded text-sm whitespace-nowrap">€{{ number_format($prijs, 2) }}</span>
                </button>

                <a href="{{ route('user.reserve') }}" class="block text-center text-gray-400 text-sm mt-5 hover:text-white transition-colors uppercase tracking-widest">
                    ← Terug naar reservering
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
