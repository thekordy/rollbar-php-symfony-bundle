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
            $this->config['config']['access_token'] = $_ENV['ROLLBAR_TEST_TOKEN'];
        }
        
        if (empty($this->config['config']['person'])) {
            try {
                if ($token = $container->get('security.token_storage')->getToken()) {
                    $this->config['config']['person'] = $token->getUser();
                }
            } catch (\Exception $exception) {
            }
        }
        
        if (!empty($this->config['config']['person_fn']) &&
            is_callable($this->config['config']['person_fn']) ) {
            $this->config['config']['person'] = null;
        }
        
        if (!empty($this->config['enable'])) {
            Rollbar::init($this->config['config'], false, false, false);
        }
    }
    
    public function createRollbarHandler()
    {
        if (!empty($this->config['enable'])) {
            return new RollbarMonologHandler(
                Rollbar::logger(),
                Logger::ERROR
            );
        }
        
        return null;
    }
}
