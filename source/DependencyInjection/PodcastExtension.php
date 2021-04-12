<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\DependencyInjection;

use Octopod\PodcastBundle\Command\CrawlEpisodesCommand;
use Octopod\PodcastBundle\Entity\Episode;
use Octopod\PodcastBundle\Entity\Podcast;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class PodcastExtension extends Extension
{
    private $messengerBusReference = false;

    public function load(array $configurations, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config/services'));
        $loader->load('crawler.php');

        $configuration = $this->processConfiguration($this->getConfiguration($configurations, $container), $configurations);

        $this->registerCrawlerConfiguration($configuration, $container);

        if ($configuration['vendor']) {
            $this->registerVendorConfiguration($configuration, $container, $loader);
        } else {
            if (null === $configuration['enabled']) {
                $configuration['enabled'] = null !== $configuration['feed'];
            }

            if ($configuration['enabled']) {
                $this->registerPodcastConfiguration($configuration, $container, $loader);
            }
        }
    }

    private function registerCrawlerConfiguration(array $configuration, ContainerBuilder $container): void
    {
        $defaultAdapterAlias = 'podcast.crawler.adapter.' . $configuration['crawler']['adapter'];
        $defaultAdapterService = $container->has($defaultAdapterAlias) ? $defaultAdapterAlias : $configuration['crawler']['adapter'];
        $container->setAlias('podcast.crawler.adapter', $defaultAdapterService);

        if (null !== $configuration['crawler']['messenger_bus']) {
            if (false === $configuration['crawler']['messenger_bus']) {
                $this->messengerBusReference = null;
            } else {
                $this->messengerBusReference = new Reference(sprintf('messenger.%s_bus', $configuration['crawler']['messenger_bus']));
            }
        }
    }

    private function registerPodcastConfiguration(array $configuration, ContainerBuilder $container, PhpFileLoader $loader): void
    {
        if (null === $configuration['classes']['episode']) {
            throw new LogicException(sprintf('To use the podcast bundle, create an entity extending "%s" and set it as "podcast.classes.episode" configuration option.', Episode::class));
        }

        $loader->load('podcast.php');

        if (false !== $this->messengerBusReference) {
            $container->getDefinition(CrawlEpisodesCommand::class)->replaceArgument(2, $this->messengerBusReference);
        }

        $podcastDefinition = new Definition(Podcast::class);
        $podcastDefinition->addMethodCall('setTitle', [$configuration['podcast']['title']]);
        $podcastDefinition->addMethodCall('setDescription', [$configuration['podcast']['description']]);
        $podcastDefinition->addMethodCall('setAuthor', [$configuration['podcast']['author']]);
        $podcastDefinition->addMethodCall('setImage', [$configuration['podcast']['image']]);

        $container->setDefinition('podcast', $podcastDefinition);

        $container->setParameter('podcast.feed', $configuration['feed']);
        $container->setParameter('podcast.classes.episode', $configuration['classes']['episode']);
    }

    private function registerVendorConfiguration(array $configuration, ContainerBuilder $container, PhpFileLoader $loader): void
    {
        if (null === $configuration['classes']['episode'] || null === $configuration['classes']['podcast']) {
            throw new LogicException('To use the podcast bundle in vendor mode, you need to set the "podcast.classes.episode" and "podcast.classes.podcast" configuration options.');
        }

        $loader->load('vendor.php');

        if (false !== $this->messengerBusReference) {
            $container->getDefinition(CrawlEpisodesCommand::class)->replaceArgument(2, $this->messengerBusReference);
        }

        $container->setParameter('podcast.classes.episode', $configuration['classes']['episode']);
        $container->setParameter('podcast.classes.podcast', $configuration['classes']['podcast']);
    }
}
