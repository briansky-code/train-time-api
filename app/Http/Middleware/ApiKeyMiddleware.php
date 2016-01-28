<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->has('api_key') and $request->api_key == env('PENN_API_KEY')) {
            return $next($request);
        } else {
            $response = array('message' => 'Forbidden', 'status' => 403);
            return response()->json($response)->setStatusCode(403, 'Forbidden');
        }
    }
}
