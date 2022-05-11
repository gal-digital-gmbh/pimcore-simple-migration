<?php

namespace GalDigitalGmbh\SimpleMigrate\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'migrate',
    description: 'forward command to doctrine',
)]
class MigrateCommand extends BaseCommand
{
    /**
     * @var string[]
     */
    protected array $leadingCommandArguments = [
        'doctrine:migrations:migrate',
        '-n',
    ];

    protected array $additionalNamespaces = [
        self::ALL_NAMESPACES,
    ];

    protected function configure(): void
    {
        $this
            ->setHelp('Provide picker to select namespace to perform migration')
            ->addOption(
                name: self::ALL_NAMESPACES,
                shortcut: 'a',
                mode: InputOption::VALUE_NONE,
                description: 'perform migration to all prefixes',
            )
        ;
    }

    protected function askForNamespace(InputInterface $input, OutputInterface $output): string|null
    {
        if ($input->getOption(self::ALL_NAMESPACES)) {
            return self::ALL_NAMESPACES;
        }

        return parent::askForNamespace($input, $output);
    }
}
