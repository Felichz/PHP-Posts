<?php namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class Hello extends Command
{
    protected static $defaultName = 'hello';

    protected function configure()
    {
        $this
            ->setDescription('Says hello to you.')
            ->setHelp('This command makes the CLI greets you depending of your name.')
            ->addArgument('name', InputArgument::REQUIRED, 'Your name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Hello ' . $input->getArgument('name'),
            "I'm your CLI and I love you :)"
        ]);
    }
}