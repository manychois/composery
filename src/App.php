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
        $composer->run($input, $output);
        return $output;
    }
}
