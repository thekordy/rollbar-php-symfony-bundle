<?php

namespace Rollbar\Symfony\RollbarBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Rollbar\Symfony\RollbarBundle\DependencyInjection\RollbarExtension;

/**
 * Class RollbarExtensionTest
 *
 * @package Rollbar\Symfony\RollbarBundle\Tests\DependencyInjection
 */
class RollbarExtensionTest extends AbstractExtensionTestCase
{
    /**
     * Get container extensions.
     *
     * @link: https://github.com/matthiasnoback/SymfonyDependencyInjectionTest
     *
     * @return array
     */
    protected function getContainerExtensions(): array
    {
        return [
            new RollbarExtension(),
        ];
    }

    /**
     * Test config enabled vars.
     *
     * @dataProvider generatorConfigVars
     *
     * @param string $var
     * @param array  $expected
     */
    public function testConfigEnabledVars(string $var, array $expected): void
    {
        $this->load();

        $param = $this->container->getParameter($var);

        foreach ($expected as $key => $value) {
            $this->assertEquals($param[$key], $value);
        }
    }

    /**
     * Data provider generatorConfigVars.
     *
     * @return array
     */
    public function generatorConfigVars(): array
    {
        return [
            ['rollbar.config', ['enabled' => true]],
        ];
    }

    /**
     * Test config disabled vars.
     *
     * @dataProvider generatorConfigVars
     *
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     *
     * @param string $var
     * @param array  $expected
     */
    public function testConfigDisabledVars(string $var, array $expected): void
    {
        $this->load(['enabled' => false]);

        $param = $this->container->getParameter($var);

        foreach ($expected as $key => $value) {
            $this->assertEquals($param[$key], $value);
        }
    }

    /**
     * Test alias.
     */
    public function testAlias(): void
    {
        $extension = new RollbarExtension();
        $this->assertEquals(RollbarExtension::ALIAS, $extension->getAlias());
    }
}
