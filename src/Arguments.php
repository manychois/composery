<?php

declare(strict_types=1);

namespace Manychois\Composery;

abstract class Arguments
{
    /**
     * Increase verbosity of messages.
     */
    public bool $verbose = false;

    /**
     * Display help information.
     */
    public bool $help = false;

    /**
     * Do not output any message.
     */
    public bool $quiet = false;

    /**
     * Do not ask any interactive question.
     */
    public bool $noInteraction = false;

    /**
     * Disables plugins.
     */
    public bool $noPlugins = false;

    /**
     * Skips execution of scripts defined in `composer.json`.
     */
    public bool $noScripts = false;

    /**
     * Disables the use of the cache directory. Same as setting the COMPOSER_CACHE_DIR env var to /dev/null
     * (or NUL on Windows).
     */
    public bool $noCache = false;

    /**
     * If specified, use the given directory as working directory.
     */
    public string $workingDir = '';

    /**
     * Display timing and memory usage information
     */
    public bool $profile = false;

    /**
     * Force ANSI output.
     */
    public bool $ansi = false;

    /**
     * Disable ANSI output.
     */
    public bool $noAnsi = false;

    /**
     * Display this application version.
     */
    public bool $version = false;

    /**
     * Convert the arguments to an array of options
     * @return array<string, mixed>
     */
    public function toOptions(): array
    {
        $options = [];
        if ($this->verbose) {
            $options['--verbose'] = true;
        }
        if ($this->help) {
            $options['--help'] = true;
        }
        if ($this->quiet) {
            $options['--quiet'] = true;
        }
        if ($this->noInteraction) {
            $options['--no-interaction'] = true;
        }
        if ($this->noPlugins) {
            $options['--no-plugins'] = true;
        }
        if ($this->noScripts) {
            $options['--no-scripts'] = true;
        }
        if ($this->noCache) {
            $options['--no-cache'] = true;
        }
        if ($this->workingDir) {
            $options['--working-dir'] = $this->workingDir;
        }
        if ($this->profile) {
            $options['--profile'] = true;
        }
        if ($this->ansi) {
            $options['--ansi'] = true;
        }
        if ($this->noAnsi) {
            $options['--no-ansi'] = true;
        }
        if ($this->version) {
            $options['--version'] = true;
        }
        return $options;
    }
}
