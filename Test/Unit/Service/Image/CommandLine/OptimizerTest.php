<?php

namespace MageSuite\Frontend\Test\Unit\Service\Image\CommandLine;

class OptimizerTest extends \PHPUnit\Framework\TestCase
{
    public function testItUsesOptimizerLibraryCorrectly()
    {
        $path = __DIR__ . '/image.jpg';

        $optimizerMock = $this->getMockBuilder(\ImageOptimizer\Optimizer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $optimizerMock->expects($this->any())->method('optimize')->with($path);

        $optimizerFactoryStub = $this->getMockBuilder(\ImageOptimizer\OptimizerFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $optimizerFactoryStub->method('get')->willReturn($optimizerMock);

        $consoleOptimizer = new \MageSuite\Frontend\Service\Image\CommandLine\Optimizer($optimizerFactoryStub);

        $consoleOptimizer->optimize($path);
    }
}
