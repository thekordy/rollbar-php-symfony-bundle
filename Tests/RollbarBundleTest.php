<?php

namespace Rollbar\Symfony\RollbarBundle\Tests;

use Rollbar\Symfony\RollbarBundle\EventListener\ErrorListener;
use Rollbar\Symfony\RollbarBundle\EventListener\ExceptionListener;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class RollbarBundleTest
 *
 * @package Rollbar\Symfony\RollbarBundle\Tests
 */
class RollbarBundleTest extends KernelTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        static::bootKernel();
    }

    /**
     * Test listeners.
     */
    public function testListeners()
    {
        $container = isset(static::$container) ? static::$container : static::$kernel->getContainer();
        $eventDispatcher = $container->get('event_dispatcher');
        $listeners = $eventDispatcher->getListeners('kernel.exception');
        $listeners = array_merge(
            $listeners,
            $eventDispatcher->getListeners('kernel.controller')
        );

        $expectedListeners = [
            ErrorListener::class,
            ExceptionListener::class,
        ];

        foreach ($listeners as $listener) {
            foreach ($expectedListeners as $key => $expectedListener) {
                if ($listener[0] instanceof $expectedListener) {
                    unset($expectedListeners[$key]);
                }
            }
        }

        $this->assertEmpty($expectedListeners, 'Listeners were not registered');
    }
}
