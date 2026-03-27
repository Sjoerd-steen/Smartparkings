@extends('layouts.app')
@section('title', 'Gebruiker Bewerken')
@section('page-title', 'Gebruiker Bewerken')

@section('content')
    <div class="max-w-lg mx-auto card">
        <div class="flex items-center gap-4 mb-6 pb-5 border-b border-gray-100">
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center text-2xl font-bold text-blue-700">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="font-bold text-gray-800">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="form-label">Naam</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">E-mailadres</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
            </div>
            <div>
                <label class="form-label">Rol</label>
                <select name="role" class="form-input">
                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Gebruiker</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1">Opslaan</button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary flex-1 text-center">Annuleren</a>
            </div>
        </form>
    </div>
@endsection
