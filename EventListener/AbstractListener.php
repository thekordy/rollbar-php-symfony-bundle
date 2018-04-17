<?php

namespace Rollbar\Symfony\RollbarBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Monolog\Logger;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Rollbar\Symfony\RollbarBundle\DependencyInjection\SymfonyRollbarExtension;
use Rollbar\Symfony\RollbarBundle\Payload\Generator;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractListener
 * @package Rollbar\Symfony\RollbarBundle\EventListener
 */
abstract class AbstractListener implements EventSubscriberInterface
{
    /**
     * @var \Monolog\Logger
     */
    protected $logger;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \Rollbar\Symfony\RollbarBundle\Payload\Generator
     */
    protected $generator;

    public function __construct(
        ContainerInterface $container,
        LoggerInterface $logger,
        Generator $generator
    ) {
        /**
         * @var \Rollbar\Symfony\RollbarBundle\Provider\RollbarHandler $rbProvider
         */
        $this->container = $container;
        $this->logger = $logger;
        $this->generator = $generator;
    }

    /**
     * @return \Monolog\Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 1],
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // dummy
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return \Rollbar\Symfony\RollbarBundle\Payload\Generator
     */
    public function getGenerator()
    {
        return $this->generator;
    }
}
