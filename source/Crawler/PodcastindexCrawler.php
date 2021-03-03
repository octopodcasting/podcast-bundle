<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler;

use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PodcastindexCrawler implements CrawlerInterface
{
    use LoggerAwareTrait;

    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = new NullLogger();
    }

    public function crawl(string $id): void
    {
        throw new \Exception('Crawling podcast data with the PodcastindexCrawler class hasn\'t been implemented.');
    }

    public function crawlEpisodes(string $id): void
    {
        throw new \Exception('Crawling episode data with the PodcastindexCrawler class hasn\'t been implemented.');
    }
}
