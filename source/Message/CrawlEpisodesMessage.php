<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Message;

class CrawlEpisodesMessage implements CrawlingMessageInterface
{
    private $feed;

    public function __construct(string $feed = null)
    {
        $this->feed = $feed;
    }

    public function feed(string $feed = null): self
    {
        $this->feed = $feed;

        return $this;
    }

    public function getFeed(): ?string
    {
        return $this->feed;
    }
}
