<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler;

use Octopod\PodcastBundle\Message\CrawlingMessageInterface;
use Psr\Log\LoggerAwareInterface;

interface CrawlerInterface extends LoggerAwareInterface
{
    public function execute(CrawlingMessageInterface $message): void;
}
