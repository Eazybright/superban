<?php

// config for Eazybright\SuperBan
return [
    /*
    |--------------------------------------------------------------------------
    | Maximum Attempts
    |--------------------------------------------------------------------------
    |
    | This is the default number of requests allowed per minute
    |
    */
    'max_attempts' => 60,

    /*
    |--------------------------------------------------------------------------
    | Rate Limit By
    |--------------------------------------------------------------------------
    |
    | Specify how your application ban user requests, either by 'ip', 'user_id', or 'email'
    |
    */
    'rate_limit_by' => 'ip',

    /*
    |--------------------------------------------------------------------------
    | Decay Rate
    |--------------------------------------------------------------------------
    |
    | This is the default minutes for the number of requests can occur.
    | The available attempts are reset after the time elapses.
    | For example, 60 requests can occur within 1 minute.
    |
    */
    'decay_rate' => 1,

    /*
    |--------------------------------------------------------------------------
    | Ban Duration
    |--------------------------------------------------------------------------
    |
    | This is the default minutes for which the user is banned for.
    |
    */
    'ban_duration' => 1,
];
