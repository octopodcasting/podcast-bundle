<?php
/*
 * (c) Tim Goudriaan <tim@codedmonkey.com>
 */

namespace Octopod\PodcastBundle\Command;

use Octopod\PodcastBundle\Crawler\CrawlerInterface;
use Octopod\PodcastBundle\Exception\LogicException;
use Octopod\PodcastBundle\Message\CrawlEpisodesMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class CrawlEpisodesCommand extends Command
{
    protected static $defaultName = 'podcast:crawl:episodes';

    private $bus;
    private $crawler;
    private $feed;

    public function __construct(?string $name, ?CrawlerInterface $crawler, ?MessageBusInterface $bus, ?string $feed)
    {
        if ($crawler === null && $bus === null) {
            throw new LogicException(sprintf('"%s" needs a Crawler or a Bus but both cannot be "null".', static::class));
        }

        $this->crawler = $crawler;
        $this->bus = $bus;
        $this->feed = $feed;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        if (null === $this->feed) {
            $this
                ->setDescription('Crawls the episodes of the podcast in the specified feed')
                ->addArgument('feed', InputArgument::REQUIRED, 'The feed to crawl')
            ;
        } else {
            $this
                ->setDescription('Crawls the episodes of the podcast')
            ;
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $message = new CrawlEpisodesMessage($this->feed ?? $input->getArgument('feed'));

        if (null === $this->bus) {
            $this->crawler->setLogger(new ConsoleLogger($output));

            $this->crawler->execute($message);

            $io->success('Finished crawling.');
        } else {
            $this->bus->dispatch($message);

            $io->success('Dispatched crawling task.');
        }

        return Command::SUCCESS;
    }
}
