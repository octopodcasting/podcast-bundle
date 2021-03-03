<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\EventListener;

use Doctrine\ORM\EntityRepository;
use Octopod\PodcastBundle\Crawler\Event\ProcessEpisodeEvent;

class DoctrineMatchEpisodeListener
{
    private $episodeRepository;
    private $episodeClassName;

    public function __construct(EntityRepository $episodeRepository, string $episodeClassName)
    {
        $this->episodeRepository = $episodeRepository;
        $this->episodeClassName = $episodeClassName;
    }

    public function onProcessEpisode(ProcessEpisodeEvent $event): void
    {
        if (null !== $event->getTargetEpisode()) {
            return;
        }

        $guid = $event->getEpisode()->getGuid();
        if (null === $episode = $this->episodeRepository->findOneBy(['guid' => $guid])) {
            $episode = new $this->episodeClassName();

            $episode->setGuid($guid);
        }

        $event->setTargetEpisode($episode);
    }
}
