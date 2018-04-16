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
    
    public function createRollbarHandler(ContainerInterface $container)
    {
        $this->config = $container->getParameter(RollbarExtension::ALIAS . '.config');
        
        if (isset($_ENV['ROLLBAR_TEST_TOKEN']) && $_ENV['ROLLBAR_TEST_TOKEN']) {
            $this->config['config']['access_token'] = $_ENV['ROLLBAR_TEST_TOKEN'];
        }
        
        if (!isset($config['person']) || (isset($config['person']) && !$config['person'])) {
            $config['person'] = $container->get('security.token_storage')
                ->getToken()
                ->getUser();
        }
        
        if (!empty($this->config['enable'])) {
            Rollbar::init($this->config['config'], false, false, false);
            
            return new RollbarMonologHandler(
                Rollbar::logger(),
                Logger::ERROR
            );
        }
        
        return null;
    }

}
