<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function tearDownAfterClass() : void
    {
        $path = base_path('bootstrap/cache/config.php');
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
