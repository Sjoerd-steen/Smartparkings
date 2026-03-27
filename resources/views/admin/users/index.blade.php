@extends('layouts.app')
@section('title', 'Gebruikersbeheer')
@section('page-title', 'Gebruikersbeheer')

@section('content')
    {{-- Zoekbalk --}}
    <div class="card mb-6">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Zoek op naam of e-mail..."
                   class="form-input flex-1">
            <button type="submit" class="btn-primary">🔍 Zoeken</button>
            @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">✕ Wissen</a>
            @endif
        </form>
    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-gray-800">Alle Gebruikers ({{ $users->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                <tr class="border-b-2 border-gray-100">
                    <th class="pb-3 text-left text-gray-500 font-medium">ID</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Naam</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">E-mail</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Rol</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Reserveringen</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Status</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Acties</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50 {{ $user->is_banned ? 'opacity-60' : '' }}">
                        <td class="py-3 text-gray-400">#{{ $user->id }}</td>
                        <td class="py-3 font-medium">{{ $user->name }}</td>
                        <td class="py-3 text-gray-600">{{ $user->email }}</td>
                        <td class="py-3">
              <span class="{{ $user->role === 'admin' ? 'badge-blue' : 'badge-green' }}">
                {{ ucfirst($user->role) }}
              </span>
                        </td>
                        <td class="py-3 text-center">{{ $user->reservations_count }}</td>
                        <td class="py-3">
              <span class="{{ $user->is_banned ? 'badge-red' : 'badge-green' }}">
                {{ $user->is_banned ? '🚫 Geblokkeerd' : '✓ Actief' }}
              </span>
                        </td>
                        <td class="py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg font-medium">
                                    Bewerken
                                </a>
                                <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                                    @csrf
                                    <button class="text-xs {{ $user->is_banned ? 'bg-green-100 hover:bg-green-200 text-green-700' : 'bg-red-100 hover:bg-red-200 text-red-700' }} px-3 py-1.5 rounded-lg font-medium">
                                        {{ $user->is_banned ? 'Deblokkeer' : 'Blokkeer' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5">{{ $users->appends(request()->query())->links() }}</div>
    </div>
@endsection
