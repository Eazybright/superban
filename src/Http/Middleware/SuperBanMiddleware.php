<?php

namespace Eazybright\SuperBan\Http\Middleware;

use Closure;
use Eazybright\SuperBan\SuperBan;
use Illuminate\Http\Response;

class SuperBanMiddleware
{
    protected $superBan;


    public function __construct(SuperBan $superBan)
    {
        $this->superBan = $superBan;
    }

    public function handle($request, Closure $next, int $maxAttempts, int $attemptWindow, int $decayMinutes)
    {
        $maxAttempts = config('api_rate_limiter.default_limit', 60);
        $decayMinutes = 1;

        // $ip = $request->ip();
        // $userId = $request->user() ? $request->user()->id : null;
        // $route = $request->route() ? $request->route()->getName() : null;

        if ($this->superBan->tooManyAttempts($request, $maxAttempts, $decayMinutes)) {
            return new Response('Too Many Attempts', 429);
        }

        $response = $next($request);


        $remainingAttempts = $this->superBan->attempts($request, $maxAttempts);

        if($remainingAttempts < $maxAttempts){
            $this->superBan->hit($request, $decayMinutes);

            return $response;
        }
    }


}