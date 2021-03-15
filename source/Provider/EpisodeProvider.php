<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Provider;

use Doctrine\ORM\EntityRepository;
use Octopod\PodcastBundle\Entity\Episode;
use Octopod\PodcastBundle\Entity\Podcast;

class EpisodeProvider
{
    private $episodeRepository;

    public function __construct(EntityRepository $episodeRepository, Podcast $podcast = null)
    {
        $this->episodeRepository = $episodeRepository;
    }

    public function find($id): ?Episode
    {
        return $this->episodeRepository->find($id);
    }

    public function findEpisodes(int $limit = null, int $offset = null): ?array
    {
        return $this->episodeRepository->findBy([], ['publishedAt' => 'DESC'], $limit, $offset);
    }

    public function count(): int
    {
        return $this->episodeRepository->count([]);
    }
}
