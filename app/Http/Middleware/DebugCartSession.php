<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DebugCartSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Log session and authentication info for debugging
        if ($request->is('cart*') || $request->is('checkout*')) {
            Log::info('Cart/Checkout request debug', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'session_id' => session()->getId(),
                'user_id' => Auth::id(),
                'user_authenticated' => Auth::check(),
                'request_data' => $request->all()
            ]);
        }

        return $next($request);
    }
}
