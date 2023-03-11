<?php

declare(strict_types=1);

namespace Manychois\ComposeryTests;

use Exception;
use Manychois\Composery\App;
use Manychois\Composery\InitArguments;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    private string $prevCwd;
    private string $cwd;

    protected function setUp(): void
    {
        $this->prevCwd = getcwd();
        $this->cwd = __DIR__ . '/cwd';
        $success = chdir($this->cwd);
        if (!$success) {
            throw new Exception("Failed to change directory to {$this->cwd}");
        }
        if (file_exists($this->cwd . '/composer.json')) {
            throw new Exception("{$this->cwd}/composer.json already exists");
        }
    }

    protected function tearDown(): void
    {
        $success = unlink($this->cwd . '/composer.json');
        if (!$success) {
            throw new Exception("Failed to delete {$this->cwd}/composer.json");
        }
        $success = chdir($this->prevCwd);
        if (!$success) {
            throw new Exception("Failed to change directory to {$this->prevCwd}");
        }
    }

    public function testInit(): void
    {
        $app = new App();
        $args = new InitArguments();
        $args->name = 'manychois/test';
        $args->description = 'Test project';
        $args->author = 'Siu Pang Tommy Choi';
        $args->type = 'project';
        $args->homepage = 'https://github.com/manychois/composery';
        $args->require = ['php:^8.2', 'composer/composer:^2.5'];
        $args->requireDev = ['phpunit/phpunit:^10.0', 'squizlabs/php_codesniffer:^3.7'];
        $args->stability = 'dev';
        $args->license = 'MIT';
        $args->repository = [
            'https://example.org',
            json_encode([
                'type' => 'package',
                'package' => [
                    'name' => 'smarty/smarty',
                    'version' => '3.1.7',
                    'dist' => [
                        'url' => 'https://www.smarty.net/files/Smarty-3.1.7.zip',
                        'type' => 'zip',
                    ],
                    'source' => [
                        'url' => 'https://smarty-php.googlecode.com/svn/',
                        'type' => 'svn',
                        'reference' => 'tags/Smarty_3_1_7/distribution/',
                    ],
                ],
            ]),
        ];
        $args->autoload = 'src/';

        $output = $app->init($args);
        $expectedMessage = [
            '<warning>Composer is operating slower than normal because you have Xdebug enabled. See https://getcomposer.org/xdebug</warning>',
            'Writing ./composer.json',
            'PSR-4 autoloading configured. Use "<comment>namespace Manychois\Test;</comment>" in src/',
            'Include the Composer autoloader with: <comment>require \'vendor/autoload.php\';</comment>',
            ''
        ];
        static::assertEquals(implode("\n", $expectedMessage), $output->getMessage());

        $expectedComposerJson = <<<'JSON'
{
    "name": "manychois/test",
    "description": "Test project",
    "type": "project",
    "homepage": "https://github.com/manychois/composery",
    "require": {
        "php": "^8.2",
        "composer/composer": "^2.5"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0",
        "squizlabs/php_codesniffer": "^3.7"
    },
    "license": "MIT",
    "autoload": {
        "psr-4": {
            "Manychois\\Test\\": "src/"
        }
    },
    "authors": [
        {
            "name": "Siu Pang Tommy Choi"
        }
    ],
    "repositories": [
        {
            "type": "composer",
            "url": "https://example.org"
        },
        {
            "type": "package",
            "package": {
                "name": "smarty/smarty",
                "version": "3.1.7",
                "dist": {
                    "url": "https://www.smarty.net/files/Smarty-3.1.7.zip",
                    "type": "zip"
                },
                "source": {
                    "url": "https://smarty-php.googlecode.com/svn/",
                    "type": "svn",
                    "reference": "tags/Smarty_3_1_7/distribution/"
                }
            }
        }
    ],
    "minimum-stability": "dev"
}

JSON;
        $actualComposerJson = file_get_contents($this->cwd . '/composer.json');
        static::assertEquals($expectedComposerJson, $actualComposerJson);
    }
}
