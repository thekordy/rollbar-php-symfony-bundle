<?php
namespace Rollbar\Symfony\RollbarBundle\Factories;

use Rollbar\Rollbar;
use Rollbar\Monolog\Handler\RollbarHandler as RollbarMonologHandler;
use Rollbar\Symfony\RollbarBundle\DependencyInjection\RollbarExtension;

use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SymfonyRollbarBundle\DependencyInjection\SymfonyRollbarExtension;

class RollbarHandlerFactory
{
    
    private $config;
    
    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->getParameter(RollbarExtension::ALIAS . '.config');
        
        if (isset($_ENV['ROLLBAR_TEST_TOKEN']) && $_ENV['ROLLBAR_TEST_TOKEN']) {
            $this->config['access_token'] = $_ENV['ROLLBAR_TEST_TOKEN'];
        }
        
        if (empty($this->config['person'])) {
            try {
                if ($token = $container->get('security.token_storage')->getToken()) {
                    $this->config['person'] = $token->getUser();
                }
            } catch (\Exception $exception) {
            }
        }
        
        if (!empty($this->config['person_fn']) &&
            is_callable($this->config['person_fn']) ) {
            $this->config['person'] = null;
        }
        
        Rollbar::init($this->config, false, false, false);
    }
    
    public function createRollbarHandler()
    {
        return new RollbarMonologHandler(
            Rollbar::logger(),
            Logger::ERROR
        );
    }
}
