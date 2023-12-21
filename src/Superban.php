<?php

namespace Eazybright\SuperBan;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;

class SuperBan
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
            'ip' => $this->setKeyByIp($request),
            'user' => $this->setKeyByUserId($request),
            'email' => $this->setKeyByEmail($request),
            default => throw new \InvalidArgumentException('Invalid rate_limit_by configuration.')
        };
    } 

    /**
     * Determine if the given key has been "accessed" too many times.
     */
    public function tooManyAttempts(string $key, $maxAttempts): int
    {
        return $this->rateLimiter->tooManyAttempts(
            $key,
            $maxAttempts
        );
    }

     /**
     * Increment the counter for a given key for a given decay time.
     */
    public function hit(string $key, int $decayMinutes = 1): int
    {
        return $this->rateLimiter->hit(
            $key,
            $decayMinutes * 60
        );
    }

    /**
     * Get the number of seconds until the "key" is accessible again.
     *
     * @param  string  $key
     * @return int
     */
    public function availableIn(string $key): int
    {
        return $this->rateLimiter->availableIn($key);
    }

     /**
     * Get the number of retries left for the given key.
     */
    public function remainingAttempts(string $key, int $maxAttempts): int
    {
        return $this->rateLimiter->remaining(
            $key,
            $maxAttempts
        );
    }

    protected function setKeyByIp(Request $request): string
    {
        return 'superban:' . $request->ip();
    }

    protected function setKeyByUserId(Request $request): string
    {
        $user = $request->user();

        if (!$user) {
            return $this->setKeyByIp($request);
        }

        return 'superban:user:' . $user->id;
    }

    protected function setKeyByEmail(Request $request): string
    {
        $user = $request->user();

        if (!$user) {
            return $this->setKeyByIp($request);   
        }

        return 'superban:email:' . $user->email;
    }

}
