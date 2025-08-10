<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!session('is_logged_in')) {
            // TODO
            /**
             * Ini kasih redirect access ditolak atau kode 401
             */
            return redirect()->route('login');
        }
        return $next($request);
    }
}
