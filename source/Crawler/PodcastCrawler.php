<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler;

use Octopod\PodcastBundle\Crawler\Adapter\CrawlerAdapterInterface;
use Octopod\PodcastBundle\Crawler\Event\PostProcessEvent;
use Octopod\PodcastBundle\Crawler\Event\ProcessEpisodeEvent;
use Octopod\PodcastBundle\Crawler\Event\ProcessPodcastEvent;
use Octopod\PodcastBundle\Exception\LogicException;
use Octopod\PodcastBundle\Message\CrawlEpisodesMessage;
use Octopod\PodcastBundle\Message\CrawlingMessageInterface;
use Octopod\PodcastBundle\Message\CrawlPodcastMessage;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class PodcastCrawler implements CrawlerInterface
{
    use LoggerAwareTrait;

    private $adapter;
    private $eventDispatcher;

    public function __construct(CrawlerAdapterInterface $adapter, EventDispatcherInterface $eventDispatcher)
    {
        $this->adapter = $adapter;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = new NullLogger();
    }

    public function execute(CrawlingMessageInterface $message): void
    {
        if ($message instanceof CrawlPodcastMessage) {
            $this->logger->debug(sprintf('Fetching feed: %s', $message->getFeed()));

            $podcast = $this->adapter->crawlMetadata($message->getFeed());

            $this->logger->debug(sprintf('Processing feed: %s', $message->getFeed()));

            $this->eventDispatcher->dispatch(new ProcessPodcastEvent($podcast));
        } else if ($message instanceof CrawlEpisodesMessage) {
            $this->logger->debug(sprintf('Fetching episodes from feed: %s', $message->getFeed()));

            $episodes = $this->adapter->crawlEpisodes($message->getFeed());

            foreach ($episodes as $episode) {
                $this->logger->debug(sprintf('Processing episode: %s', $episode->getGuid()));

                $this->eventDispatcher->dispatch(new ProcessEpisodeEvent($episode));
            }
        } else {
            throw new LogicException(sprintf('The %s crawler is unable to handle messages of type %s.', self::class, get_class($message)));
        }

        $this->logger->debug('Finished processing');

        $this->eventDispatcher->dispatch(new PostProcessEvent());

        $this->logger->debug('Finished post-processing');
    }
}
