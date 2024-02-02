<?php

declare(strict_types=1);

namespace Manychois\Composery;

use Composer\Console\Application;
use Manychois\Composery\Output;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;

/**
 * A wrapper around Composer's Application class to make it easier to use.
 */
class App
{
    /**
     * Creates a basic composer.json file in current directory.
     *
     * @param InitOptions $args Arguments to pass to `composer init`.
     *
     * @return Output The command result.
     */
    public function init(InitOptions $args): Output
    {
        $inputArgs = ['command' => 'init'];
        $inputArgs = array_merge($inputArgs, $args->toOptions());
        $input = new ArrayInput($inputArgs);

        return $this->execute($input);
    }

    /**
     * Installs the project dependencies from the composer.lock file if present, or falls back on the composer.json.
     *
     * @param InstallOptions $args Arguments to pass to `composer install`.
     *
     * @return Output The command result.
     */
    public function install(InstallOptions $args): Output
    {
        $inputArgs = ['command' => 'install'];
        $inputArgs = array_merge($inputArgs, $args->toOptions());
        $input = new ArrayInput($inputArgs);

        return $this->execute($input);
    }

    /**
     * Runs the given command as if it were entered in the terminal.
     *
     * @param string $command The command to run, e.g. "install --dry-run".
     *
     * @return Output The command result.
     */
    public function runInput(string $command): Output
    {
        $input = new StringInput($command);

        return $this->execute($input);
    }

    /**
     * Gets the latest versions of the dependencies and updates the `composer.lock` file.
     *
     * @param UpdateOptions $args        Arguments to pass to `composer update`.
     * @param string        ...$packages Optional, specify the packages to update.
     *
     * @return Output The command result.
     */
    public function update(UpdateOptions $args, string ...$packages): Output
    {
        $updateArgs = ['command' => 'update'];
        if (\count($packages) > 0) {
            $updateArgs['packages'] = $packages;
        }
        $updateArgs = array_merge($updateArgs, $args->toOptions());
        $input = new ArrayInput($updateArgs);

        return $this->execute($input);
    }

    /**
     * Executes the given command with the given arguments.
     *
     * @param InputInterface $input The inputs to pass to the command.
     *
     * @return Output The command result.
     */
    protected function execute(InputInterface $input): Output
    {
        $input->setInteractive(false);
        $composer = new Application();
        $composer->setAutoExit(false);
        $output = new Output();
        $output->exitCode = $composer->run($input, $output);

        return $output;
    }
}
