<?php

namespace Rollbar\Symfony\RollbarBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Psr\Log\LoggerInterface;
use Rollbar\Symfony\RollbarBundle\Payload\Generator;

class ExceptionListener
{
    private $container;
    private $logger;
    private $generator;
    
    /**
     * ErrorListener constructor.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Rollbar\Symfony\RollbarBundle\Payload\Generator $generator
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
     * Process exception
     *
     * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof \Exception
            || (version_compare(PHP_VERSION, '7.0.0') >= 0 && $exception instanceof \Error)
        ) {
            $this->handleException($exception);
        }
    }

    /**
     * Handle provided exception
     *
     * @param $exception
     */
    public function handleException($exception)
    {
        // generate payload and log data
        list($message, $payload) = $this->generator->getExceptionPayload($exception);
        
        $this->logger->error($message, [
            'payload' => $payload,
        ]);
    }
}
