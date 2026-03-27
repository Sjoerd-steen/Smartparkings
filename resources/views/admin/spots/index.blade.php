@extends('layouts.app')
@section('title', 'Parkeerplaatsen')
@section('page-title', 'Parkeerplaatsen Beheren')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <p class="text-gray-500">{{ $spots->total() }} parkeerplaatsen in totaal</p>
        <a href="{{ route('admin.spots.create') }}" class="btn-primary">+ Toevoegen</a>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                <tr class="border-b-2 border-gray-100">
                    <th class="pb-3 text-left text-gray-500 font-medium">Naam</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Locatie</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Type</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Tarief/uur</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Status</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Res.</th>
                    <th class="pb-3 text-left text-gray-500 font-medium">Acties</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                @foreach($spots as $spot)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 font-semibold">{{ $spot->name }}</td>
                        <td class="py-3 text-gray-500">{{ $spot->location ?? '–' }}</td>
                        <td class="py-3">{{ $spot->type }}</td>
                        <td class="py-3">€{{ $spot->price_per_hour }}</td>
                        <td class="py-3">
              <span class="{{ $spot->status === 'beschikbaar' ? 'badge-green' : ($spot->status === 'gereserveerd' ? 'badge-yellow' : 'badge-red') }}">
                {{ ucfirst($spot->status) }}
              </span>
                        </td>
                        <td class="py-3 text-center">{{ $spot->reservations_count }}</td>
                        <td class="py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.spots.edit', $spot) }}"
                                   class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg font-medium">
                                    Bewerken
                                </a>
                                <form method="POST" action="{{ route('admin.spots.destroy', $spot) }}"
                                      onsubmit="return confirm('Parkeerplaats {{ $spot->name }} verwijderen?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg font-medium">
                                        Verwijderen
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-5">{{ $spots->links() }}</div>
    </div>
@endsection
