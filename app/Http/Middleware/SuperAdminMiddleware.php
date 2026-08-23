<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isSuperAdmin()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'অননুমোদিত: শুধুমাত্র প্ল্যাটফর্ম সুপার-অ্যাডমিন এই ফিচার এক্সেস করতে পারেন।'
                ], 403);
            }

            return redirect()->route('pos.index')->with('error', 'শুধুমাত্র সুপার-অ্যাডমিন এই ড্যাশবোর্ড এক্সেস করতে পারবেন!');
        }

        return $next($request);
    }
}
