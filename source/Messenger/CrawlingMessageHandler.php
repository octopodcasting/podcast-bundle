<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Messenger;

use Octopod\PodcastBundle\Crawler\CrawlerInterface;
use Octopod\PodcastBundle\Message\CrawlingMessageInterface;

class CrawlingMessageHandler
{
    private $crawler;

    public function __construct(CrawlerInterface $crawler)
    {
        $this->crawler = $crawler;
    }

    public function __invoke(CrawlingMessageInterface $message): void
    {
        $this->crawler->execute($message);
    }
}
