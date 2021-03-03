<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Command;

use Octopod\PodcastBundle\Crawler\CrawlerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CrawlEpisodesCommand extends Command
{
    protected static $defaultName = 'podcast:crawl:episodes';

    private $crawler;
    private $feed;

    public function __construct(string $name = null, CrawlerInterface $crawler, string $feed)
    {
        parent::__construct($name);

        $this->crawler = $crawler;
        $this->feed = $feed;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Crawl the latest podcast episodes')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->crawler->setLogger(new ConsoleLogger($output));

        $this->crawler->crawlEpisodes($this->feed);

        $io->success('Crawling done');

        return Command::SUCCESS;
    }
}
