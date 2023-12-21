<?php

namespace Eazybright\SuperBan\Tests;

use Eazybright\SuperBan\Http\Middleware\SuperBanMiddleware;
use Eazybright\SuperBan\SuperBan;
use Eazybright\SuperBan\SuperBanServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $superBan;

    protected $superBanMiddleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superBan = app(SuperBan::class);
        $this->superBanMiddleware = new SuperBanMiddleware($this->superBan);
    }

    protected function getPackageProviders($app)
    {
        return [
            SuperBanServiceProvider::class,
        ];
    }
}
