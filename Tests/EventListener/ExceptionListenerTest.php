<?php

namespace Tests\SymfonyRollbarBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class ExceptionListenerTest
 *
 * @package Tests\SymfonyRollbarBundle\EventListener
 */
class ExceptionListenerTest extends KernelTestCase
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
     * Test handleException.
     */
    public function testException()
    {
        $container = $this->getContainer();
        $eventDispatcher = $container->get('event_dispatcher');
        $exception       = new \Exception('This is new exception');
        $event           = new GetResponseForExceptionEvent(
            static::$kernel,
            new Request(),
            HttpKernelInterface::MASTER_REQUEST,
            $exception
        );

        $eventDispatcher->dispatch('kernel.exception', $event);
    }

    /**
     * Get container.
     *
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return isset(static::$container) ? static::$container : static::$kernel->getContainer();
    }
}
