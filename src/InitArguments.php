<?php

declare(strict_types=1);

namespace Manychois\Composery;

class InitArguments extends Arguments
{
    /**
     * Name of the package
     */
    public string $name = '';
    /**
     * Description of the package
     */
    public string $description = '';
    /**
     * Author name of the package
     */
    public string $author = '';
    /**
     * Type of package (e.g. library, project, metapackage, composer-plugin)
     */
    public string $type = '';
    /**
     * Homepage of the package
     */
    public string $homepage = '';
    /**
     * 'Package to require with a version constraint, e.g. foo/bar:1.0.0 or foo/bar=1.0.0 or "foo/bar 1.0.0"
     * @var array<string>
     */
    public array $require = [];
    /**
     * Package to require for development with a version constraint, e.g. foo/bar:1.0.0 or foo/bar=1.0.0 or
     * "foo/bar 1.0.0"
     * @var array<string>
     */
    public array $requireDev = [];
    /**
     * Minimum stability of the package (e.g. dev, alpha, beta, RC, stable)
     */
    public string $stability = '';
    /**
     * License of the package
     */
    public string $license = '';
    /**
     * Add custom repositories, either by URL or using JSON arrays.
     * @var array<string>
     */
    public array $repository = [];
    /**
     * Add PSR-4 autoload mapping. Maps your package\'s namespace to the provided directory.
     * (Expects a relative path, e.g. src/)
     */
    public string $autoload = '';

    /**
     * Convert the arguments to an array of options
     * @return array<string, mixed>
     */
    public function toOptions(): array
    {
        $options = parent::toOptions();
        if ($this->name) {
            $options['--name'] = $this->name;
        }
        if ($this->description) {
            $options['--description'] = $this->description;
        }
        if ($this->author) {
            $options['--author'] = $this->author;
        }
        if ($this->type) {
            $options['--type'] = $this->type;
        }
        if ($this->homepage) {
            $options['--homepage'] = $this->homepage;
        }
        if ($this->require) {
            $options['--require'] = $this->require;
        }
        if ($this->requireDev) {
            $options['--require-dev'] = $this->requireDev;
        }
        if ($this->stability) {
            $options['--stability'] = $this->stability;
        }
        if ($this->license) {
            $options['--license'] = $this->license;
        }
        if ($this->repository) {
            $options['--repository'] = $this->repository;
        }
        if ($this->autoload) {
            $options['--autoload'] = $this->autoload;
        }
        return $options;
    }
}
