<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

use Octopod\PodcastBundle\Crawler\FeedCrawler;
use Octopod\PodcastBundle\Crawler\PodcastindexCrawler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container = $container->services()->defaults()
        ->private()
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

    $container->set('podcast.crawler')
        ->synthetic()
    ;

    $container->alias('podcast.crawler.feed', FeedCrawler::class);
    $container->alias('podcast.crawler.podcastindex', PodcastindexCrawler::class);
};
