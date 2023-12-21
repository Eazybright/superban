<?php

namespace Eazybright\SuperBan\Tests;

use Eazybright\SuperBan\Http\Middleware\SuperBanMiddleware;
use Eazybright\SuperBan\SuperBan;
use Orchestra\Testbench\TestCase as Orchestra;
use Eazybright\SuperBan\SuperBanServiceProvider;

class TestCase extends Orchestra
{

    protected $superBan;

    protected $superBanMiddleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superBan = app(Superban::class);
        $this->superBanMiddleware = new SuperBanMiddleware($this->superBan);
    }

    protected function getPackageProviders($app)
    {
        return [
            SuperBanServiceProvider::class,
        ];
    }
}
