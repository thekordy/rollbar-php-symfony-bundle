<?php

namespace Rollbar\Symfony\RollbarBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Rollbar\Symfony\RollbarBundle\DependencyInjection\Configuration;
use Rollbar\Symfony\RollbarBundle\DependencyInjection\RollbarExtension;

/**
 * Class ConfigurationTest
 * @package Rollbar\Symfony\Tests\DependencyInjection
 */
class ConfigurationTest extends KernelTestCase
{
    public function testParameters()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();

        $config           = $container->getParameter(RollbarExtension::ALIAS . '.config');
        
        $defaults = [];
        foreach (\Rollbar\Config::listOptions() as $option) {
            // TODO: this is duplicated code from
            // https://github.com/rollbar/rollbar-php-wordpress/blob/master/src/Plugin.php#L359-L366
            // It needs to get replaced with a native rollbar/rollbar-php method
            // as pointed out here https://github.com/rollbar/rollbar-php/issues/344
            $method = lcfirst(str_replace('_', '', ucwords($option, '_')));
                    
            // Handle the "branch" exception
            switch ($method) {
                case "branch":
                    $method = "gitBranch";
                    break;
                case "includeErrorCodeContext":
                    $method = 'includeCodeContext';
                    break;
                case "includeExceptionCodeContext":
                    $method = 'includeExcCodeContext';
                    break;
            }
                    
            $default = method_exists(\Rollbar\Defaults::get(), $method) ?
                \Rollbar\Defaults::get()->$method() :
                null;
                    
            $defaults[$option] = $default;
        }
        
        
        $default = [
            'enable' => true,
            'config' => $defaults
        ];

        $this->assertNotEmpty($config);
        $this->assertEquals($default, $config);
    }
}
