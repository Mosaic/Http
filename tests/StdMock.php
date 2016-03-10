<?php

namespace Mosaic\Http\Tests;

class StdMock
{
    /**
     * @var \Mockery\Mock|null
     */
    public static $sapi;

    /**
     * @var bool
     */
    public static $headersSent = false;

    public static function setUp()
    {
        self::$sapi = \Mockery::mock(['header' => true, 'ob_get_level' => 1, 'ob_end_flush' => true, 'ob_start' => true]);
    }

    public static function tearDown()
    {
        self::$sapi        = null;
        self::$headersSent = false;
    }
}
