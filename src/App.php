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
     * @param InitOptions $options Options to pass to `composer init`.
     *
     * @return Output The command result.
     */
    public function init(InitOptions $options): Output
    {
        $params = ['command' => 'init'];
        $params = \array_merge($params, $options->toParameters());
        $input = new ArrayInput($params);

        return $this->execute($input);
    }

    /**
     * Installs the project dependencies from the composer.lock file if present, or falls back on the composer.json.
     *
     * @param InstallOptions $options Options to pass to `composer install`.
     *
     * @return Output The command result.
     */
    public function install(InstallOptions $options): Output
    {
        $params = ['command' => 'install'];
        $params = \array_merge($params, $options->toParameters());
        $input = new ArrayInput($params);

        return $this->execute($input);
    }

    /**
     * Adds new packages to the composer.json file from the current directory.
     * If no file exists one will be created on the fly.
     *
     * @param RequireOptions $options     Options to pass to `composer require`.
     * @param string         ...$packages Optional, specify the packages to require.
     *
     * @return Output The command result.
     */
    public function require(RequireOptions $options, string ...$packages): Output
    {
        $params = ['command' => 'require'];
        if (\count($packages) > 0) {
            $params['packages'] = $packages;
        }
        $params = \array_merge($params, $options->toParameters());
        $input = new ArrayInput($params);

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
     * @param UpdateOptions $options     Options to pass to `composer update`.
     * @param string        ...$packages Optional, specify the packages to update.
     *
     * @return Output The command result.
     */
    public function update(UpdateOptions $options, string ...$packages): Output
    {
        $params = ['command' => 'update'];
        if (\count($packages) > 0) {
            $params['packages'] = $packages;
        }
        $params = \array_merge($params, $options->toParameters());
        $input = new ArrayInput($params);

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
