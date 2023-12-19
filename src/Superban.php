<?php

namespace Eazybright\SuperBan;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;

class Superban
{
    protected $rateLimiter;

    public function __construct(RateLimiter $rateLimiter)
    {
        $this->rateLimiter = $rateLimiter;
    }

    /**
     * Set rate limit key.
     */
    public function getKey(Request $request): string
    {
        $rateLimitBy = config('superban.rate_limit_by', 'ip');

        return match($rateLimitBy){
            'ip' => $this->banByIp($request),
            'user' => $this->banByUserId($request),
            'email' => $this->banByEmail($request),
            default => throw new \InvalidArgumentException('Invalid rate_limit_by configuration.')
        };
    }

    /**
     * 
     */
    public function setKey()
    {
        
    }

    public function attempts(Request $request)
    {
        return $this->rateLimiter->attempts($this->getKey($request));
    }

    public function tooManyAttempts(Request $request, $maxAttempts, $decayMinutes)
    {
        return $this->rateLimiter->tooManyAttempts(
            $this->getKey($request),
            $maxAttempts,
            $decayMinutes
        );
    }

    public function hit(Request $request, $decayMinutes = 1)
    {
        return $this->rateLimiter->hit(
            $this->getKey($request),
            $decayMinutes
        );
    }

    public function remainingAttempts(Request $request, $maxAttempts): int
    {
        return $this->rateLimiter->remaining(
            $this->getKey($request),
            $maxAttempts
        );
    }

    public function resetTime(Request $request)
    {
        return $this->rateLimiter->availableIn($this->getKey($request));
    }

    protected function banByIp(Request $request): string
    {
        return 'superban:' . $request->ip();
    }

    protected function banByUserId(Request $request): string
    {
        $user = $request->user();

        if (!$user) {
            return $this->banByIp($request);
        }

        return 'superban:user:' . $user->id;
    }

    protected function banByEmail(Request $request): string
    {
        $user = $request->user();

        if (!$user) {
            return $this->banByIp($request);   
        }

        return 'superban:email:' . $user->email;
    }

}
