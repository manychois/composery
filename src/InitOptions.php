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
    public function toParameters(): array
    {
        $parameters = parent::toParameters();
        if ($this->isSpecified($this->name)) {
            $parameters['--name'] = $this->name;
        }
        if ($this->isSpecified($this->description)) {
            $parameters['--description'] = $this->description;
        }
        if ($this->isSpecified($this->author)) {
            $parameters['--author'] = $this->author;
        }
        if ($this->type !== null) {
            $parameters['--type'] = $this->type->value;
        }
        if ($this->isSpecified($this->homepage)) {
            $parameters['--homepage'] = $this->homepage;
        }
        if (\count($this->require) > 0) {
            $parameters['--require'] = $this->require;
        }
        if (\count($this->requireDiv) > 0) {
            $parameters['--require-dev'] = $this->requireDiv;
        }
        if ($this->stability !== null) {
            $parameters['--stability'] = $this->stability->value;
        }
        if ($this->isSpecified($this->license)) {
            $parameters['--license'] = $this->license;
        }
        if (\count($this->repository) > 0) {
            $parameters['--repository'] = $this->repository;
        }
        if ($this->isSpecified($this->autoload)) {
            $parameters['--autoload'] = $this->autoload;
        }

        return $parameters;
    }

    #endregion extends AbstractCommandOptions
}
