<?php

// config for Eazybright\SuperBan
return [
    /*
    |--------------------------------------------------------------------------
    | Default Number of Requests
    |--------------------------------------------------------------------------
    |
    | This is the default number of requests allowed per minute
    |
    */
    'default_limit' => 60, 

    /*
    |--------------------------------------------------------------------------
    | Rate Limit By
    |--------------------------------------------------------------------------
    |
    | Specify how your application ban requests, either by 'ip', 'user_id', or 'email'
    |
    */
    'rate_limit_by' => 'ip', 

    /*
    |--------------------------------------------------------------------------
    | Limit per Request
    |--------------------------------------------------------------------------
    |
    | This is the default number of available attempts.
    |
    */
    'limit_per_request' => 5,

    /*
    |--------------------------------------------------------------------------
    | Decay Rate
    |--------------------------------------------------------------------------
    |
    | This is the default minutes until the available attempts are reset.
    |
    */
    'decay_rate' => 2400
];
