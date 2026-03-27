@extends('layouts.app')
@section('title', isset($spot) ? 'Parkeerplaats Bewerken' : 'Parkeerplaats Toevoegen')
@section('page-title', isset($spot) ? 'Parkeerplaats Bewerken' : 'Nieuwe Parkeerplaats')

@section('content')
    <div class="max-w-xl mx-auto card">
        <form method="POST" action="{{ isset($spot) ? route('admin.spots.update', $spot) : route('admin.spots.store') }}" class="space-y-4">
            @csrf
            @if(isset($spot)) @method('PUT') @endif

            <div>
                <label class="form-label">Naam / Nummer *</label>
                <input type="text" name="name" value="{{ old('name', $spot->name ?? '') }}"
                       required placeholder="bijv. A1, B-12" class="form-input">
            </div>
            <div>
                <label class="form-label">Locatie</label>
                <input type="text" name="location" value="{{ old('location', $spot->location ?? '') }}"
                       placeholder="bijv. Verdieping 2, Hal A" class="form-input">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Type *</label>
                    <select name="type" required class="form-input">
                        @foreach(['Standaard','Gehandicapt','Motor','Elektrisch'] as $type)
                            <option value="{{ $type }}" {{ old('type', $spot->type ?? 'Standaard') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Prijs per uur (€) *</label>
                    <input type="number" name="price_per_hour" step="0.50" min="0"
                           value="{{ old('price_per_hour', $spot->price_per_hour ?? '2.50') }}"
                           required class="form-input">
                </div>
            </div>
            <div>
                <label class="form-label">Status *</label>
                <select name="status" required class="form-input">
                    @foreach(['beschikbaar','bezet','gereserveerd'] as $status)
                        <option value="{{ $status }}" {{ old('status', $spot->status ?? 'beschikbaar') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3 pt-3">
                <button type="submit" class="btn-primary flex-1">
                    {{ isset($spot) ? '💾 Opslaan' : '➕ Toevoegen' }}
                </button>
                <a href="{{ route('admin.spots.index') }}" class="btn-secondary flex-1 text-center">Annuleren</a>
            </div>
        </form>
    </div>
@endsection
