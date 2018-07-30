<?php

namespace Rollbar\Symfony\RollbarBundle\Tests\EventListener;

use Monolog\Logger;
use Rollbar\Symfony\RollbarBundle\EventListener\AbstractListener;
use Rollbar\Symfony\RollbarBundle\EventListener\ErrorListener;
use Rollbar\Symfony\RollbarBundle\Payload\Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Tests\Fixtures\ErrorHandler;

/**
 * Class ErrorListenerTest
 *
 * @package Rollbar\Symfony\RollbarBundle\Tests\EventListener
 */
class ErrorListenerTest extends KernelTestCase
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
     * Test user error.
     */
    public function testUserError()
    {
        $message = 'Fatal error - ' . time();

        $container = $this->getContainer();
        $eventDispatcher = $container->get('event_dispatcher');
        $listeners = $eventDispatcher->getListeners('kernel.exception');
        $handler = ErrorHandler::getInstance();

        $handler->setAssert(function (array $record) use ($message) {
            $this->assertNotEmpty($record);

            $this->assertEquals($message, $record['message']);
            $this->assertEquals(Logger::ERROR, $record['level']);
            $this->assertTrue(!empty($record['context']['payload']['body']['trace']['exception']['class']));

            $trace = $record['context']['payload']['body']['trace'];
            $this->assertEquals('E_USER_ERROR', $trace['exception']['class']);
            $this->assertNotEmpty($trace['frames']);
        });

        foreach ($listeners as $listener) {
            if (!$listener[0] instanceof AbstractListener) {
                continue;
            }

            $listener[0]->getLogger()->setHandlers([$handler]);
        }

        trigger_error($message, E_USER_ERROR);

        $handler->setAssert(null);
    }

    /**
     * Test fatal error parser.
     *
     * @dataProvider generateFatalError
     *
     * @param array $error
     * @param bool  $called
     */
    public function testFatalErrorParser($error, $called)
    {
        $mock = $this->getMockBuilder(ErrorListener::class)
            ->setMethods(['getLastError', 'handleError'])
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('getLastError')
            ->willReturn($error);

        $mock->expects($called ? $this->once() : $this->never())
            ->method('handleError')
            ->with(
                $this->equalTo($error['type']),
                $this->stringContains($error['message']),
                $this->stringContains($error['file']),
                $this->equalTo($error['line'])
            );

        /** @var ErrorListener $mock */
        $mock->handleFatalError();
    }

    /**
     * Data provider generate fatal error.
     *
     * @return array
     */
    public function generateFatalError()
    {
        return [
            [['type' => E_ERROR, 'message' => 'Error message', 'file' => __DIR__, 'line' => rand(10, 100)], true],
            [null, false]
        ];
    }

    /**
     * Test isReportable.
     *
     * @dataProvider generateIsReportable
     *
     * @param bool $called
     */
    public function testIsReportable($called)
    {
        $container = $this->getContainer();
        $generator = $container->get('test.' . Generator::class);

        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['error'])
            ->setConstructorArgs(['test-alias'])
            ->getMock();

        $logger->method('error')
            ->willReturn(true);

        $mock = $this->getMockBuilder(ErrorListener::class)
            ->setMethods(['isReportable', 'getGenerator', 'getLogger'])
            ->disableOriginalConstructor()
            ->getMock();

        $mock->method('isReportable')
            ->willReturn($called);

        $mock->method('getGenerator')
            ->willReturn($generator);

        $mock->method('getLogger')
            ->willReturn($logger);

        /** @var ErrorListener $mock */
        $mock->handleError(E_ERROR, 'Message', __FILE__, rand(1, 10));
    }

    /**
     * Data provider for testIsReportable.
     *
     * @return array
     */
    public function generateIsReportable()
    {
        return [
            [true],
            [false]
        ];
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
