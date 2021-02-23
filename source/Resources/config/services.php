<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container) {
    $container = $container->services()->defaults()
        ->private()
    ;
};
