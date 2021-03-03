<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\Event;

use Octopod\PodcastBundle\Entity\Podcast;
use Symfony\Contracts\EventDispatcher\Event;

class ProcessPodcastEvent extends Event
{
    private $podcast;
    private $targetPodcast;

    public function __construct(Podcast $podcast)
    {
        $this->podcast = $podcast;
    }

    public function getPodcast(): Podcast
    {
        return $this->podcast;
    }

    public function getTargetPodcast(): ?Podcast
    {
        return $this->targetPodcast;
    }

    public function setTargetPodcast(?Podcast $targetPodcast): void
    {
        $this->targetPodcast = $targetPodcast;
    }
}
