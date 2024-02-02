<?php

declare(strict_types=1);

namespace Manychois\ComposeryTests;

use Manychois\Composery\App;
use Manychois\Composery\ArgumentOptions\AuditFormat;
use Manychois\Composery\ArgumentOptions\InstallPreference;
use Manychois\Composery\ArgumentOptions\MinimumStability;
use Manychois\Composery\ArgumentOptions\PackageType;
use Manychois\Composery\InitArguments;
use Manychois\Composery\InstallArguments;

use function PHPUnit\Framework\directoryExists;

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

    public function testInstallDryRun(): void
    {
        $app = new App();
        $args = new InstallArguments();
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
        $app = new App();
        $args = new InstallArguments();
        $args->downloadOnly = true;
        $args->ignorePlatformReqs = true;
        $args->noProgress = true;
        $output = $app->install($args);
        $lines = $output->getLines();

        static::assertOutputLines($lines, '<info>Loading composer repositories with package information</info>');
        static::assertOutputLines($lines, '<info>Updating dependencies</info>');
        static::assertOutputLines($lines, '- Locking <info>composer/ca-bundle</info>');
        static::assertOutputLines($lines, '- Locking <info>composer/composer</info>');
        static::assertOutputLines($lines, '<info>Installing dependencies from lock file (including require-dev)</info>');
        static::assertOutputLineMissing($lines, '- Installing <info>composer/ca-bundle</info>');
        static::assertOutputLineMissing($lines, '- Installing <info>composer/composer</info>');
        
        static::assertTrue(\file_exists($this->cwd . '/composer.lock'));
        static::assertFalse(\file_exists($this->cwd . '/vendor'));
    }

    public function testNoAutoloader(): void
    {
        $app = new App();
        $args = new InstallArguments();
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
        static::assertOutputLines($lines, '<info>Installing dependencies from lock file (including require-dev)</info>');
        static::assertOutputLines($lines, '- Installing <info>composer/ca-bundle</info>');
        static::assertOutputLines($lines, '- Installing <info>composer/composer</info>');

        static::assertTrue(\file_exists($this->cwd . '/composer.lock'));
        static::assertTrue(\file_exists($this->cwd . '/vendor'));
        static::assertFalse(\file_exists($this->cwd . '/vendor/autoload.php'));
    }

    public function testClassmapAuthoritative(): void
    {
        $app = new App();
        $args = new InstallArguments();
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
        static::assertOutputLines($lines, '<info>Installing dependencies from lock file (including require-dev)</info>');
        static::assertOutputLines($lines, '- Installing <info>composer/ca-bundle</info>');
        static::assertOutputLines($lines, '- Installing <info>composer/composer</info>');
        static::assertOutputLines($lines, '<info>No security vulnerability advisories found</info>');

        static::assertTrue(\file_exists($this->cwd . '/composer.lock'));
        static::assertTrue(\file_exists($this->cwd . '/vendor'));
        static::assertTrue(\file_exists($this->cwd . '/vendor/autoload.php'));
    }
}