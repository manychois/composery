<?php

declare(strict_types=1);

namespace Manychois\Composery;

use Manychois\Composery\OptionChoices\AuditFormat;
use Manychois\Composery\OptionChoices\InstallPreference;

/**
 * Represents the options for the `require` command.
 */
class RequireOptions extends AbstractCommandOptions
{
    /**
     * If true, adds packages to `require-dev`.
     */
    public bool $dev = false;

    /**
     * If true, simulates the command without actually doing anything.
     */
    public bool $dryRun = false;

    /**
     * Sets how composer should install a package.
     */
    public ?InstallPreference $preferInstall = null;

    /**
     * If true, removes the progress display that can mess with some terminals or scripts which don't handle backspace
     * characters.
     */
    public bool $noProgress = false;

    /**
     * If true, disables the automatic update of the dependencies (implies `--no-install`).
     */
    public bool $noUpdate = false;

    /**
     * If true, does not run the install step after updating the composer.lock file.
     */
    public bool $noInstall = false;

    /**
     * If true, does not run the audit steps after updating the composer.lock file.
     */
    public bool $noAudit = false;

    /**
     * Audit output format.
     */
    public ?AuditFormat $auditFormat = null;

    /**
     * If true, runs the dependency update with the `--no-dev` option.
     */
    public bool $updateNoDev = false;

    /**
     * If true, also updates dependencies of the newly required packages, except those that are root requirements.
     */
    public bool $updateWithDependencies = false;

    /**
     * If true, also updates dependencies of the newly required packages, including those that are root requirements.
     */
    public bool $updateWithAllDependencies = false;

    /**
     * If true, ignores all platform requirements (php, hhvm, lib-* and ext-*) and forces the installation even if the
     * local machine does not fulfill these.
     */
    public bool $ignorePlatformReqs = false;

    /**
     * Ignores a specific platform requirement(php, hhvm, lib-* and ext-*) and forces the installation even if the local
     * machine does not fulfill it.
     * Multiple requirements can be ignored via wildcard.
     * Appending a + makes it only ignore the upper-bound of the requirements.
     *
     * @var array<string>
     */
    public array $ignorePlatformReq = [];

    /**
     * Prefers stable versions of dependencies.
     */
    public bool $preferStable = false;

    /**
     * Prefers lowest versions of dependencies.
     * Useful for testing minimal versions of requirements, generally used with `--prefer-stable`.
     */
    public bool $preferLowest = false;

    /**
     * If true, keeps packages sorted in `composer.json`.
     */
    public bool $sortPackages = false;

    /**
     * If true, converts PSR-0/4 autoloading to classmap to get a faster autoloader.
     * This is recommended especially for production, but can take a bit of time to run so it is currently not done by
     * default.
     */
    public bool $optimizeAutoloader = false;

    /**
     * If true, autoloads classes from the classmap only.
     * Implicitly enables `--optimize-autoloader`.
     */
    public bool $classmapAuthoritative = false;

    /**
     * If true, uses APCu to cache found/not-found classes.
     */
    public bool $apcuAutoloader = false;

    /**
     * Uses a custom prefix for the APCu autoloader cache.
     * Implicitly enables `--apcu-autoloader`.
     */
    public ?string $apcuAutoloaderPrefix = null;

    #region extends AbstractCommandOptions

    /**
     * @inheritDoc
     */
    public function toParameters(): array
    {
        $parameters = parent::toParameters();
        if ($this->dev) {
            $parameters['--dev'] = true;
        }
        if ($this->dryRun) {
            $parameters['--dry-run'] = true;
        }
        if ($this->preferInstall !== null) {
            $parameters['--prefer-install'] = $this->preferInstall->value;
        }
        if ($this->noProgress) {
            $parameters['--no-progress'] = true;
        }
        if ($this->noUpdate) {
            $parameters['--no-update'] = true;
        }
        if ($this->noInstall) {
            $parameters['--no-install'] = true;
        }
        if ($this->noAudit) {
            $parameters['--no-audit'] = true;
        }
        if ($this->auditFormat !== null) {
            $parameters['--audit-format'] = $this->auditFormat->value;
        }
        if ($this->updateNoDev) {
            $parameters['--update-no-dev'] = true;
        }
        if ($this->updateWithDependencies) {
            $parameters['--update-with-dependencies'] = true;
        }
        if ($this->updateWithAllDependencies) {
            $parameters['--update-with-all-dependencies'] = true;
        }
        if ($this->ignorePlatformReqs) {
            $parameters['--ignore-platform-reqs'] = true;
        }
        if (\count($this->ignorePlatformReq) > 0) {
            $parameters['--ignore-platform-req'] = $this->ignorePlatformReq;
        }
        if ($this->preferStable) {
            $parameters['--prefer-stable'] = true;
        }
        if ($this->preferLowest) {
            $parameters['--prefer-lowest'] = true;
        }
        if ($this->sortPackages) {
            $parameters['--sort-packages'] = true;
        }
        if ($this->optimizeAutoloader) {
            $parameters['--optimize-autoloader'] = true;
        }
        if ($this->classmapAuthoritative) {
            $parameters['--classmap-authoritative'] = true;
        }
        if ($this->apcuAutoloader) {
            $parameters['--apcu-autoloader'] = true;
        }
        if ($this->apcuAutoloaderPrefix !== null) {
            $parameters['--apcu-autoloader-prefix'] = $this->apcuAutoloaderPrefix;
        }

        return $parameters;
    }

    #endregion extends AbstractCommandOptions
}
