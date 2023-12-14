<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Epaphrodite\epaphrodite\Kernel\runKernel;

class RunKernelTest extends TestCase
{
    public function testGetSomeValueReturnsExpectedValue()
    {
        require_once 'bin/epaphrodite/define/config/SetDirectory.php';

        $runKernel = new runKernel();

        runKernel::Run();

        $actualValue = $runKernel->getHome();

        $expectedValue = 'views/index/';

        $this->assertEquals($expectedValue, $actualValue);
    }
}

