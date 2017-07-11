<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use Pallares\Laravel\StorageSafe\Application;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function test_can_make_application()
    {
        $this->assertInstanceOf(Application::class, new Application(__DIR__));
    }
}
