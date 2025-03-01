<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\NewsGrabber;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'blog:news:import',
    description: 'Import news from internet',
)]
class CrawlerCommand extends Command
{
    use LockableTrait;

    public function __construct(private readonly NewsGrabber $newsGrabber)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('count', InputArgument::OPTIONAL, 'Number of news')
            ->addOption('dryRun', null, InputOption::VALUE_OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');
            return Command::SUCCESS;
        }

        $count = (int) $input->getArgument('count');
        $dryRun = (bool) $input->getOption('dryRun');

        if ($output->isVerbose()) {
            $logger = new ConsoleLogger($output);
            $this->newsGrabber->setLogger($logger);
        }

        $this->newsGrabber->importNews($count, $dryRun);

        $this->release();

        return Command::SUCCESS;
    }
}
