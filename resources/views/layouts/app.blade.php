<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartParking – @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            font-family: 'Segoe UI', system-ui, sans-serif; 
            background-image: url('/images/background.png');
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }
        .main-content { min-height: calc(100vh - 80px); }
        .card { background-color: #3b4b6b; border-radius: 0.5rem; padding: 2rem; color: white; width: 100%; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .form-input { width: 100%; background-color: #d1d5db; color: #1f2937; border: none; border-radius: 0.5rem; padding: 0.75rem 1rem; margin-top: 0.5rem; outline: none; }
        .form-label { display: block; font-size: 1rem; font-weight: bold; color: white; margin-bottom: 0.25rem; }
        .btn-primary { background-color: #1f2937; color: white; font-weight: bold; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-align: center; display: inline-block; cursor: pointer; border: none; transition: background 0.2s;}
        .btn-primary:hover { background-color: #111827; }
        .btn-danger { background-color: #ef4444; color: white; font-weight: bold; padding: 0.5rem 1rem; border-radius: 0.5rem; text-align: center; display: inline-block; cursor: pointer; border: none; transition: background 0.2s;}
        .btn-danger:hover { background-color: #dc2626; }
    </style>
</head>
<body class="antialiased">

    {{-- TOP HEADER --}}
    <header class="bg-[#3b5998] h-[80px] px-8 flex items-center justify-between shadow-md">
        {{-- Left: Logo --}}
        <a href="/" class="flex items-center h-full py-2">
            <img src="/images/Screenshot%202026-03-06%20at%2010.43.38%E2%80%AFAM%204.png" alt="SmartParking Logo" class="max-h-12 w-auto object-contain">
        </a>

        {{-- Right: Navigation --}}
        <nav class="flex items-center gap-6">
            @auth
                <a href="{{ route('user.dashboard') }}" class="text-black hover:text-gray-200 flex flex-col items-center">
                    <svg class="w-8 h-8 rounded-full border-2 border-black p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-xs mt-1 font-bold tracking-widest uppercase">Home</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="text-black hover:text-gray-200 flex flex-col items-center bg-transparent border-none cursor-pointer">
                        <svg class="w-8 h-8 rounded-full border-2 border-black p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="text-xs mt-1 font-bold tracking-widest uppercase">Logout</span>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-black hover:text-gray-800 flex flex-col items-center">
                    <svg class="w-8 h-8 rounded-full border-2 border-black p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="text-xs mt-1 font-bold tracking-widest uppercase">Login</span>
                </a>
                <a href="/" class="text-black hover:text-gray-800 flex flex-col items-center">
                    <svg class="w-8 h-8 rounded-full border-2 border-black p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="text-xs mt-1 font-bold tracking-widest uppercase">Home</span>
                </a>
            @endauth
        </nav>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="main-content flex flex-col items-center w-full px-4 py-8">
        <div class="w-full max-w-6xl mx-auto">
            @if(session('success'))
                <div class="bg-green-500 text-white font-bold px-4 py-3 rounded relative mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500 text-white font-bold px-4 py-3 rounded relative mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>
