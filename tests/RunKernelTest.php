<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Epaphrodites\epaphrodites\Kernel\runKernel;

class RunKernelTest extends TestCase
{
    public function testGetSomeValueReturnsExpectedValue()
    {
        require_once 'bin/epaphrodites/define/config/SetDirectory.php';

        $runKernel = new runKernel();

        runKernel::Run();

        $actualValue = $runKernel->getHome();

        $expectedValue = 'views/index/';

        $this->assertEquals($expectedValue, $actualValue);
    }
}

