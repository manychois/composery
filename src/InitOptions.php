<?php

declare(strict_types=1);

namespace Manychois\Composery;

use Manychois\Composery\OptionChoices\MinimumStability;
use Manychois\Composery\OptionChoices\PackageType;

/**
 * Represents the options for the `init` command.
 */
class InitOptions extends AbstractCommandOptions
{
    /**
     * Name of the package
     */
    public ?string $name = null;
    /**
     * Description of the package
     */
    public ?string $description = null;
    /**
     * Author name of the package
     */
    public ?string $author = null;
    /**
     * Type of package (e.g. library, project, metapackage, composer-plugin)
     */
    public ?PackageType $type = null;
    /**
     * Homepage of the package
     */
    public ?string $homepage = null;
    /**
     * Packages to require with a version constraint.
     * Should be in format "foo/bar:1.0.0".
     *
     * @var array<string>
     */
    public array $require = [];
    /**
     * Packages to require with a version constraint for development.
     * Should be in format "foo/bar:1.0.0".
     *
     * @var array<string>
     */
    public array $requireDiv = [];
    /**
     * Minimum stability of the package (e.g. dev, alpha, beta, RC, stable)
     */
    public ?MinimumStability $stability = null;
    /**
     * License of the package
     */
    public ?string $license = '';
    /**
     * Provides one (or more) custom repositories.
     * An item is a JSON representation of a repository or an HTTP URL pointing to a composer repository.
     *
     * @var array<string>
     */
    public array $repository = [];
    /**
     * Maps package's namespace to the provided directory.
     * Expects a relative path, e.g. `src/`
     */
    public ?string $autoload = null;

    #region extends AbstractCommandOptions

    /**
     * @inheritDoc
     */
    public function toOptions(): array
    {
        $options = parent::toOptions();

        if ($this->isSpecified($this->name)) {
            $options['--name'] = $this->name;
        }
        if ($this->isSpecified($this->description)) {
            $options['--description'] = $this->description;
        }
        if ($this->isSpecified($this->author)) {
            $options['--author'] = $this->author;
        }
        if ($this->type !== null) {
            $options['--type'] = $this->type->value;
        }
        if ($this->isSpecified($this->homepage)) {
            $options['--homepage'] = $this->homepage;
        }
        if (\count($this->require) > 0) {
            $options['--require'] = $this->require;
        }
        if (\count($this->requireDiv) > 0) {
            $options['--require-dev'] = $this->requireDiv;
        }
        if ($this->stability !== null) {
            $options['--stability'] = $this->stability->value;
        }
        if ($this->isSpecified($this->license)) {
            $options['--license'] = $this->license;
        }
        if (\count($this->repository) > 0) {
            $options['--repository'] = $this->repository;
        }
        if ($this->isSpecified($this->autoload)) {
            $options['--autoload'] = $this->autoload;
        }

        return $options;
    }

    #endregion extends AbstractCommandOptions
}
