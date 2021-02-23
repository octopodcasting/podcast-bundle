<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PodcastBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $doctrineMappingPass = DoctrineOrmMappingsPass::createXmlMappingDriver([
            realpath(__DIR__ . '/Resources/config/doctrine') => 'Octopod\PodcastBundle\Entity',
        ]);

        $container->addCompilerPass($doctrineMappingPass);
    }
}
