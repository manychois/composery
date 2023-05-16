<?php

declare(strict_types=1);

namespace Manychois\Composery;

use Composer\Console\Application;
use Manychois\Composery\Output;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * A wrapper around Composer's Application class to make it easier to use.
 */
class App
{
    /**
     * Creates a basic composer.json file in current directory.
     * @param InitArguments $args Arguments to pass to `composer init`.
     * @return Output The command result.
     */
    public function init(InitArguments $args): Output
    {
        $inputArgs = ['command' => 'init'];
        $inputArgs = array_merge($inputArgs, $args->toOptions());
        $input = new ArrayInput($inputArgs);
        return $this->run($input);
    }

    /**
     * Installs the project dependencies from the composer.lock file if present, or falls back on the composer.json.
     * @param InstallArguments $args Arguments to pass to `composer install`.
     * @return Output The command result.
     */
    public function install(InstallArguments $args): Output
    {
        $inputArgs = ['command' => 'install'];
        $inputArgs = array_merge($inputArgs, $args->toOptions());
        $input = new ArrayInput($inputArgs);
        return $this->run($input);
    }

    public function require(string $package): Output
    {
        $input = new ArrayInput(['command' => 'require', 'packages' => [$package]]);
        return $this->run($input);
    }

    protected function run(ArrayInput $input): Output
    {
        $input->setInteractive(false);
        $composer = new Application();
        $composer->setAutoExit(false);
        $output = new Output();
        $output->exitCode = $composer->run($input, $output);
        return $output;
    }
}
