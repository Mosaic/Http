<?php

namespace Mosaic\Http\Tests {

    trait MocksWebServerHelpers
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

namespace Mosaic\Http {

    use Mosaic\Http\Tests\StdMock;

    function ob_end_flush()
    {
        return StdMock::$sapi ? StdMock::$sapi->ob_end_flush() : \ob_end_flush();
    }

    function ob_start()
    {
        return StdMock::$sapi ? StdMock::$sapi->ob_start() : \ob_start();
    }
}
