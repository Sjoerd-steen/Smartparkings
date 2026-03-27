<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartParking – @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50:'#eff6ff', 100:'#dbeafe', 500:'#3b82f6', 600:'#2563eb', 700:'#1d4ed8', 800:'#1e40af', 900:'#1e3a8a' }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
        .sidebar { width: 260px; min-height: 100vh; }
        .main-content { flex: 1; min-height: 100vh; }
        .nav-link { @apply flex items-center gap-3 px-4 py-3 rounded-lg text-blue-200 hover:bg-blue-700 hover:text-white transition-all duration-200; }
        .nav-link.active { @apply bg-blue-600 text-white; }
        .card { @apply bg-white rounded-xl shadow-sm border border-gray-100 p-6; }
        .btn-primary { @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg transition-colors; }
        .btn-secondary { @apply bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2.5 rounded-lg transition-colors; }
        .btn-danger { @apply bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg transition-colors; }
        .badge-green { @apply inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800; }
        .badge-red { @apply inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800; }
        .badge-yellow { @apply inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800; }
        .badge-blue { @apply inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800; }
        .form-input { @apply w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all; }
        .form-label { @apply block text-sm font-medium text-gray-700 mb-1.5; }
        .alert-success { @apply bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 flex items-center gap-2; }
        .alert-error { @apply bg-red-50 border border-red-200 text-red-800 rounded-lg p-4; }
    </style>
</head>
<body class="bg-gray-50">
<div class="flex">

    {{-- SIDEBAR --}}
    <aside class="sidebar bg-blue-900 text-white flex flex-col fixed left-0 top-0 z-40">
        <div class="p-6 border-b border-blue-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center font-bold text-lg">🅿</div>
                <div>
                    <div class="font-bold text-lg leading-tight">SmartParking</div>
                    <div class="text-blue-300 text-xs">Slim Parkeerbeheer</div>
                </div>
            </div>
        </div>

        <nav class="p-4 flex-1 space-y-1">
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span>📊</span> Dashboard
                    </a>
                    <a href="{{ route('admin.spots.index') }}" class="nav-link {{ request()->routeIs('admin.spots*') ? 'active' : '' }}">
                        <span>🅿️</span> Parkeerplaatsen
                    </a>
                    <a href="{{ route('admin.reservations.index') }}" class="nav-link {{ request()->routeIs('admin.reservations*') ? 'active' : '' }}">
                        <span>📅</span> Reserveringen
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <span>👥</span> Gebruikers
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                        <span>🏠</span> Dashboard
                    </a>
                    <a href="{{ route('user.reserve') }}" class="nav-link {{ request()->routeIs('user.reserve') ? 'active' : '' }}">
                        <span>➕</span> Reserveren
                    </a>
                    <a href="{{ route('user.reservations') }}" class="nav-link {{ request()->routeIs('user.reservations') ? 'active' : '' }}">
                        <span>📋</span> Mijn Reserveringen
                    </a>
                @endif
            @endauth
        </nav>

        <div class="p-4 border-t border-blue-800">
            @auth
                <div class="flex items-center gap-3 mb-3 px-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-medium truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-blue-300">{{ ucfirst(auth()->user()->role) }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-left nav-link text-red-300 hover:text-red-200 hover:bg-red-900/30">
                        <span>🚪</span> Uitloggen
                    </button>
                </form>
            @endauth
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="main-content ml-[260px]">
        {{-- TOP BAR --}}
        <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between sticky top-0 z-30">
            <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            @auth
                <span class="text-sm text-gray-500">Welkom, <strong>{{ auth()->user()->name }}</strong>
          @if(auth()->user()->isAdmin())
                        <span class="ml-2 badge-blue">Admin</span>
                    @endif
        </span>
            @endauth
        </header>

        <div class="p-8">
            {{-- FLASH MESSAGES --}}
            @if(session('success'))
                <div class="alert-success mb-6">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error mb-6">
                    <p class="font-medium mb-1">⚠️ Er zijn fouten:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</div>
</body>
</html>
