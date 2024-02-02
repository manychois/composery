<?php

declare(strict_types=1);

namespace Manychois\Composery\ArgumentOptions;

enum InstallPreference: string
{
    /**
     * Default. Install from dist.
     */
    case Dist = 'dist';
    /**
     * Install from source.
     */
    case Source = 'source';
    /**
     * Install from source if the package is a dev version, otherwise install from dist.
     */
    case Auto = 'auto';
}
