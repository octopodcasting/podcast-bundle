<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

use Doctrine\ORM\EntityRepository;
use Octopod\PodcastBundle\Command\CrawlEpisodesCommand;
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
