<?php

declare(strict_types=1);

namespace Manychois\Composery;

class InstallArguments
{
    /**
     * Sets how composer should install a package:
     * 
     * + `dist`: Default. Install from dist.
     * + `source`: Install from source.
     * + `auto`: Install from source if the package is a dev version, otherwise install from dist.
     */
    public string $preferInstall = '';

    /**
     * Simulates the installation without actually installing a package.
     */
    public bool $dryRun = false;

    /**
     * Download only, do not install packages.
     */
    public bool $downloadOnly = false;

    /**
     * Skip installing packages listed in require-dev.
     * The autoloader generation skips the autoload-dev rules.
     */
    public bool $noDev = false;

    /**
     * Skips autoloader generation.
     */
    public bool $noAutoloader = false;

    /**
     * Removes the progress display that can mess with some terminals or scripts which don't handle backspace characters.
     */
    public bool $noProgress = false;

    /**
     * Run an audit after installation is complete.
     */
    public bool $audit = false;

    /**
     * Audit output format. Must be `table`, `plain`, `json`, or `summary` (default).
     */
    public string $auditFormat = '';

    /**
     * Convert PSR-0/4 autoloading to classmap to get a faster autoloader.
     * This is recommended especially for production, but can take a bit of time to run so it is currently not done by default.
     * @var bool
     */
    public bool $optimizeAutoloader = false;

    /**
     * Autoload classes from the classmap only.
     * Implicitly enables `--optimize-autoloader`.
     */
    public bool $classmapAuthoritative = false;

    /**
     * Use APCu to cache found/not-found classes.
     * @var bool
     */
    public bool $apcuAutoloader = false;

    /**
     * Use a custom prefix for the APCu autoloader cache. Implicitly enables `--apcu-autoloader`.
     */
    public string $apcuAutoloaderPrefix = '';

    /**
     * Ignore all platform requirements (php, hhvm, lib-* and ext-*) and force the installation even if the local machine does not fulfill these.
     */
    public bool $ignorePlatformReqs = false;

    /**
     * Ignore a specific platform requirement(php, hhvm, lib-* and ext-*) and force the installation even if the local machine does not fulfill it.
     * @var string
     */
    public string $ignorePlatformReq = '';

    /**
     * Convert the arguments to an array of options
     * @return array
     */
    public function toOptions(): array
    {
        $options = [];
        if ($this->preferInstall) {
            $options['--prefer-install'] = $this->preferInstall;
        }
        if ($this->dryRun) {
            $options['--dry-run'] = $this->dryRun;
        }
        if ($this->noDev) {
            $options['--no-dev'] = $this->noDev;
        }
        return $options;
    }
}
