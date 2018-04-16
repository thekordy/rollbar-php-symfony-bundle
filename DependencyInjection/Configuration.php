<?php

namespace Rollbar\Symfony\RollbarBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @link https://rollbar.com/docs/notifier/rollbar-php/#configuration-reference
 * @package Rollbar\Symfony\RollbarBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritdoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder      = new TreeBuilder();
        $rootNode         = $treeBuilder->root(RollbarExtension::ALIAS);

        // the intendation in this method reflects the structure of the rootNode
        // for convenience

        $rootNode->children()
            ->scalarNode('enable')->defaultTrue()->end();
                
            $rollbarConfigNode = $rootNode->children()
                ->arrayNode('config');
            
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
                    
            $rollbarConfigNode->children()
            ->scalarNode($option)->defaultValue($default)->end();
        }
                
            $rollbarConfigNode->end();
        
        $rootNode->end();

        return $treeBuilder;
    }
}
