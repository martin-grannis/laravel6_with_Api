<?php

namespace App\Http\Middleware;

use Closure;

class AddAuthHeader
{
    /**
     ** function to extract the token from the cookie and add it to the header of the request before forwarding it.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if no cookie we return 401 error
        // mb patch to return unauthenticated

        if ($request->hasCookie('_token')) {
            $token = $request->cookie('_token');
            if (isset($token) && 'deleted' == $token) {
                return response()->json(
                    ['error' => 'unauthorized'],
                    401
                );
            }
        }

        if (!$request->bearerToken()) {
            if ($request->hasCookie('_token')) {
                $token = $request->cookie('_token');
                $request->headers->add(['Authorization' => 'Bearer '.$token]);
            }
        }

        return $next($request);
    }
}
