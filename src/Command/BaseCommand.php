<?php

namespace GalDigitalGmbh\SimpleMigrate\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Yaml;

abstract class BaseCommand extends Command
{
    public const ALL_NAMESPACES = 'all';

    /**
     * @var string[]
     */
    protected array $consoleArguments = [
        'php',
        'bin/console',
    ];

    /**
     * @var string[]
     */
    protected array $leadingCommandArguments = [];

    /**
     * @var string[]
     */
    protected array $trailingCommandArguments = [];

    /**
     * @var array|string[]
     */
    protected array $additionalNamespaces = [];

    /**
     * @param string[] $arguments
     *
     * @return string[]
     */
    public function getProxyCommand(array $arguments): array
    {
        return array_merge(
            $this->consoleArguments,
            $this->leadingCommandArguments,
            $arguments,
            $this->trailingCommandArguments,
        );
    }

    /**
     * @param string[] $arguments
     */
    public function proxyCommand(OutputInterface $output, array $arguments): void
    {
        $process = new Process($this->getProxyCommand($arguments), PIMCORE_PROJECT_ROOT);

        $process->mustRun(fn ($type, $data) => $output->write($data));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $selectedNamespace = $this->askForNamespace($input, $output);

        switch ($selectedNamespace) {
            case null:
                return self::FAILURE;

            case self::ALL_NAMESPACES:
                $this->proxyCommand($output, []);

                break;

            default:
                $this->proxyCommand($output, $this->createNamespaceArguments($selectedNamespace));

                break;
        }

        return self::SUCCESS;
    }

    protected function askForNamespace(InputInterface $input, OutputInterface $output): string|null
    {
        $config = (new YamlParser())->parseFile(
            PIMCORE_PROJECT_ROOT . '/config/packages/doctrine-migrations.yaml',
            Yaml::PARSE_CONSTANT | Yaml::PARSE_CUSTOM_TAGS,
        );
        if (!is_array($config['doctrine_migrations']['migrations_paths'] ?? null)) {
            return null;
        }

        $question = new ChoiceQuestion(
            'Please select namespace',
            array_merge(
                $this->additionalNamespaces,
                array_keys($config['doctrine_migrations']['migrations_paths']),
            ),
        );
        $selectedNamespace = $this->getHelper('question')->ask($input, $output, $question);

        $output->writeln('You have just selected: ' . $selectedNamespace);

        return $selectedNamespace;
    }

    /**
     * @return string[]
     */
    protected function createNamespaceArguments(string $namespace): array
    {
        return ['--prefix=' . $namespace];
    }
}
