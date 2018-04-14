<?php

namespace Tests\SymfonyRollbarBundle\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use SymfonyRollbarBundle\DependencyInjection\Configuration;
use SymfonyRollbarBundle\DependencyInjection\SymfonyRollbarExtension;

/**
 * Class ConfigurationTest
 * @package Tests\SymfonyRollbarBundle\DependencyInjection
 */
class ConfigurationTest extends KernelTestCase
{
    public function testParameters()
    {
        static::bootKernel();
        $container = static::$kernel->getContainer();

        $config           = $container->getParameter(SymfonyRollbarExtension::ALIAS . '.config');
        
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
            'rollbar' => $defaults
        ];

        $this->assertNotEmpty($config);
        $this->assertEquals($default, $config);
    }
}
