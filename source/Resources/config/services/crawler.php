<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

use Octopod\PodcastBundle\Crawler\Adapter\LaminasFeedAdapter;
use Octopod\PodcastBundle\Crawler\Adapter\PodcastindexAdapter;
use Octopod\PodcastBundle\Crawler\PodcastCrawler;
use Octopod\PodcastBundle\Message\CrawlingMessageInterface;
use Octopod\PodcastBundle\Messenger\CrawlingMessageHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container = $container->services()->defaults()
        ->private()
    ;

    $container->set(PodcastCrawler::class)
        ->args([
            service('podcast.crawler.adapter'),
            service('event_dispatcher'),
        ])
    ;

    $container->set(LaminasFeedAdapter::class)
        ->args([
            service('http_client'),
        ])
    ;

    $container->set(PodcastindexAdapter::class)
        ->args([
            service('http_client'),
        ])
    ;

    $container->set(CrawlingMessageHandler::class)
        ->args([
            service(PodcastCrawler::class),
        ])
        ->tag('messenger.message_handler', ['handles' => CrawlingMessageInterface::class])
    ;

    $container->set('podcast.crawler.adapter')
        ->synthetic()
    ;

    $container->alias('podcast.crawler.adapter.feed', LaminasFeedAdapter::class);
    $container->alias('podcast.crawler.adapter.podcastindex', PodcastindexAdapter::class);
};
