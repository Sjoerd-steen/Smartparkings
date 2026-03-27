<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>SmartParking – Registreren</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">🅿</div>
        <h1 class="text-3xl font-bold text-white">SmartParking</h1>
    </div>

    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20 shadow-2xl">
        <h2 class="text-xl font-bold text-white mb-6">Account aanmaken</h2>

        @if($errors->any())
            <div class="bg-red-500/20 border border-red-400 text-red-200 rounded-lg p-3 mb-5 text-sm">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-blue-200 text-sm font-medium mb-1.5">Gebruikersnaam</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full bg-white/10 border border-white/30 text-white placeholder-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       placeholder="Uw naam">
            </div>
            <div>
                <label class="block text-blue-200 text-sm font-medium mb-1.5">E-mailadres</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-white/10 border border-white/30 text-white placeholder-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       placeholder="uw@email.nl">
            </div>
            <div>
                <label class="block text-blue-200 text-sm font-medium mb-1.5">Wachtwoord</label>
                <input type="password" name="password" required minlength="6"
                       class="w-full bg-white/10 border border-white/30 text-white placeholder-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       placeholder="Minimaal 6 tekens">
            </div>
            <div>
                <label class="block text-blue-200 text-sm font-medium mb-1.5">Herhaal wachtwoord</label>
                <input type="password" name="password_confirmation" required
                       class="w-full bg-white/10 border border-white/30 text-white placeholder-blue-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400"
                       placeholder="••••••••">
            </div>
            <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-400 text-white font-bold py-3 rounded-lg transition-colors mt-2">
                Registreren
            </button>
        </form>

        <p class="text-center text-blue-300 text-sm mt-6">
            Al een account?
            <a href="{{ route('login') }}" class="text-white font-semibold hover:underline">Inloggen</a>
        </p>
    </div>
</div>

</body>
</html>
