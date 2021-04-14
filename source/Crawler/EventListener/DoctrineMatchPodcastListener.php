<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\EventListener;

use Doctrine\ORM\EntityRepository;
use Octopod\PodcastBundle\Crawler\Event\ProcessPodcastEvent;

class DoctrineMatchPodcastListener
{
    private $podcastRepository;
    private $podcastClassName;

    public function __construct(EntityRepository $podcastRepository, string $podcastClassName)
    {
        $this->podcastRepository = $podcastRepository;
        $this->podcastClassName = $podcastClassName;
    }

    public function onProcessPodcast(ProcessPodcastEvent $event): void
    {
        if (null !== $event->getTargetPodcast()) {
            return;
        }

        $feed = $event->getPodcast()->getFeed();

        if (null === $podcast = $this->podcastRepository->findOneBy(['feed' => $feed])) {
            $podcast = new $this->podcastClassName();

            $podcast->setFeed($feed);
        }

        $event->setTargetPodcast($podcast);
    }
}
