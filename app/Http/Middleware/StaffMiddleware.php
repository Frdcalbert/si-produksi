<?php
// app/Http/Middleware/StaffMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'Staff') {
            return $next($request);
        }
        
        abort(403, 'Unauthorized access. Staff only.');
    }
}