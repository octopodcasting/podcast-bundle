<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler;

use Psr\Log\LoggerAwareInterface;

interface CrawlerInterface extends LoggerAwareInterface
{
    public function crawl(string $id): void;

    public function crawlEpisodes(string $id): void;
}
