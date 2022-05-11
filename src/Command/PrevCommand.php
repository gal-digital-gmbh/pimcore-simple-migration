<?php

namespace GalDigitalGmbh\SimpleMigration\Command;

use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'migrate:prev',
    description: 'forward command to doctrine',
)]
class PrevCommand extends BaseCommand
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
        'prev',
    ];

}
