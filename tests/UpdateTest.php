<?php

declare(strict_types=1);

namespace Manychois\ComposeryTests;

use Manychois\Composery\App;
use Manychois\Composery\OptionChoices\AuditFormat;
use Manychois\Composery\OptionChoices\InstallPreference;
use Manychois\Composery\UpdateOptions;

class UpdateTest extends AbstractCommandTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $filesToCopy = ['composer.json', 'composer.lock'];
        foreach ($filesToCopy as $file) {
            $success = \copy(__DIR__ . "/$file", $this->cwd . "/$file");
            if (!$success) {
                throw new \Exception("Failed to copy $file");
            }
        }
    }

    public function testDryRun(): void
    {
        $app = new App();
        $args = new UpdateOptions();
        $args->preferInstall = InstallPreference::Source;
        $args->dryRun = true;
        $args->noDev = true;
        $args->noInstall = true;
        $args->noAudit = true;
        $args->noAutoloader = true;
        $args->noProgress = true;
        $args->withDependencies = true;
        $args->classmapAuthoritative = true;
        $args->apcuAutoloader = true;
        $args->apcuAutoloaderPrefix = 'apcutest';
        $args->ignorePlatformReq = ['php'];
        $args->preferStable = true;
        $args->preferLowest = true;
        $args->rootReqs = true;
        $output = $app->update($args);
        $lines = $output->getLines();

        static::assertOutputLines($lines, '<info>Loading composer repositories with package information</info>');
        static::assertOutputLines($lines, '<info>Updating dependencies</info>');
        static::assertOutputLines($lines, '<info>Lock file operations:');
        static::assertOutputLineMissing($lines, 'Run "composer audit" for a full list of advisories.');
    }

    public function testAuditFormat(): void
    {
        $app = new App();
        $args = new UpdateOptions();
        $args->auditFormat = AuditFormat::Plain;
        $args->lock = true;
        $args->with = ['phpstan/phpstan:^1.10'];
        $args->optimizeAutoloader = true;
        $output = $app->update($args);
        $lines = $output->getLines();

        static::assertOutputLines($lines, '<info>Loading composer repositories with package information</info>');
        static::assertOutputLines($lines, '<info>Updating dependencies</info>');
        static::assertOutputLines($lines, 'Nothing to modify in lock file');
        static::assertOutputLines($lines, '<info>Writing lock file</info>');
        $part = '<info>Installing dependencies from lock file (including require-dev)</info>';
        static::assertOutputLines($lines, $part);
        static::assertOutputLines($lines, '<info>Generating optimized autoload files</info>');
        $part = '<warning>Found 1 security vulnerability advisory affecting 1 package:</warning>';
        static::assertOutputLines($lines, $part);
        static::assertOutputLines($lines, 'Package: composer/composerCVE');
    }

    public function testPackageWithAllDependencies(): void
    {
        $app = new App();

        $app->runInput('install');

        $args = new UpdateOptions();
        $args->withDependencies = true;
        $args->ignorePlatformReqs = true;
        $output = $app->update($args, 'composer/composer');
        $lines = $output->getLines();

        static::assertOutputLines($lines, '<info>Loading composer repositories with package information</info>');
        static::assertOutputLines($lines, '<info>Updating dependencies</info>');
        static::assertOutputLineMissing($lines, 'Installing <info>sebastian/version</info>');
        static::assertOutputLineMissing($lines, 'Installing <info>phpunit/php-timer</info>');
    }
}
