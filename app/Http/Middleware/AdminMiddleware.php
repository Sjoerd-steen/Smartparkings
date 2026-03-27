<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware {
    public function handle(Request $request, Closure $next) {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Alleen admins hebben toegang tot deze pagina.');
        }
        return $next($request);
    }
}

// Registreer in app/Http/Kernel.php onder $routeMiddleware:
// 'admin' => \App\Http\Middleware\AdminMiddleware::class,
