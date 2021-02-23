<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Entity;

class PodcastEpisode extends Episode
{
    /** @var Podcast */
    protected $podcast;

    public function getPodcast(): ?Podcast
    {
        return $this->podcast;
    }

    public function setPodcast(?Podcast $podcast): self
    {
        $this->podcast = $podcast;

        return $this;
    }
}
