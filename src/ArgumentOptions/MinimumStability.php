<?php

declare(strict_types=1);

namespace Manychois\Composery\ArgumentOptions;

enum MinimumStability: string
{
    case Stable = 'stable';
    case Rc = 'RC';
    case Beta = 'beta';
    case Alpha = 'alpha';
    case Dev = 'dev';
}
