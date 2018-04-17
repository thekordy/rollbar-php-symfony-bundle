<?php

namespace Rollbar\Symfony\RollbarBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Rollbar\Rollbar;
use Rollbar\Monolog\Handler\RollbarHandler;
use Monolog\Logger;
use Psr\Logger\LoggerInterface;

/**
 * Class Extension
 * @package Rollbar\Symfony\RollbarBundle\DependencyInjection
 */
class RollbarExtension extends Extension
{
    const ALIAS = 'rollbar';

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
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        // load services and register listeners
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        // store parameters for external use
        $container->setParameter(static::ALIAS . '.config', $config);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return static::ALIAS;
    }
}
