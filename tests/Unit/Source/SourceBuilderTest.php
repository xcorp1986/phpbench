<?php

namespace PhpBench\Tests\Unit\Source;

use PhpBench\Source\SourceBuilder;
use PhpBench\Source\CodeElementInterface;
use Prophecy\Argument;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Prophecy\Prophecy\ObjectProphecy;

class SourceBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $builder;

    public function setUp()
    {
        $this->builder = new SourceBuilder();

        $this->element1 = $this->prophesize(CodeElementInterface::class);
        $this->element2 = $this->prophesize(CodeElementInterface::class);
        $this->element3 = $this->prophesize(CodeElementInterface::class);
        $this->element4 = $this->prophesize(CodeElementInterface::class);
    }

    /**
     * It should compose source from a set of code elements.
     */
    public function testBuild()
    {
        $options = [];

        $this->configureElement($this->element1, [
            'left' => [
                'el1 left line 1',
                'el1 left line 2',
            ],
            'right' => [
                'el1 right line 1',
                'el1 right line 2',
            ],
        ]);
        $this->configureElement($this->element2, [
            'left' => [
                'el2 left line 1',
                'el2 left line 2',
            ],
            'right' => [
                'el2 right line 1',
                'el2 right line 2',
            ],
        ]);

        $this->builder->add($this->element1->reveal());
        $this->builder->add($this->element2->reveal());

        $source = $this->builder->build($options);
        $this->assertEquals(<<<EOT
el1 left line 1
el1 left line 2
el2 left line 1
el2 left line 2
el2 right line 1
el2 right line 2
el1 right line 1
el1 right line 2
EOT
        , $source);
    }

    private function configureElement(ObjectProphecy $element, array $options)
    {
        $options = array_merge([
            'left' => [],
            'right' => [],
            'expectedOptions' => [],
        ], $options);

        $element->getLeftLines($options['expectedOptions'])->willReturn($options['left']);
        $element->getRightLines($options['expectedOptions'])->willReturn($options['right']);
        $element->configureOptions(Argument::type(OptionsResolver::class))->shouldBeCalled();
    }
}
