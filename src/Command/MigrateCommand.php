<?php

namespace GalDigitalGmbh\SimpleMigrate\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Yaml;

#[AsCommand(
    name: 'migrate',
    description: 'forward command to doctrine',
)]
class MigrateCommand extends Command
{
    public function proxyCommand(OutputInterface $output, array $arguments)
    {
        $process = new Process(array_merge([
            'php',
            'bin/console',
            'doctrine:migrations:migrate',
            '-n',
        ], $arguments), PIMCORE_PROJECT_ROOT);

        $process->mustRun(fn ($type, $data) => $output->write($data));
    }

    protected function configure(): void
    {
        $this
            ->setHelp('Provide picker to select namespace to perform migration')
            ->addOption(
                name: 'all',
                shortcut: 'a',
                mode: InputOption::VALUE_NONE,
                description: 'perform migration to all prefixes',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('all')) {
            $this->proxyCommand($output, []);

            return self::SUCCESS;
        }
        $config = (new YamlParser())->parseFile(
            PIMCORE_PROJECT_ROOT . '/config/packages/doctrine-migrations.yaml',
            Yaml::PARSE_CONSTANT | Yaml::PARSE_CUSTOM_TAGS,
        );
        if (!isset($config['doctrine_migrations']) || !isset($config['doctrine_migrations']['migrations_paths'])) {
            return self::FAILURE;
        }

        $question = new ChoiceQuestion(
            'Please select namespace',
            array_merge(
                ['all'],
                array_keys($config['doctrine_migrations']['migrations_paths']),
            ),
        );
        $selectedNamespace = $this->getHelper('question')->ask($input, $output, $question);
        $output->writeln('You have just selected: ' . $selectedNamespace);

        if ($selectedNamespace === 'all') {
            $this->proxyCommand($output, []);
        } else {
            $this->proxyCommand($output, ['--prefix=' . $selectedNamespace]);
        }

        return self::SUCCESS;
    }
}
