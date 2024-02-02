<?php

declare(strict_types=1);

namespace Manychois\Composery;

/**
 * Represents the global options for all Composer commands.
 */
abstract class AbstractCommandOptions
{
    /**
     * If true, increases verbosity of messages.
     */
    public bool $verbose = false;

    /**
     * If true, displays help information.
     */
    public bool $help = false;

    /**
     * If true, does not output any message.
     */
    public bool $quiet = false;

    /**
     * If true, does not ask any interactive question.
     */
    public bool $noInteraction = false;

    /**
     * If true, disables plugins.
     */
    public bool $noPlugins = false;

    /**
     * If true, skips execution of scripts defined in `composer.json`.
     */
    public bool $noScripts = false;

    /**
     * If true, disables the use of the cache directory.
     * Same as setting the `COMPOSER_CACHE_DIR` env var to `/dev/null` (or `NUL` on Windows).
     */
    public bool $noCache = false;

    /**
     * If specified, uses the given directory as working directory.
     */
    public ?string $workingDir = null;

    /**
     * If true, displays timing and memory usage information.
     */
    public bool $profile = false;

    /**
     * If true, forces ANSI output.
     */
    public bool $ansi = false;

    /**
     * If true, disables ANSI output.
     */
    public bool $noAnsi = false;

    /**
     * If true, displays this application version.
     */
    public bool $version = false;

    /**
     * Convert the arguments to an array of options
     *
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
        if ($this->isSpecified($this->workingDir)) {
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

    /**
     * Returns whether the given value is not null and not empty.
     *
     * @param null|string $value The value to check.
     *
     * @return bool True if the value is not null and not empty.
     */
    protected function isSpecified(?string $value): bool
    {
        return $value !== null && \trim($value) !== '';
    }
}
