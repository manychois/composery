<?php

declare(strict_types=1);

namespace Manychois\ComposeryTests;

use Exception;
use Manychois\Composery\App;
use Manychois\Composery\InitArguments;
use Manychois\Composery\InstallArguments;
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
        $shouldNotPresent = ['composer.json', 'composer.lock', 'src', 'vendor'];
        foreach ($shouldNotPresent as $path) {
            if (file_exists($path)) {
                throw new Exception("$path already exists");
            }
        }
    }

    protected function tearDown(): void
    {
        $shouldNotPresent = ['composer.json', 'composer.lock', 'src', 'vendor'];
        foreach ($shouldNotPresent as $path) {
            $success = $this->rmr("{$this->cwd}/$path");
            if (!$success && file_exists($path)) {
                throw new Exception("Failed to delete {$this->cwd}/$path");
            }
        }
        $success = chdir($this->prevCwd);
        if (!$success) {
            throw new Exception("Failed to change directory to {$this->prevCwd}");
        }
    }

    private function rmr(string $path): bool
    {
        if (!file_exists($path)) {
            return true;
        }
        if (is_dir($path)) {
            $hasFailedCase = false;
            foreach (scandir($path) as $file) {
                if ($file === '.' || $file === '..') continue;
                $fullPath = "$path/$file";
                if (is_dir($fullPath)) {
                    $hasFailedCase |= !$this->rmr($fullPath);
                } else {
                    $hasFailedCase |= !unlink($fullPath);
                }
            }
            return rmdir($path) && !$hasFailedCase;
        } else {
            return unlink($path);
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
        $expectedMessageLines = [
            'Writing ./composer.json',
            'PSR-4 autoloading configured. Use "<comment>namespace Manychois\Test;</comment>" in src/',
            'Include the Composer autoloader with: <comment>require \'vendor/autoload.php\';</comment>',
        ];
        foreach ($expectedMessageLines as $line) {
            static::assertStringContainsStringIgnoringLineEndings(
                $line,
                $output->getMessage(),
            );
        }

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

    public function testInstall(): void
    {
        $json = <<<'JSON'
{
    "name": "manychois/test",
    "description": "Test project",
    "type": "project",
    "require": {
        "php": "^8.2",
        "composer/composer": "^2.5"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0"
    }
}
JSON;
        if (!file_put_contents($this->cwd . '/composer.json', $json)) {
            throw new Exception("Failed to write {$this->cwd}/composer.json");
        }
        $app = new App();
        $args = new InstallArguments();
        $args->preferInstall = 'dist';
        $args->noDev = true;
        $output = $app->install($args);
        static::assertStringContainsStringIgnoringLineEndings(
            'Installing <info>composer/composer</info>',
            $output->getMessage(),
        );
        static::assertStringContainsStringIgnoringLineEndings(
            '<info>Generating autoload files</info>',
            $output->getMessage(),
        );
    }

    public function testInstallDryRun(): void
    {
        $json = <<<'JSON'
{
    "name": "manychois/test",
    "description": "Test project",
    "type": "project",
    "require": {
        "php": "^8.2",
        "composer/composer": "^2.5"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.0"
    }
}
JSON;
        if (!file_put_contents($this->cwd . '/composer.json', $json)) {
            throw new Exception("Failed to write {$this->cwd}/composer.json");
        }
        $app = new App();
        $args = new InstallArguments();
        $args->dryRun = true;
        $output = $app->install($args);
        static::assertStringContainsStringIgnoringLineEndings(
            'Installing <info>composer/composer</info>',
            $output->getMessage(),
        );
        static::assertStringNotContainsStringIgnoringCase(
            '<info>Generating autoload files</info>',
            $output->getMessage(),
        );

        foreach (scandir($this->cwd) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if ($file === 'README.md' || $file === 'composer.json') {
                continue;
            }
            static::fail("Unexpected file {$file} in {$this->cwd}");
        }
    }
}
