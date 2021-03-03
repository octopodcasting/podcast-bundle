<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\DependencyInjection;

use Octopod\PodcastBundle\Command\CrawlEpisodesCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class PodcastExtension extends Extension
{
    public function load(array $configurations, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.php');

        $configuration = $this->processConfiguration($this->getConfiguration($configurations, $container), $configurations);

        $defaultCrawlerAlias = 'podcast.crawler.' . $configuration['crawler']['service'];
        $defaultCrawlerService = $container->has($defaultCrawlerAlias) ? $defaultCrawlerAlias : $configuration['crawler']['service'];
        $container->setAlias('podcast.crawler', $defaultCrawlerService);

        if (null === $configuration['enabled']) {
            $configuration['enabled'] = null !== $configuration['feed'];
        }

        if (false === $configuration['enabled']) {
            $container->removeDefinition(CrawlEpisodesCommand::class);

            return;
        }

        $container->setParameter('podcast.feed', $configuration['feed']);
        $container->setParameter('podcast.classes.episode', $configuration['classes']['episode']);
        $container->setParameter('podcast.classes.podcast', $configuration['classes']['podcast']);
    }
}
