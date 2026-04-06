<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Scheme;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveScheme
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('get')) {

            if (! $request->scheme_id) {
                abort(400, 'Scheme context missing.');
            }

            $activeScheme = Scheme::where('is_active', 1)->value('id');

            if ($request->scheme_id != $activeScheme) {

                return redirect()->back()->withErrors([
                    'scheme' => 'The active scheme has changed. Please refresh the page.',
                ]);

            }

        }

        return $next($request);
    }
}
