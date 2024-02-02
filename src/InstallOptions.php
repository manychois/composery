<?php

declare(strict_types=1);

namespace Manychois\Composery;

use Manychois\Composery\OptionChoices\AuditFormat;
use Manychois\Composery\OptionChoices\InstallPreference;

/**
 * Represents the options for the `install` command.
 */
class InstallOptions extends AbstractCommandOptions
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
     * If true, downloads only and does not install packages.
     */
    public bool $downloadOnly = false;

    /**
     * If true, skips installing packages listed in `require-dev`.
     * The autoloader generation skips the `autoload-dev` rules.
     */
    public bool $noDev = false;

    /**
     * If true, skips autoloader generation.
     */
    public bool $noAutoloader = false;

    /**
     * If true, removes the progress display that can mess with some terminals or scripts which don't handle backspace
     * characters.
     */
    public bool $noProgress = false;

    /**
     * If true, runs an audit after installation is complete.
     */
    public bool $audit = false;

    /**
     * Audit output format.
     */
    public ?AuditFormat $auditFormat = null;

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

    #region extends AbstractArguments

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
        if ($this->downloadOnly) {
            $options['--download-only'] = true;
        }
        if ($this->noDev) {
            $options['--no-dev'] = true;
        }
        if ($this->noAutoloader) {
            $options['--no-autoloader'] = true;
        }
        if ($this->noProgress) {
            $options['--no-progress'] = true;
        }
        if ($this->audit) {
            $options['--audit'] = true;
        }
        if ($this->auditFormat !== null) {
            $options['--audit-format'] = $this->auditFormat->value;
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

        return $options;
    }

    #endregion extends AbstractArguments
}
