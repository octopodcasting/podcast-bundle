<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler;

use Laminas\Feed\Reader\Entry\Rss as RssEntry;
use Laminas\Feed\Reader\Feed\Rss as RssFeed;
use Laminas\Feed\Reader\Extension\Podcast\Entry as PodcastEntry;
use Laminas\Feed\Reader\Extension\Podcast\Feed as PodcastFeed;
use Laminas\Feed\Reader\Reader;
use Octopod\PodcastBundle\Crawler\Event\PostProcessEvent;
use Octopod\PodcastBundle\Crawler\Event\ProcessEpisodeEvent;
use Octopod\PodcastBundle\Entity\Episode;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FeedCrawler implements CrawlerInterface
{
    use LoggerAwareTrait;

    private $eventDispatcher;
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient, EventDispatcherInterface $eventDispatcher)
    {
        if (!class_exists(Reader::class)) {
            throw new \Exception('To crawl episodes directly from the RSS feed, install the "laminas/laminas-feed" package with Composer.');
        }

        $this->httpClient = $httpClient;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = new NullLogger();
    }

    public function crawl(string $id): void
    {
        throw new \Exception('Crawling podcast data with the FeedCrawler class hasn\'t been implemented.');
    }

    public function crawlEpisodes(string $id): void
    {
        $response = $this->httpClient->request('GET', $id);

        /** @var RssFeed|PodcastFeed $feed */
        $feed = Reader::importString($response->getContent());

        $episodes = [];

        $this->logger->debug(sprintf('Found %s episodes', count($feed)));

        /** @var RssEntry|PodcastEntry $item */
        foreach ($feed as $item) {
            $episode = new Episode();

            $episode->setTitle($item->getTitle());
            $episode->setLink($item->getLink());
            $episode->setGuid($item->getId());
            $episode->setDescription($item->getDescription());
            $episode->setDuration($item->getDuration());
            $episode->setAuthor($item->getAuthor());
            $episode->setImage($item->getItunesImage());
            $episode->setExplicit($item->getExplicit() === 'yes');
            $episode->setPublishedAt($item->getDateCreated());
            $episode->setEnclosureUrl($item->getEnclosure()->url);
            $episode->setEnclosureLength($item->getEnclosure()->length);
            $episode->setEnclosureType($item->getEnclosure()->type);

            $episodes[] = $episode;
        }

        foreach ($episodes as $episode) {
            $this->logger->debug(sprintf('Processing episode: %s', $episode->getGuid()));

            $this->eventDispatcher->dispatch(new ProcessEpisodeEvent($episode));
        }

        $this->logger->debug('Post-processing...');

        $this->eventDispatcher->dispatch(new PostProcessEvent());
    }
}
