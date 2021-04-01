<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\Adapter;

use Octopod\PodcastBundle\Entity\Podcast;

class PodcastindexAdapter implements CrawlerAdapterInterface
{
    public function crawlMetadata(string $feed): Podcast
    {
        // TODO: Implement crawl() method.
    }

    public function crawlEpisodes(string $feed): array
    {
        // TODO: Implement crawlEpisodes() method.
    }
}
