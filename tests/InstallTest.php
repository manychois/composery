<?php

declare(strict_types=1);

namespace Manychois\ComposeryTests;

use Manychois\Composery\InstallOptions;
use Manychois\Composery\OptionChoices\AuditFormat;
use Manychois\Composery\OptionChoices\InstallPreference;

class InstallTest extends AbstractCommandTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $initialComposerJson = <<<'JSON'
{
    "name": "manychois/composery-test",
    "type": "library",
    "require": {
        "php": "^7.0",
        "composer/composer": "^2.5"
    },
    "require-dev": {
        "phpunit/phpunit": "^10.5"
    }
}

JSON;
        \file_put_contents($this->cwd . '/composer.json', $initialComposerJson);
    }

    public function testDryRun(): void
    {
        $app = $this->app;
        $args = new InstallOptions();
        $args->preferInstall = InstallPreference::Source;
        $args->dryRun = true;
        $args->optimizeAutoloader = true;
        $args->noDev = true;
        $args->ignorePlatformReqs = true;
        $output = $app->install($args);
        $lines = $output->getLines();

        static::assertOutputLines($lines, '<info>Loading composer repositories with package information</info>');
        static::assertOutputLines($lines, '<info>Updating dependencies</info>');
        static::assertOutputLines($lines, '- Locking <info>composer/ca-bundle</info>');
        static::assertOutputLines($lines, '- Locking <info>composer/composer</info>');
        static::assertOutputLines($lines, '<info>Installing dependencies from lock file</info>');
        static::assertOutputLines($lines, '- Installing <info>composer/ca-bundle</info>');
        static::assertOutputLines($lines, '- Installing <info>composer/composer</info>');

        static::assertFalse(\file_exists($this->cwd . '/composer.lock'));
        static::assertFalse(\file_exists($this->cwd . '/vendor'));
    }

    public function testDownloadOnly(): void
    {
        $app = $this->app;
        $args = new InstallOptions();
        $args->downloadOnly = true;
        $args->ignorePlatformReqs = true;
        $args->noProgress = true;
        $output = $app->install($args);
        $lines = $output->getLines();

        static::assertOutputLines($lines, '<info>Loading composer repositories with package information</info>');
        static::assertOutputLines($lines, '<info>Updating dependencies</info>');
        static::assertOutputLines($lines, '- Locking <info>composer/ca-bundle</info>');
        static::assertOutputLines($lines, '- Locking <info>composer/composer</info>');
        $part = '<info>Installing dependencies from lock file (including require-dev)</info>';
        static::assertOutputLines($lines, $part);
        static::assertOutputLineMissing($lines, '- Installing <info>composer/ca-bundle</info>');
        static::assertOutputLineMissing($lines, '- Installing <info>composer/composer</info>');

        static::assertTrue(\file_exists($this->cwd . '/composer.lock'));
        static::assertFalse(\file_exists($this->cwd . '/vendor'));
    }

    public function testNoAutoloader(): void
    {
        $app = $this->app;
        $args = new InstallOptions();
        $args->noAutoloader = true;
        $args->audit = true;
        $args->auditFormat = AuditFormat::Plain;
        $args->ignorePlatformReq = ['php'];
        $output = $app->install($args);
        $lines = $output->getLines();

        static::assertOutputLines($lines, '<info>Loading composer repositories with package information</info>');
        static::assertOutputLines($lines, '<info>Updating dependencies</info>');
        static::assertOutputLines($lines, '- Locking <info>composer/ca-bundle</info>');
        static::assertOutputLines($lines, '- Locking <info>composer/composer</info>');
        $part = '<info>Installing dependencies from lock file (including require-dev)</info>';
        static::assertOutputLines($lines, $part);
        static::assertOutputLines($lines, '- Installing <info>composer/ca-bundle</info>');
        static::assertOutputLines($lines, '- Installing <info>composer/composer</info>');

        static::assertTrue(\file_exists($this->cwd . '/composer.lock'));
        static::assertTrue(\file_exists($this->cwd . '/vendor'));
        static::assertFalse(\file_exists($this->cwd . '/vendor/autoload.php'));
    }

    public function testClassmapAuthoritative(): void
    {
        $app = $this->app;
        $args = new InstallOptions();
        $args->classmapAuthoritative = true;
        $args->apcuAutoloader = true;
        $args->apcuAutoloaderPrefix = 'apctest';
        $args->audit = true;
        $args->auditFormat = AuditFormat::Table;
        $args->ignorePlatformReq = ['php'];
        $output = $app->install($args);
        $lines = $output->getLines();
        static::assertOutputLines($lines, '<info>Loading composer repositories with package information</info>');
        static::assertOutputLines($lines, '<info>Updating dependencies</info>');
        static::assertOutputLines($lines, '- Locking <info>composer/ca-bundle</info>');
        static::assertOutputLines($lines, '- Locking <info>composer/composer</info>');
        $part = '<info>Installing dependencies from lock file (including require-dev)</info>';
        static::assertOutputLines($lines, $part);
        static::assertOutputLines($lines, '- Installing <info>composer/ca-bundle</info>');
        static::assertOutputLines($lines, '- Installing <info>composer/composer</info>');
        static::assertOutputLines($lines, '<info>No security vulnerability advisories found.</info>');

        static::assertTrue(\file_exists($this->cwd . '/composer.lock'));
        static::assertTrue(\file_exists($this->cwd . '/vendor'));
        static::assertTrue(\file_exists($this->cwd . '/vendor/autoload.php'));
    }
}
