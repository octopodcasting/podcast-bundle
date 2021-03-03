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
    private $targetEpisode;

    public function __construct(Episode $episode)
    {
        $this->episode = $episode;
    }

    public function getEpisode(): Episode
    {
        return $this->episode;
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
