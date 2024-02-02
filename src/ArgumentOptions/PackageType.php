<?php

declare(strict_types=1);

namespace Manychois\Composery\ArgumentOptions;

enum PackageType: string
{
    case Library = 'library';
    case Project = 'project';
    case Metapackage = 'metapackage';
    case ComposerPlugin = 'composer-plugin';
}
