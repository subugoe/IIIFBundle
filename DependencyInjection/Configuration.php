<?php

namespace Subugoe\IIIFBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('subugoe_iiif');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('presentation')
                    ->children()
                        ->scalarNode('logo')->end()
                        ->scalarNode('service_id')->end()
                        ->arrayNode('http')
                            ->children()
                                ->scalarNode('scheme')->defaultValue('https,')->end()
                                ->scalarNode('host')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('image')
                    ->children()
                        ->integerNode('tile_width')->defaultValue(512)->end()
                        ->integerNode('tile_height')->defaultValue(512)->end()
                        ->scalarNode('thumbnail_size')->defaultValue('92,')->end()
                        ->scalarNode('scheme')->end()
                        ->booleanNode('originals_caching')->defaultFalse()->end()
                        ->arrayNode('zoom_levels')
                            ->prototype('scalar')->end()
                            ->defaultValue([1, 2, 4, 8, 16])
                        ->end()
                        ->arrayNode('http')
                            ->children()
                                ->scalarNode('scheme')->defaultValue('https,')->end()
                                ->scalarNode('host')->end()
                            ->end()
                        ->end()
                        ->arrayNode('adapters')
                            ->children()
                                ->arrayNode('source')
                                    ->children()
                                        ->scalarNode('class')->end()
                                        ->variableNode('configuration')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
