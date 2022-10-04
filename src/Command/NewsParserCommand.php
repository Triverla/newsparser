<?php

namespace App\Command;

use App\Actions\Parser;
use App\Resources\HighLoad;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NewsParserCommand extends Command
{
    protected static $defaultName = 'parse:news';
    protected static $defaultDescription = 'Add a short description for your command';

    private Parser $parser;
    public function __construct(Parser $parser)
    {
        parent::__construct();

        $this->parser = $parser;
    }

    protected function configure(): void
    {
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('dry-run')) {
            $io->note('Dry mode enabled');

        }

        $output->writeln("Start");

        $posts = $this->parser->parse();

        $output->writeln($posts);

        $io->success('Parsing completed');

        return Command::SUCCESS;
    }
}
