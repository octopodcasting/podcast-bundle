<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

use Doctrine\ORM\EntityRepository;
use Octopod\PodcastBundle\Command\CrawlEpisodesCommand;
use Octopod\PodcastBundle\Controller\PodcastController;
use Octopod\PodcastBundle\Crawler\EventListener\DoctrineFlushEntitiesListener;
use Octopod\PodcastBundle\Crawler\EventListener\DoctrineMatchEpisodeListener;
use Octopod\PodcastBundle\Crawler\EventListener\DoctrinePersistEntitiesListener;
use Octopod\PodcastBundle\Crawler\EventListener\MapCrawlingFieldsListener;
use Octopod\PodcastBundle\Crawler\FeedCrawler;
use Octopod\PodcastBundle\Crawler\PodcastindexCrawler;
use Octopod\PodcastBundle\Provider\EpisodeProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container = $container->services()->defaults()
        ->private()
    ;

    $container->set(CrawlEpisodesCommand::class)
        ->args([
            'podcast:crawl:episodes',
            service('podcast.crawler'),
            param('podcast.feed'),
        ])
        ->tag('console.command')
    ;

    $container->set(PodcastController::class)
        ->public()
        ->args([
            service(EpisodeProvider::class),
            service('podcast'),
        ])
        ->call('setContainer', [service('service_container')])
        ->tag('controller.service_arguments')
    ;

    $container->set(EpisodeProvider::class)
        ->args([
            service('podcast.repository.episode'),
        ])
    ;

    $container->set(FeedCrawler::class)
        ->args([
            service('http_client'),
            service('event_dispatcher'),
        ])
        ->tag('podcast.crawler')
    ;

    $container->set(PodcastindexCrawler::class)
        ->args([
            service('event_dispatcher'),
        ])
        ->tag('podcast.crawler')
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
        ])
        ->tag('kernel.event_listener', ['method' => 'onProcessEpisode', 'priority' => 4096])
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

    $container->set('podcast')
        ->synthetic()
    ;

    $container->set('podcast.crawler')
        ->synthetic()
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

    $container->alias('podcast.crawler.feed', FeedCrawler::class);
    $container->alias('podcast.crawler.podcastindex', PodcastindexCrawler::class);
};
