<?php

namespace Rollbar\Symfony\RollbarBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Psr\Log\LoggerInterface;

use Rollbar\Rollbar;
use Monolog\Logger;
use Rollbar\Symfony\RollbarBundle\Payload\Generator;
use Rollbar\Monolog\Handler\RollbarHandler as RollbarMonologHandler;

class ControllerListener
{
    private $container;
    private $logger;
    
    /**
     * ErrorListener constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        ContainerInterface $container,
        LoggerInterface $logger
    ) {
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * Process exception
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $this->logger->pushHandler(
            new RollbarMonologHandler(
                Rollbar::logger(),
                Logger::ERROR
            )
        );
    }
}
