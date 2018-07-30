<?php

namespace Rollbar\Symfony\RollbarBundle\EventListener;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Rollbar\Symfony\RollbarBundle\Payload\Generator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class AbstractListener
 *
 * @package Rollbar\Symfony\RollbarBundle\EventListener
 */
abstract class AbstractListener implements EventSubscriberInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Generator
     */
    protected $generator;

    /**
     * AbstractListener constructor.
     *
     * @param ContainerInterface $container
     * @param LoggerInterface    $logger
     * @param Generator          $generator
     */
    public function __construct(
        ContainerInterface $container,
        LoggerInterface $logger,
        Generator $generator
    ) {
        $this->container = $container;
        $this->logger = $logger;
        $this->generator = $generator;
    }

    /**
     * Get logger.
     *
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 1],
        ];
    }

    /**
     * On kernel exception event handler.
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // dummy
    }

    /**
     * Get container.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get generator.
     *
     * @return Generator
     */
    public function getGenerator()
    {
        return $this->generator;
    }
}
