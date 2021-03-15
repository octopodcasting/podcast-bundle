<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

use Octopod\PodcastBundle\Controller\PodcastController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('podcast_list', '')
        ->controller([PodcastController::class, 'list'])
    ;

    $routes->add('podcast_archive', '/archive')
        ->controller([PodcastController::class, 'archive'])
    ;

    $routes->add('podcast_episode', '/episode/{episode}')
        ->controller([PodcastController::class, 'episode'])
    ;
};
