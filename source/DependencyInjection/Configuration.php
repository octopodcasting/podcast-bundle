<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('podcast');

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('enabled')->defaultNull()->end()
                ->booleanNode('vendor')->defaultFalse()->end()
                ->scalarNode('feed')->defaultNull()->end()
                ->arrayNode('podcast')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('title')->defaultValue('My Podcast')->end()
                        ->scalarNode('description')->defaultValue('Some text about My Podcast')->end()
                        ->scalarNode('author')->defaultValue('My Name')->end()
                        ->scalarNode('image')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('crawler')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('adapter')->defaultValue('feed')->end()
                        ->scalarNode('messenger_bus')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('classes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('podcast')->defaultNull()->end()
                        ->scalarNode('episode')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
