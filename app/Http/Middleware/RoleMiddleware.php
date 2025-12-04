<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\error;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $roles)
    {
        $roles = explode(',', $roles);

        if (!Auth::check()) {
            return redirect()->route('styluxe.unauthorized');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            return redirect()->route('styluxe.unauthorized');
        }

        return $next($request);
    }
}
?>