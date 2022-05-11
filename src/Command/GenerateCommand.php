<?php

namespace GalDigitalGmbh\SimpleMigration\Command;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'migration:generate',
    description: 'forward command to doctrine',
)]
class GenerateCommand extends BaseCommand
{
    /**
     * @var string[]
     */
    protected array $leadingCommandArguments = [
        'doctrine:migrations:generate',
        '-n',
    ];

    /**
     * @param string $namespace
     * @return string[]
     */
    protected function createNamespaceArguments(string $namespace): array
    {
        return [
            '--namespace=' . $namespace,
            '--prefix=' . $namespace,
        ];
    }
}
