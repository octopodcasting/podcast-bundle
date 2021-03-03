<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\DependencyInjection;

use Octopod\PodcastBundle\Entity\Episode;
use Octopod\PodcastBundle\Entity\Podcast;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('podcast');

        $treeBuilder->getRootNode()
            ->children()
                ->booleanNode('enabled')
                    ->defaultNull()
                ->end()
                ->scalarNode('feed')
                    ->defaultNull()
                ->end()
                ->arrayNode('crawler')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('service')
                            ->defaultValue('feed')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('classes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('podcast')->defaultValue(Podcast::class)->end()
                        ->scalarNode('episode')->defaultValue(Episode::class)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
