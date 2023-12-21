<?php

namespace Eazybright\SuperBan\Http\Middleware;

use Closure;
use Eazybright\SuperBan\SuperBan;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class SuperBanMiddleware
{
    protected $superBan;

    public function __construct(SuperBan $superBan)
    {
        $this->superBan = $superBan;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param int $attempts
     * @param int $minutes
     * @param int $duration
     */
    public function handle($request, Closure $next, int $attempts = null, int $minutes = null, int $duration = null)
    {
        $key = $this->superBan->getKey($request);
        $maxAttempts = $attempts ?? config('superban.max_attempts');
        $decayMinutes = $minutes ?? config('superban.decay_rate');
        $banDuration = $duration ?? config('superban.ban_duration');

        $banKey = $key.'_ban';

        // Check if the user is banned
        if ($this->superBan->tooManyAttempts($banKey, 1)) {
            return $this->buildBanResponse($banKey, $maxAttempts);
        }

        // Perform rate limiting
        if ($this->superBan->tooManyAttempts($key, $maxAttempts)) {
            return $this->buildRateLimitResponse($key, $banDuration, $banKey, $maxAttempts);
        }

        $this->superBan->hit($key, $decayMinutes);

        // Continue with the request
        $response = $next($request);
        return $this->buildResponseHeader($response, $maxAttempts, $key);
    }

    protected function buildRateLimitResponse(string $key, int $banDuration, string $banKey, int $maxAttempts)
    {
        $response = new Response('Too Many Attempts.', 429);

        // Calculate the time until the next allowed request
        $retryAfter = $this->superBan->availableIn($key);

        // Ban the user for the specified ban duration
        $this->superBan->hit($banKey, $banDuration);

        return $this->buildResponseHeader($response, $maxAttempts, $banKey, $retryAfter);
    }

    protected function buildBanResponse(string $banKey, int $maxAttempts)
    {
        $response = new Response('User is Banned.', 403);

        // Calculate the time until the ban is lifted
        $retryAfter = $this->superBan->availableIn($banKey);

        return $this->buildResponseHeader($response, $maxAttempts, $banKey, $retryAfter);
    }

    protected function buildResponseHeader(Response $response, int $maxAttempts, string $key, int $retryAfter = null)
    {
        $headers = [
            'X-SuperBan-Limit' => $maxAttempts,
            'X-SuperBan-Remaining' => $this->superBan->remainingAttempts($key, $maxAttempts),  
        ];

        if (! is_null($retryAfter)) {
            $headers['Retry-After'] = $retryAfter;
        }

        $response->headers->add($headers);
        return $response;
    }
}