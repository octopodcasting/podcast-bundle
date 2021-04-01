<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\Adapter;

use Laminas\Feed\Reader\Entry\Rss as RssEntry;
use Laminas\Feed\Reader\Extension\Podcast\Entry as PodcastEntry;
use Laminas\Feed\Reader\Extension\Podcast\Feed as PodcastFeed;
use Laminas\Feed\Reader\Feed\Rss as RssFeed;
use Laminas\Feed\Reader\Reader;
use Octopod\PodcastBundle\Entity\Episode;
use Octopod\PodcastBundle\Entity\Podcast;
use Octopod\PodcastBundle\Exception\LogicException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class LaminasFeedAdapter implements CrawlerAdapterInterface
{
    use LoggerAwareTrait;

    private $eventDispatcher;
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        if (!class_exists(Reader::class)) {
            throw new LogicException('To crawl episodes directly from the RSS feed, install the "laminas/laminas-feed" package with Composer.');
        }

        $this->httpClient = $httpClient;
        $this->logger = new NullLogger();
    }

    public function crawlMetadata(string $feed): Podcast
    {
        // TODO: Implement crawl() method.
    }

    public function crawlEpisodes(string $feed): array
    {
        $rawResponse = $this->httpClient->request('GET', $feed);

        /** @var RssFeed|PodcastFeed $response */
        $response = Reader::importString($rawResponse->getContent());

        $episodes = [];

        $this->logger->debug(sprintf('Found %s episodes', count($response)));

        /** @var RssEntry|PodcastEntry $item */
        foreach ($response as $item) {
            $episode = new Episode();

            $episode->setTitle($item->getTitle());
            $episode->setLink($item->getLink());
            $episode->setGuid($item->getId());
            $episode->setDescription($item->getDescription());
            $episode->setDuration($item->getDuration());
            $episode->setAuthor($item->getCastAuthor());
            $episode->setImage($item->getItunesImage());
            $episode->setExplicit($item->getExplicit() === 'yes');
            $episode->setPublishedAt($item->getDateCreated());
            $episode->setEnclosureUrl($item->getEnclosure()->url);
            $episode->setEnclosureLength($item->getEnclosure()->length);
            $episode->setEnclosureType($item->getEnclosure()->type);

            $episodes[] = $episode;
        }

        return $episodes;
    }
}
