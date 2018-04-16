<?php
namespace SymfonyRollbarBundle\Provider;

use Rollbar\Rollbar;
use Rollbar\Monolog\Handler\RollbarHandler as RollbarMonologHandler;

use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SymfonyRollbarBundle\DependencyInjection\SymfonyRollbarExtension;

class RollbarHandler
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return \Rollbar\Monolog\Handler\RollbarHandler
     */
    public function getHandler()
    {
        $config = $this->getContainer()->getParameter(SymfonyRollbarExtension::ALIAS . '.config');
        
        if (empty($config['enable'])) {
            return;
        }
        
        // if (isset($_ENV['ROLLBAR_TEST_TOKEN']) && $_ENV['ROLLBAR_TEST_TOKEN']) {
        //     $config['rollbar']['access_token'] = $_ENV['ROLLBAR_TEST_TOKEN'];
        // }
        
        // if (!isset($config['person']) || (isset($config['person']) && !$config['person'])) {
        //     $config['person'] = $this->getContainer()
        //         ->get('security.token_storage')
        //         ->getToken()
        //         ->getUser();
        // }
        
        Rollbar::init($config['rollbar'], false, false, false);
        
        // $rollbarLogger = \Rollbar\Rollbar::scope($config['rollbar']);
        
        // Rollbar::configure($config['rollbar']);
        
        $handler = new RollbarMonologHandler(
            Rollbar::logger(),
            Logger::ERROR
        );

        return $handler;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
