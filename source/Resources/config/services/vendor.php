<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

use Doctrine\ORM\EntityRepository;
use Octopod\PodcastBundle\Command\CrawlEpisodesCommand;
use Octopod\PodcastBundle\Crawler\EventListener\DoctrineFlushEntitiesListener;
use Octopod\PodcastBundle\Crawler\EventListener\DoctrineMatchEpisodeListener;
use Octopod\PodcastBundle\Crawler\EventListener\DoctrineMatchPodcastListener;
use Octopod\PodcastBundle\Crawler\EventListener\DoctrinePersistEntitiesListener;
use Octopod\PodcastBundle\Crawler\EventListener\MapCrawlingFieldsListener;
use Octopod\PodcastBundle\Crawler\PodcastCrawler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

/**
 * Services for applications using the bundle for multiple podcasts
 */
return static function (ContainerConfigurator $container) {
    $container = $container->services()->defaults()
        ->private()
    ;

    $container->set(CrawlEpisodesCommand::class)
        ->args([
            'podcast:crawl:episodes',
            service(PodcastCrawler::class),
            service('messenger.default_bus')->ignoreOnInvalid(),
            null,
        ])
        ->tag('console.command')
    ;

    $container->set(DoctrineFlushEntitiesListener::class)
        ->args([
            service('doctrine.orm.entity_manager'),
        ])
        ->tag('kernel.event_listener', ['method' => 'onPostProcess'])
    ;

    $container->set(DoctrineMatchEpisodeListener::class)
        ->args([
            service('podcast.repository.episode'),
            param('podcast.classes.episode'),
            service('podcast.repository.podcast'),
        ])
        ->tag('kernel.event_listener', ['method' => 'onProcessEpisode', 'priority' => 4096])
    ;

    $container->set(DoctrineMatchPodcastListener::class)
        ->args([
            service('podcast.repository.podcast'),
            param('podcast.classes.podcast'),
        ])
        ->tag('kernel.event_listener', ['method' => 'onProcessPodcast', 'priority' => 4096])
    ;

    $container->set(MapCrawlingFieldsListener::class)
        ->tag('kernel.event_listener', ['method' => 'onProcessEpisode', 'priority' => 0])
        ->tag('kernel.event_listener', ['method' => 'onProcessPodcast', 'priority' => 0])
    ;

    $container->set(DoctrinePersistEntitiesListener::class)
        ->args([
            service('doctrine.orm.entity_manager'),
        ])
        ->tag('kernel.event_listener', ['method' => 'onProcessEpisode', 'priority' => -4096])
        ->tag('kernel.event_listener', ['method' => 'onProcessPodcast', 'priority' => -4096])
    ;

    $container->set('podcast.repository.episode', EntityRepository::class)
        ->factory([service('doctrine.orm.entity_manager'), 'getRepository'])
        ->args([
            param('podcast.classes.episode'),
        ])
    ;

    $container->set('podcast.repository.podcast', EntityRepository::class)
        ->factory([service('doctrine.orm.entity_manager'), 'getRepository'])
        ->args([
            param('podcast.classes.podcast'),
        ])
    ;
};
