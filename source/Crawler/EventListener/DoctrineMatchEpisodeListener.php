<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\EventListener;

use Doctrine\ORM\EntityRepository;
use Octopod\PodcastBundle\Crawler\Event\ProcessEpisodeEvent;
use Octopod\PodcastBundle\Entity\PodcastEpisode;
use Octopod\PodcastBundle\Exception\RuntimeException;

class DoctrineMatchEpisodeListener
{
    private $episodeRepository;
    private $episodeClassName;
    private $podcastRepository;

    public function __construct(EntityRepository $episodeRepository, string $episodeClassName, EntityRepository $podcastRepository = null)
    {
        $this->episodeRepository = $episodeRepository;
        $this->episodeClassName = $episodeClassName;
        $this->podcastRepository = $podcastRepository;
    }

    public function onProcessEpisode(ProcessEpisodeEvent $event): void
    {
        if (null !== $event->getTargetEpisode()) {
            return;
        }

        $guid = $event->getEpisode()->getGuid();

        $episodeParameters = [
            'guid' => $guid,
        ];

        if (null !== $this->podcastRepository) {
            $feed = $event->getFeed();

            if (null === $podcast = $this->podcastRepository->findOneBy(['feed' => $feed])) {
                throw new RuntimeException(sprintf('Unable to find a podcast for feed "%s".', $feed));
            }

            $episodeParameters['podcast'] = $podcast;
        }

        if (null === $episode = $this->episodeRepository->findOneBy($episodeParameters)) {
            $episode = new $this->episodeClassName();

            $episode->setGuid($guid);

            if (isset($podcast) && $episode instanceof PodcastEpisode) {
                $episode->setPodcast($podcast);
            }
        }

        $event->setTargetEpisode($episode);
    }
}
