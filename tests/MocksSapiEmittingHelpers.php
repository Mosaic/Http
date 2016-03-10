<?php

namespace Mosaic\Http\Tests {

    trait MocksSapiEmittingHelpers
    {
        /**
         * @before
         */
        protected function initializeStdMocks()
        {
            StdMock::setUp();
        }

        /**
         * @after
         */
        protected function cleanUpStdMocks()
        {
            StdMock::tearDown();
        }
    }
}

namespace Mosaic\Http\Emitters {

    use Mosaic\Http\Tests\StdMock;

    function headers_sent()
    {
        return StdMock::$headersSent;
    }

    function header($string, $replace = true)
    {
        return StdMock::$sapi ? StdMock::$sapi->header($string, $replace) : \header($string, $replace);
    }

    function ob_get_level()
    {
        return StdMock::$sapi ? StdMock::$sapi->ob_get_level() : \ob_get_level();
    }

    function ob_end_flush()
    {
        return StdMock::$sapi ? StdMock::$sapi->ob_end_flush() : \ob_end_flush();
    }

    function ob_start()
    {
        return StdMock::$sapi ? StdMock::$sapi->ob_start() : \ob_start();
    }
}
