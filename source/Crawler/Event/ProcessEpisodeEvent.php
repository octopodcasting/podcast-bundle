<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\Event;

use Octopod\PodcastBundle\Entity\Episode;
use Symfony\Contracts\EventDispatcher\Event;

class ProcessEpisodeEvent extends Event
{
    private $episode;
    private $feed;
    private $targetEpisode;

    public function __construct(Episode $episode, string $feed = null)
    {
        $this->episode = $episode;
        $this->feed = $feed;
    }

    public function getEpisode(): Episode
    {
        return $this->episode;
    }

    public function getFeed(): ?string
    {
        return $this->feed;
    }

    public function getTargetEpisode(): ?Episode
    {
        return $this->targetEpisode;
    }

    public function setTargetEpisode(?Episode $targetEpisode): void
    {
        $this->targetEpisode = $targetEpisode;
    }
}
