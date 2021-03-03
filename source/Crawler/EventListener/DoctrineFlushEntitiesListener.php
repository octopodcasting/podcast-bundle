<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\EventListener;


use Doctrine\ORM\EntityManagerInterface;
use Octopod\PodcastBundle\Crawler\Event\PostProcessEvent;

class DoctrineFlushEntitiesListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onPostProcess(PostProcessEvent $event): void
    {
        $this->entityManager->flush();
    }
}
