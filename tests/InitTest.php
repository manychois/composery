<?php

declare(strict_types=1);

namespace Manychois\ComposeryTests;

use Manychois\Composery\App;
use Manychois\Composery\ArgumentOptions\MinimumStability;
use Manychois\Composery\ArgumentOptions\PackageType;
use Manychois\Composery\InitArguments;

class InitTest extends AbstractCommandTestCase
{
    public function testInit(): void
    {
        $app = new App();
        $args = new InitArguments();
        $args->name = 'manychois/composery-test';
        $args->description = 'Composery test';
        $args->author = 'Tommy Choi';
        $args->type = PackageType::Project;
        $args->homepage = 'https://github.com/manychois/composery';
        $args->require = ['php:>=8.2', 'psr/log:^3.0'];
        $args->requireDiv = ['phpunit/phpunit:^10.5'];
        $args->stability = MinimumStability::Alpha;
        $args->license = 'MIT';
        $j = static fn ($array) => \json_encode($array, JSON_UNESCAPED_SLASHES);
        $args->repository = [
            $j([
                'type' => 'composer',
                'url' => 'http://packages.example.com',
            ]),
            $j([
                'type' => 'composer',
                'url' => 'https://packages.example.com',
                'options' => [
                    'ssl' => [
                        'verify_peer' => 'true',
                    ],
                ],
            ]),
        ];
        $args->autoload = 'src/';

        $output = $app->init($args);
        $lines = $output->getLines();
        static::assertOutputLines($lines, 'Writing ./composer.json');

        $composerJsonOutput = \file_get_contents($this->cwd . '/composer.json');
        $json = \json_decode($composerJsonOutput, true);

        static::assertEquals($args->name, $json['name']);
        static::assertEquals($args->description, $json['description']);
        static::assertEquals('project', $json['type']);
        static::assertEquals($args->homepage, $json['homepage']);
        static::assertEquals('{"php":">=8.2","psr/log":"^3.0"}', $j($json['require']));
        static::assertEquals('{"phpunit/phpunit":"^10.5"}', $j($json['require-dev']));
        static::assertEquals($args->license, $json['license']);
        static::assertEquals('{"psr-4":{"Manychois\\\\ComposeryTest\\\\":"src/"}}', $j($json['autoload']));
        static::assertEquals('[{"name":"Tommy Choi"}]', $j($json['authors']));
        static::assertEquals('alpha', $json['minimum-stability']);
        static::assertEquals('[' . \implode(',', $args->repository) . ']', $j($json['repositories']));
    }
}
