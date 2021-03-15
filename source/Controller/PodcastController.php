<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Controller;

use Octopod\PodcastBundle\Entity\Podcast;
use Octopod\PodcastBundle\Provider\EpisodeProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PodcastController extends AbstractController
{
    public const FIRST_PAGE_ITEMS = 5;
    public const PAGE_ITEMS = 20;

    private $episodeProvider;
    private $podcast;

    public function __construct(EpisodeProvider $episodeProvider, Podcast $podcast)
    {
        $this->episodeProvider = $episodeProvider;
        $this->podcast = $podcast;
    }

    public function list(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        $offset = 1 === $page ? 0 : (static::FIRST_PAGE_ITEMS + (($page - 2) * self::PAGE_ITEMS));
        $episodes = $this->episodeProvider->findEpisodes(1 === $page ? static::FIRST_PAGE_ITEMS : static::PAGE_ITEMS, $offset);

        $lastPage = (($this->episodeProvider->count() - static::FIRST_PAGE_ITEMS) / static::PAGE_ITEMS) + 1;

        return $this->render('@Podcast/list.html.twig', [
            'podcast' => $this->podcast,
            'episodes' => $episodes,

            'current_page' => $page,
            'last_page' => $lastPage,
        ]);
    }

    public function episode(Request $request): Response
    {
        $id = $request->attributes->get('episode');

        $episode = $this->episodeProvider->find($id);

        return $this->render('@Podcast/episode.html.twig', [
            'podcast' => $this->podcast,
            'episode' => $episode,
        ]);
    }

    public function archive(): Response
    {
        $episodes = $this->episodeProvider->findEpisodes();

        return $this->render('@Podcast/archive.html.twig', [
            'podcast' => $this->podcast,
            'episodes' => $episodes,
        ]);
    }
}
