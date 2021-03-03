<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Crawler\EventListener;

use Octopod\PodcastBundle\Crawler\Event\ProcessEpisodeEvent;
use Octopod\PodcastBundle\Crawler\Event\ProcessPodcastEvent;

class MapCrawlingFieldsListener
{
    public function onProcessPodcast(ProcessPodcastEvent $event): void
    {
        if (null === $targetPodcast = $event->getTargetPodcast()) {
            return;
        }

        $podcast = $event->getPodcast();

        if ($targetPodcast === $podcast) {
            return;
        }

        $targetPodcast->setTitle($podcast->getTitle());
        $targetPodcast->setLink($podcast->getLink());
        $targetPodcast->setDescription($podcast->getDescription());
        $targetPodcast->setAuthor($podcast->getAuthor());
        $targetPodcast->setImage($podcast->getImage());
        $targetPodcast->setCategories($podcast->getCategories());
        $targetPodcast->setExplicit($podcast->isExplicit());
        $targetPodcast->setOwnerName($podcast->getOwnerName());
        $targetPodcast->setOwnerEmail($podcast->getOwnerEmail());
    }

    public function onProcessEpisode(ProcessEpisodeEvent $event): void
    {
        if (null === $targetEpisode = $event->getTargetEpisode()) {
            return;
        }

        $episode = $event->getEpisode();

        if ($targetEpisode === $episode) {
            return;
        }

        $targetEpisode->setTitle($episode->getTitle());
        $targetEpisode->setLink($episode->getLink());
        $targetEpisode->setDescription($episode->getDescription());
        $targetEpisode->setDuration($episode->getDuration());
        $targetEpisode->setAuthor($episode->getAuthor());
        $targetEpisode->setImage($episode->getImage());
        $targetEpisode->setExplicit($episode->isExplicit());
        $targetEpisode->setPublishedAt($episode->getPublishedAt());
        $targetEpisode->setEnclosureUrl($episode->getEnclosureUrl());
        $targetEpisode->setEnclosureLength($episode->getEnclosureLength());
        $targetEpisode->setEnclosureType($episode->getEnclosureType());
        $targetEpisode->setChaptersUrl($episode->getChaptersUrl());
        $targetEpisode->setChaptersType($episode->getChaptersType());
        $targetEpisode->setTranscriptUrl($episode->getTranscriptUrl());
        $targetEpisode->setTranscriptType($episode->getTranscriptType());
    }
}
