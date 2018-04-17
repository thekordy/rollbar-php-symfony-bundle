<?php
namespace Rollbar\Symfony\RollbarBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

use Rollbar\Symfony\RollbarBundle\DependencyInjection\RollbarExtension;
use Rollbar\Symfony\RollbarBundle\EventListener\ErrorListener;
use Rollbar\Symfony\RollbarBundle\EventListener\ExceptionListener;
use Rollbar\Symfony\RollbarBundle\Payload\Generator;
use Rollbar\Symfony\RollbarBundle\Provider\RollbarHandler;

/**
 * Class RollbarExtensionTest
 * @package Rollbar\Symfony\RollbarBundle\Tests\DependencyInjection
 */
class RollbarExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @link: https://github.com/matthiasnoback/SymfonyDependencyInjectionTest
     * @return array
     */
    protected function getContainerExtensions()
    {
        return [
            new RollbarExtension(),
        ];
    }

    /**
     * @dataProvider generatorConfigVars
     *
     * @param string $var
     * @param mixed  $value
     */
    public function testConfigEnabledVars($var, $value)
    {
        $this->load();

        $this->assertContainerBuilderHasParameter($var, $value);
    }

    public function generatorConfigVars()
    {
        return [
            ['rollbar.config', ['enable' => true]],
        ];
    }

    /**
     * @dataProvider generatorConfigVars
     *
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     *
     * @param string $var
     * @param mixed  $value
     */
    public function testConfigDisabledVars($var, $value)
    {
        $this->load(['enable' => false]);

        $this->assertContainerBuilderHasParameter($var, $value);
    }

    public function testAlias()
    {
        $extension = new RollbarExtension();
        $this->assertEquals(RollbarExtension::ALIAS, $extension->getAlias());
    }
}
