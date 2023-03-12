<?php

declare(strict_types=1);

namespace Manychois\Composery;

use Composer\Console\Application;
use Manychois\Composery\Output;
use Symfony\Component\Console\Input\ArrayInput;

class App
{
    /**
     * Creates a basic composer.json file in current directory
     * @param InitArguments $args Arguments to pass to `composer init`.
     * @return Output The command result.
     * Exit codes:
     *
     * - 0: OK
     * - 1: Generic/unknown error code
     * - 2: Dependency solving error code
     */
    public function init(InitArguments $args): Output
    {
        $inputArgs = ['command' => 'init'];
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
