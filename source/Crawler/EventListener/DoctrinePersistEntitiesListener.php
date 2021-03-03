<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Octopod\PodcastBundle\Crawler\Event\ProcessEpisodeEvent;
use Octopod\PodcastBundle\Crawler\Event\ProcessPodcastEvent;

class DoctrinePersistEntitiesListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onProcessPodcast(ProcessPodcastEvent $event): void
    {
        if (null !== $podcast = $event->getTargetPodcast()) {
            $this->entityManager->persist($podcast);
        }
    }

    public function onProcessEpisode(ProcessEpisodeEvent $event): void
    {
        if (null !== $episode = $event->getTargetEpisode()) {
            $this->entityManager->persist($episode);
        }
    }
}
