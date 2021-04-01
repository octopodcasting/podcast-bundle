<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Message;

interface CrawlingMessageInterface
{
    public function getFeed(): ?string;
}
