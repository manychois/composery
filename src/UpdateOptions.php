<?php

declare(strict_types=1);

namespace Manychois\Composery;

use Manychois\Composery\OptionChoices\AuditFormat;
use Manychois\Composery\OptionChoices\InstallPreference;

/**
 * Represents the options for the `update` command.
 */
class UpdateOptions extends AbstractCommandOptions
{
    /**
     * Sets how composer should install a package.
     */
    public ?InstallPreference $preferInstall = null;

    /**
     * If true, simulates the installation without actually installing a package.
     */
    public bool $dryRun = false;

    /**
     * If true, skips installing packages listed in `require-dev`.
     * The autoloader generation skips the `autoload-dev` rules.
     */
    public bool $noDev = false;

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
     * If true, overwrites the lock file hash to suppress warning about the lock file being out of date without updating
     * package versions.
     * Package metadata like mirrors and URLs are updated if they changed.
     */
    public bool $lock = false;

    /**
     * Temporary version constraint to add, e.g. `foo/bar:1.0.0` or `foo/bar=1.0.0`.
     *
     * @var string[]
     */
    public array $with = [];

    /**
     * Skips autoloader generation.
     */
    public bool $noAutoloader = false;

    /**
     * Removes the progress display that can mess with some terminals or scripts which don't handle backspace
     * characters.
     */
    public bool $noProgress = false;

    /**
     * Update also dependencies of packages in the argument list, except those which are root requirements.
     */
    public bool $withDependencies = false;

    /**
     * Update also dependencies of packages in the argument list, including those which are root requirements.
     */
    public bool $withAllDependencies = false;

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
     * Restricts the update to your first degree dependencies.
     */
    public bool $rootReqs = false;

    #region extends AbstractCommandOptions

    /**
     * @inheritDoc
     */
    public function toOptions(): array
    {
        $options = parent::toOptions();
        if ($this->preferInstall !== null) {
            $options['--prefer-install'] = $this->preferInstall->value;
        }
        if ($this->dryRun) {
            $options['--dry-run'] = true;
        }
        if ($this->noDev) {
            $options['--no-dev'] = true;
        }
        if ($this->noInstall) {
            $options['--no-install'] = true;
        }
        if ($this->noAudit) {
            $options['--no-audit'] = true;
        }
        if ($this->auditFormat !== null) {
            $options['--audit-format'] = $this->auditFormat->value;
        }
        if ($this->lock) {
            $options['--lock'] = true;
        }
        if (\count($this->with) > 0) {
            $options['--with'] = $this->with;
        }
        if ($this->noAutoloader) {
            $options['--no-autoloader'] = true;
        }
        if ($this->noProgress) {
            $options['--no-progress'] = true;
        }
        if ($this->withDependencies) {
            $options['--with-dependencies'] = true;
        }
        if ($this->withAllDependencies) {
            $options['--with-all-dependencies'] = true;
        }
        if ($this->optimizeAutoloader) {
            $options['--optimize-autoloader'] = true;
        }
        if ($this->classmapAuthoritative) {
            $options['--classmap-authoritative'] = true;
        }
        if ($this->apcuAutoloader) {
            $options['--apcu-autoloader'] = true;
        }
        if ($this->apcuAutoloaderPrefix !== null) {
            $options['--apcu-autoloader-prefix'] = $this->apcuAutoloaderPrefix;
        }
        if ($this->ignorePlatformReqs) {
            $options['--ignore-platform-reqs'] = true;
        }
        if (\count($this->ignorePlatformReq) > 0) {
            $options['--ignore-platform-req'] = $this->ignorePlatformReq;
        }
        if ($this->preferStable) {
            $options['--prefer-stable'] = true;
        }
        if ($this->preferLowest) {
            $options['--prefer-lowest'] = true;
        }
        if ($this->rootReqs) {
            $options['--root-reqs'] = true;
        }

        return $options;
    }

    #endregion extends AbstractCommandOptions
}
