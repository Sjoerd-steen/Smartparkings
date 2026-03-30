@extends('layouts.app')

@section('title', 'Inloggen')

@section('content')
<div class="card mt-12">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-extrabold text-white">SmartParking</h2>
    </div>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="mb-4">
            <label class="form-label text-xl">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="form-input"
                   placeholder="Tekstteksttekst">
        </div>
        
        <div class="mb-2">
            <label class="form-label text-xl">Wachtwoord</label>
            <input type="password" name="password" required
                   class="form-input"
                   placeholder="Tekstteksttekst">
        </div>

        <div class="flex items-center justify-between mb-8 mt-2">
            <a href="#" class="text-xs text-white hover:underline">Wachtwoord vergeten?</a>
        </div>

        <button type="submit" class="btn-primary text-xl">
            Inloggen
        </button>
    </form>

    <p class="text-center text-white text-sm mt-8">
        geen account? <a href="{{ route('register') }}" class="font-bold hover:underline">Registreer nu</a>
    </p>
</div>
@endsection
