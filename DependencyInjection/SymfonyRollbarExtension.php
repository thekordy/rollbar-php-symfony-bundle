<?php

namespace SymfonyRollbarBundle\DependencyInjection;

use Rollbar\Rollbar;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class Extension
 * @package SymfonyRollbarBundle\DependencyInjection
 */
class SymfonyRollbarExtension extends Extension
{
    const ALIAS = 'symfony_rollbar';

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        var_dump(get_called_class() . '::load'); die();
        
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        if (empty($config['enable'])) {
            return;
        }

        // load services and register listeners
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        // store parameters for external use
        $container->setParameter(static::ALIAS . '.config', $config);
        
        // initialize Rollbar
        if (isset($_ENV['ROLLBAR_TEST_TOKEN']) && $_ENV['ROLLBAR_TEST_TOKEN']) {
            $config['rollbar']['access_token'] = $_ENV['ROLLBAR_TEST_TOKEN'];
        }
        
        if (!isset($config['person']) || (isset($config['person']) && !$config['person'])) {
            $config['person'] = $this->getContainer()
                ->get('security.token_storage')
                ->getToken()
                ->getUser();
        }
        
        Rollbar::init($config['rollbar'], false, false, false);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return static::ALIAS;
    }
}
