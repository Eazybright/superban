<?php

namespace Eazybright\SuperBan\Tests\Features;

use Eazybright\SuperBan\Tests\TestCase;
use Illuminate\Http\Request;

class SuperBanMiddlewareTest extends TestCase
{
    public function test_block_and_ban_user_with_too_many_attempts(): void
    {
        config([
            'superban.rate_limit_by' => 'ip',
        ]);

        $ip = '127.0.0.1';

        $response = '';

        $request = Request::create('/test', 'GET');
        $request->server->set('REMOTE_ADDR', $ip);
        for ($i = 0; $i < 3; $i++) {

            $response = $this->superBanMiddleware->handle($request, function () {
                return response('OK', 200);
            }, 2, 2, 120);
        }

        $this->assertEquals(429, $response->getStatusCode());

        sleep(60);

        $request = Request::create('/test', 'GET');
        $request->server->set('REMOTE_ADDR', $ip);

        $response = $this->superBanMiddleware->handle($request, function () {
            return response('OK', 200);
        }, 200, 1, 5);

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function test_non_banned_clients_can_access(): void
    {
        config([
            'superban.rate_limit_by' => 'ip',
        ]);

        $ip = '123.123.123.123';

        $response = '';

        $request = Request::create('/test', 'GET');
        $request->server->set('REMOTE_ADDR', $ip);
        for ($i = 0; $i < 40; $i++) {

            $response = $this->superBanMiddleware->handle($request, function () {
                return response('OK', 200);
            });
        }

        $this->assertEquals(200, $response->getStatusCode());
    }
}
