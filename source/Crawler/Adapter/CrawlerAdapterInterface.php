<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\Adapter;

use Octopod\PodcastBundle\Entity\Episode;
use Octopod\PodcastBundle\Entity\Podcast;

interface CrawlerAdapterInterface
{
    public function crawlMetadata(string $feed): Podcast;

    /**
     * @return Episode[]
     */
    public function crawlEpisodes(string $feed): array;
}
