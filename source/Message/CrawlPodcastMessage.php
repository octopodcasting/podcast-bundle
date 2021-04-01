<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Message;

class CrawlPodcastMessage implements CrawlingMessageInterface
{
    private $feed;

    public function __construct(string $feed)
    {
        $this->feed = $feed;
    }

    public function feed(string $feed): self
    {
        $this->feed = $feed;

        return $this;
    }

    public function getFeed(): string
    {
        return $this->feed;
    }
}
