<?php

namespace GalDigitalGmbh\SimpleMigrate\Command;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'migrate:next',
    description: 'forward command to doctrine',
)]
class NextCommand extends BaseCommand
{
    /**
     * @var string[]
     */
    protected array $leadingCommandArguments = [
        'doctrine:migrations:migrate',
        '-n',
        '--allow-no-migration',
    ];

    /**
     * @var string[]
     */
    protected array $trailingCommandArguments = [
        'next',
    ];

}
