<?php

namespace GalDigitalGmbh\SimpleMigrate\Command;

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

    protected function createNamespaceArguments(string $namespace): array
    {
        return [
            '--namespace=' . $namespace,
            '--prefix=' . $namespace,
        ];
    }
}
